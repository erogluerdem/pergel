<?php

namespace App\Http\Controllers;

use App\Models\KvkkConsent;
use App\Models\Customer;
use Illuminate\Http\Request;

class KvkkConsentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // KVKK Onay listesi
    public function index()
    {
        $consents = KvkkConsent::with('customer')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('crm.kvkk-consents.index', compact('consents'));
    }

    // Yeni KVKK Onay formu
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        return view('crm.kvkk-consents.create', compact('customers'));
    }

    // KVKK Onay kaydet
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'phone' => 'required|regex:/^[0-9\+\-\s()]+$/|min:10',
            'email' => 'nullable|email',
            'consent_type' => 'required|in:sms,email,both',
            'notes' => 'nullable|string',
        ], [
            'phone.required' => 'Telefon numarası zorunludur',
            'phone.regex' => 'Geçerli bir telefon numarası giriniz',
            'consent_type.required' => 'Onay türü seçimi zorunludur',
        ]);

        $smsCode = KvkkConsent::generateSmsCode();
        $expiryDate = now()->addDays(1); // 24 saat geçerli

        $consent = KvkkConsent::create(array_merge($validated, [
            'sms_code' => $smsCode,
            'consent_date' => now(),
            'expiry_date' => $expiryDate,
            'consent_status' => 'pending'
        ]));

        // SMS gönder (Gerçek SMS servisiyle entegre edilebilir)
        // Mail gönder (Gerçek Email servisiyle entegre edilebilir)
        
        return redirect()->route('kvkk-consents.show', $consent)->with('success', 
            "KVKK Onay talebi oluşturuldu. Doğrulama kodu: {$smsCode}");
    }

    // KVKK Onay detay
    public function show($id)
    {
        $kvkkConsent = KvkkConsent::with('customer')->findOrFail($id);
        return view('crm.kvkk-consents.show', compact('kvkkConsent'));
    }

    // KVKK Onay düzenle formu
    public function edit($id)
    {
        $kvkkConsent = KvkkConsent::findOrFail($id);
        $customers = Customer::orderBy('name')->get();
        return view('crm.kvkk-consents.edit', compact('kvkkConsent', 'customers'));
    }

    // KVKK Onay güncelle
    public function update(Request $request, $id)
    {
        $kvkkConsent = KvkkConsent::findOrFail($id);

        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'phone' => 'required|regex:/^[0-9\+\-\s()]+$/|min:10',
            'email' => 'nullable|email',
            'consent_type' => 'required|in:sms,email,both',
            'consent_status' => 'required|in:pending,verified,rejected,expired',
            'notes' => 'nullable|string',
        ], [
            'phone.required' => 'Telefon numarası zorunludur',
            'phone.regex' => 'Geçerli bir telefon numarası giriniz',
            'consent_type.required' => 'Onay türü seçimi zorunludur',
        ]);

        $kvkkConsent->update($validated);

        return redirect()->route('kvkk-consents.show', $kvkkConsent->id)
            ->with('success', 'KVKK Onayı bilgileri başarıyla güncellendi.');
    }

    // KVKK Onay sil
    public function destroy($id)
    {
        $kvkkConsent = KvkkConsent::findOrFail($id);
        $kvkkConsent->delete();

        return redirect()->route('kvkk-consents.index')
            ->with('success', 'KVKK Onay kaydı başarıyla silindi.');
    }

    // SMS Kodu doğrula
    public function verifySms(Request $request, $id)
    {
        $kvkkConsent = KvkkConsent::findOrFail($id);

        if ($kvkkConsent->isCodeExpired()) {
            return back()->with('error', 'Doğrulama kodu süresi dolmuş. Lütfen yeniden talep ediniz.');
        }

        if ($kvkkConsent->maxAttemptsReached()) {
            return back()->with('error', 'Maksimum deneme sayısına ulaşıldı. Lütfen daha sonra tekrar deneyiniz.');
        }

        $validated = $request->validate([
            'code' => 'required|digits:6'
        ], [
            'code.required' => 'Doğrulama kodu giriniz',
            'code.digits' => 'Doğrulama kodu 6 haneli olmalıdır',
        ]);

        $kvkkConsent->increment('verification_attempts');
        $kvkkConsent->update(['last_attempt_at' => now()]);

        if ($validated['code'] === $kvkkConsent->sms_code) {
            $kvkkConsent->update([
                'sms_verified_at' => now(),
                'consent_status' => 'verified'
            ]);

            return redirect()->route('kvkk-consents.show', $kvkkConsent->id)
                ->with('success', 'SMS Doğrulama başarılı. KVKK Onayı tamamlanmıştır.');
        }

        return back()->with('error', 'Doğrulama kodu yanlış. Lütfen tekrar deneyiniz.');
    }

    // Email Doğrulama Linki Gönder
    public function sendEmailVerification($id)
    {
        $kvkkConsent = KvkkConsent::findOrFail($id);

        if (!$kvkkConsent->email) {
            return back()->with('error', 'Email adresi belirtilmemiştir.');
        }

        return back()->with('success', 'Doğrulama linki email adresine gönderilmiştir.');
    }

    // Email Doğrulama Linki
    public function verifyEmail(Request $request, $token)
    {
        $kvkkConsent = KvkkConsent::where('sms_code', 'like', "%{$token}%")->first();

        if (!$kvkkConsent) {
            return redirect()->route('kvkk-consents.index')->with('error', 'Geçersiz doğrulama linki.');
        }

        $kvkkConsent->update([
            'email_verified_at' => now(),
            'consent_status' => $kvkkConsent->sms_verified_at ? 'verified' : 'pending'
        ]);

        return redirect()->route('kvkk-consents.show', $kvkkConsent->id)
            ->with('success', 'Email doğrulama başarılı.');
    }

    // KVKK Onayı Reddet
    public function reject($id)
    {
        $kvkkConsent = KvkkConsent::findOrFail($id);
        $kvkkConsent->update(['consent_status' => 'rejected']);

        return back()->with('success', 'KVKK Onayı reddedilmiştir.');
    }

    // Süresi dolmuş onayları temizle
    public static function cleanExpiredConsents()
    {
        KvkkConsent::where('consent_status', 'pending')
            ->where('expiry_date', '<', now())
            ->update(['consent_status' => 'expired']);
    }
}

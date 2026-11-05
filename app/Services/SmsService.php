<?php

namespace App\Services;

use App\Models\KvkkConsent;
use Illuminate\Support\Facades\Log;

class SmsService
{
    private $apiUrl;
    private $apiKey;
    private $apiSecret;
    private $sender;

    public function __construct()
    {
        $this->apiUrl = config('services.sms.url');
        $this->apiKey = config('services.sms.api_key');
        $this->apiSecret = config('services.sms.api_secret');
        $this->sender = config('services.sms.sender', 'PERGEL');
    }

    /**
     * SMS gönder
     */
    public function send(string $phoneNumber, string $message): bool
    {
        try {
            if (!$this->apiUrl || !$this->apiKey) {
                Log::warning('SMS service not configured', ['phone' => $phoneNumber]);
                return false;
            }

            // Telefon numarasını format et (0 ile başlıyorsa 90'ya çevir)
            $phoneNumber = $this->formatPhoneNumber($phoneNumber);

            // Eğer gerçek SMS servisi yoksa test mode'da log'la
            if (config('app.env') === 'local' || config('services.sms.test_mode')) {
                Log::info('SMS (Test Mode)', [
                    'to' => $phoneNumber,
                    'message' => $message,
                    'timestamp' => now(),
                ]);
                return true;
            }

            // Gerçek SMS API'ye gönder (Örnek: Iletişim Merkezi, Twilio, vs)
            $response = $this->sendViaApi($phoneNumber, $message);

            return $response['success'] ?? false;
        } catch (\Exception $e) {
            Log::error('SMS Send Error', [
                'error' => $e->getMessage(),
                'phone' => $phoneNumber,
            ]);
            return false;
        }
    }

    /**
     * KVKK SMS doğrulama kodu gönder
     */
    public function sendKvkkVerificationCode(string $phoneNumber): ?KvkkConsent
    {
        try {
            $code = KvkkConsent::generateSmsCode();
            $message = "PERGEL KVKK Onay Kodu: {$code}\n\nBu kodu 15 dakika içinde giriniz.";

            if ($this->send($phoneNumber, $message)) {
                // Veritabanına kaydını güncelle
                $kvkkConsent = KvkkConsent::where('phone', $phoneNumber)
                    ->where('consent_status', 'pending')
                    ->first();

                if ($kvkkConsent) {
                    $kvkkConsent->update([
                        'sms_code' => $code,
                        'verification_attempts' => 0,
                    ]);
                }

                Log::info('KVKK SMS sent successfully', [
                    'phone' => $this->formatPhoneNumber($phoneNumber),
                ]);

                return $kvkkConsent;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('KVKK SMS Error', [
                'error' => $e->getMessage(),
                'phone' => $phoneNumber,
            ]);
            return null;
        }
    }

    /**
     * Promosyon SMS'i gönder
     */
    public function sendPromotional(string $phoneNumber, string $campaignMessage): bool
    {
        $message = "{$campaignMessage}\n\nBiz ile iletişime geçmek için: www.pergel.com";
        return $this->send($phoneNumber, $message);
    }

    /**
     * Telefon numarasını format et
     */
    private function formatPhoneNumber(string $phoneNumber): string
    {
        // Boşlukları ve özel karakterleri kaldır
        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

        // Eğer 0 ile başlıyorsa 90'ya çevir
        if (str_starts_with($phoneNumber, '0')) {
            $phoneNumber = '90' . substr($phoneNumber, 1);
        }

        // Eğer 90 ile başlamıyorsa ekle
        if (!str_starts_with($phoneNumber, '90')) {
            $phoneNumber = '90' . $phoneNumber;
        }

        return $phoneNumber;
    }

    /**
     * SMS API'ye gönder (Örnek implementasyon)
     */
    private function sendViaApi(string $phoneNumber, string $message): array
    {
        try {
            // Bu örnek cURL ile implementasyon gösterir
            // Gerçek SMS servisinize göre değiştirin (Iletişim Merkezi, Twilio, etc)

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $this->apiUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode([
                    'phone' => $phoneNumber,
                    'message' => $message,
                    'sender' => $this->sender,
                    'api_key' => $this->apiKey,
                ]),
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $this->apiSecret,
                ],
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $data = json_decode($response, true);
                return ['success' => $data['status'] === 'success'];
            }

            return ['success' => false];
        } catch (\Exception $e) {
            Log::error('SMS API Error', ['error' => $e->getMessage()]);
            return ['success' => false];
        }
    }
}

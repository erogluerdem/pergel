<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\Project;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $projectId = $request->query('project_id');

        if (Schema::hasTable('payments')) {
            $payments = \App\Models\Payment::when($projectId, function($q) use ($projectId){ return $q->where('project_id',$projectId); })->orderBy('created_at','desc')->get();
        } else {
            $payments = \App\Models\Payment::with('project')->get();
        }

        $projects = Project::all();

        return view('cost.payments.index', compact('payments','projects'));
    }

    public function show($id)
    {
        if (Schema::hasTable('payments')) {
            $payment = \App\Models\Payment::with('items.evidences')->find($id);
            if (!$payment) abort(404);
        } else {
            $payment = (object)[
                'id' => $id,
                'no' => 'HK-021-003',
                'contractor' => 'A İnşaat',
                'contract' => 'Kaba İnşaat Sözleşmesi',
                'date' => '2025-09-25',
                'items' => [
                    ['id'=>1,'pos' => '160.01.001','desc' => 'C30 Beton','unit' => 'm³','contract_qty' => 1200,'prev_qty' => 450,'this_qty' => 250,'total_qty' => 700,'unit_price' => 2000,'this_amount' => 500000,'evidence_count' => 3],
                    ['id'=>2,'pos' => '160.02.005','desc' => 'Nervürlü Demir','unit' => 'ton','contract_qty' => 85,'prev_qty' => 30,'this_qty' => 15,'total_qty' => 45,'unit_price' => 25000,'this_amount' => 375000,'evidence_count' => 2],
                ],
                'deductions' => [ ['label' => 'Teminat Kesintisi (%10)','amount' => -97500], ['label' => 'Avans Mahsubu','amount' => -50000] ],
                'vat_percent' => 20,
                'history' => [
                    ['when' => '2025-09-25 14:30','who' => 'A İnşaat','note' => 'Hakediş gönderildi.'],
                    ['when' => '2025-09-26 11:00','who' => 'Saha Şefi Ahmet Yılmaz','note' => 'Onaylandı. Fotoğraflarla teyit edildi.'],
                    ['when' => '2025-09-26 16:45','who' => 'Proje Müdürü Zeynep Kaya','note' => 'Muhasebeye iletildi.'],
                ]
            ];
        }

        return view('cost.payments.show', compact('payment'));
    }

    public function approve(Request $request, $paymentId)
    {
        if (!Schema::hasTable('payments')) return redirect()->back()->with('error','Payments table missing');
        $p = \App\Models\Payment::findOrFail($paymentId);
        $p->status = 'Onaylandı';
        $p->approved = $p->requested;
        $p->save();
        return redirect()->route('cost.payments.index')->with('success','Hakediş onaylandı');
    }

    public function create(Request $request)
    {
        $projects = \App\Models\Project::all();
        return view('cost.payments.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'nullable|integer',
            'contractor' => 'required|string',
            'contract' => 'nullable|string',
            'date' => 'required|date',
            'requested' => 'required|numeric',
        ]);

        if (Schema::hasTable('payments')) {
            $payment = \App\Models\Payment::create(array_merge($data, ['no' => 'HK-' . rand(100,999) . '-' . rand(100,999), 'status' => 'Taslak']));
            return redirect()->route('cost.payments.index')->with('success','Hakediş oluşturuldu');
        }

        // For demo (no table) create an object and redirect to index
        return redirect()->route('cost.payments.index')->with('success','(Demo) Hakediş oluşturuldu');
    }

    public function uploadEvidence(Request $request, $itemId)
    {
        if (!Schema::hasTable('payment_items')) return response()->json(['error'=>'No item model'],400);
        $item = \App\Models\PaymentItem::findOrFail($itemId);
        $file = $request->file('file');
        if (!$file) return response()->json(['error'=>'No file'],400);
        $path = $file->store('payment_evidences','public');
        $ev = \App\Models\PaymentEvidence::create(['payment_item_id'=>$item->id,'filename'=>$file->getClientOriginalName(),'mime'=>$file->getClientMimeType(),'path'=>$path,'uploaded_by'=>auth()->id()]);
        return response()->json(['ok'=>true,'id'=>$ev->id,'path'=>\Storage::url($path)]);
    }

    public function exportCsv(Request $request)
    {
        // Simple CSV export of payments list with optional KDV columns
        $rows = [];
        $kdvPercent = \App\Services\FinanceService::resolveKdvPercent($request->get('kdv', null));

        if (Schema::hasTable('payments')) {
            $payments = \App\Models\Payment::all();
            foreach ($payments as $p) {
                $kdvAmount = \App\Services\FinanceService::kdvAmount((float)$p->requested, $kdvPercent);
                $rows[] = [$p->no,$p->contractor,$p->contract,$p->date,$p->requested,$kdvPercent,$kdvAmount,$p->approved,$p->status];
            }
        } else {
            $kdvAmount1 = \App\Services\FinanceService::kdvAmount(450000, $kdvPercent);
            $kdvAmount2 = \App\Services\FinanceService::kdvAmount(2500000, $kdvPercent);
            $rows[] = ['HK-021-003','A İnşaat','Kaba İnşaat Sözleşmesi','2025-09-25',450000,$kdvPercent,$kdvAmount1,'','Saha Onayında'];
            $rows[] = ['HK-M-001','Proje Sahibi','Anahtar Teslim Sözleşme','2025-09-24',2500000,$kdvPercent,$kdvAmount2,2450000,'Onaylandı'];
        }

        $filename = 'payments_export_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No','Taşeron','Sözleşme','Tarih','Talep','KDV %','KDV Tutarı','Onaylanan','Durum']);
            foreach ($rows as $r) {
                fputcsv($handle, $r);
            }
            fclose($handle);
        }, $filename, $headers);
    }
}

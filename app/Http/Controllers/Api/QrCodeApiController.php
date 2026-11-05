<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\QrCodeService;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class QrCodeApiController extends Controller
{
    protected $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * QR kod taramasını işle
     */
    public function scan($token)
    {
        try {
            $qrCode = $this->qrCodeService->recordQrScan($token);
            
            return response()->json([
                'success' => true,
                'message' => 'QR kod başarıyla tarandı',
                'data' => [
                    'unit_id' => $qrCode->unit_id,
                    'project_id' => $qrCode->project_id,
                    'qr_label' => $qrCode->qr_label,
                    'qr_token' => $token,
                    'scans_count' => $qrCode->scans_count,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz QR kod',
            ], 404);
        }
    }

    /**
     * QR kodundan hızlı talep oluştur
     */
    public function createRequestFromQr(Request $request, $token)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'customer_id' => 'nullable|exists:customers,id',
        ]);

        try {
            $serviceRequest = $this->qrCodeService->createServiceRequestFromQr(
                $token,
                $request->subject,
                $request->description,
                $request->customer_id,
                null // Photo path varsa daha sonra ekle
            );

            return response()->json([
                'success' => true,
                'message' => 'Destek talebi başarıyla oluşturuldu',
                'data' => [
                    'id' => $serviceRequest->id,
                    'subject' => $serviceRequest->subject,
                    'status' => $serviceRequest->status,
                    'unit_id' => $serviceRequest->unit_id,
                    'sla_target' => $serviceRequest->sla_target,
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Talep oluşturulamadı: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * QR kod istatistiklerini al
     */
    public function getQrStats($token)
    {
        try {
            $stats = $this->qrCodeService->getQrCodeStats($token);
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'QR kod bulunamadı',
            ], 404);
        }
    }
}

<?php

namespace App\Services;

use App\Models\UnitQrCode;
use App\Models\ServiceRequest;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QrCodeService
{
    /**
     * Bir daire için QR kod oluştur ve depo et
     */
    public function generateQrCodeForUnit($unitId, $projectId, $label = null)
    {
        // Benzersiz token oluştur
        $token = Str::random(32);

        // QR kod URL'si
        $qrUrl = url("/api/qr/scan/{$token}");

        // QR kod oluştur
        $qrCode = QrCode::format('png')
            ->size(300)
            ->margin(2)
            ->generate($qrUrl);

        // Dosya adı ve yolunu oluştur
        $fileName = "qr-codes/unit-{$unitId}-{$token}.png";
        Storage::put("public/{$fileName}", $qrCode);

        // Veritabanına kaydet
        $qrCodeRecord = UnitQrCode::create([
            'unit_id' => $unitId,
            'project_id' => $projectId,
            'qr_token' => $token,
            'qr_code_path' => $fileName,
            'qr_label' => $label ?? "Daire {$unitId}",
        ]);

        return $qrCodeRecord;
    }

    /**
     * Token'dan QR kod kaydını al
     */
    public function getQrCodeByToken($token)
    {
        return UnitQrCode::where('qr_token', $token)
            ->where('is_active', true)
            ->firstOrFail();
    }

    /**
     * QR kod taramasını kaydet
     */
    public function recordQrScan($token)
    {
        $qrCode = $this->getQrCodeByToken($token);
        $qrCode->recordScan();
        return $qrCode;
    }

    /**
     * QR kodundan hızlı talep oluştur
     */
    public function createServiceRequestFromQr($token, $subject, $description, $customerId = null, $photoPath = null)
    {
        $qrCode = $this->recordQrScan($token);

        // SLA konfigürasyonunu al
        $slaConfig = \App\Models\SlaConfig::where('project_id', $qrCode->project_id)
            ->where('is_active', true)
            ->first();

        $slaTarget = $slaConfig?->resolution_time ?? (24 * 3600); // Varsayılan 24 saat

        // Talep oluştur
        $serviceRequest = ServiceRequest::create([
            'subject' => $subject,
            'description' => $description,
            'location' => "Daire {$qrCode->unit_id}",
            'unit_id' => $qrCode->unit_id,
            'project_id' => $qrCode->project_id,
            'customer_id' => $customerId,
            'photo_path' => $photoPath,
            'qr_token' => $token,
            'status' => 'pending',
            'sla_target' => $slaTarget,
            'communication_log' => [
                [
                    'timestamp' => now()->toIso8601String(),
                    'user' => 'system',
                    'message' => 'QR kod üzerinden talep oluşturuldu',
                ]
            ],
        ]);

        // İletişim kaydını ekle
        $serviceRequest->addCommunicationLog('customer', $description);

        return $serviceRequest;
    }

    /**
     * Bir proje için tüm QR kodlarını oluştur
     */
    public function generateQrCodesForProject($projectId)
    {
        $project = \App\Models\Project::find($projectId);
        if (!$project) {
            throw new \Exception("Proje bulunamadı: {$projectId}");
        }

        // Projeye ait tüm daireleri al
        $units = \App\Models\Villa::where('project_id', $projectId)->get();

        $generatedCodes = [];
        foreach ($units as $unit) {
            // Zaten QR kodu varsa, oluşturma
            $existing = UnitQrCode::where('unit_id', $unit->id)
                ->where('project_id', $projectId)
                ->first();

            if (!$existing) {
                $unitName = $unit->name ?? ('Daire ' . $unit->id);
                $qrCode = $this->generateQrCodeForUnit(
                    $unit->id,
                    $projectId,
                    $unitName
                );
                $generatedCodes[] = $qrCode;
            }
        }

        return $generatedCodes;
    }

    /**
     * QR kod görüntüsü URL'sini al
     */
    public function getQrCodeUrl($token)
    {
        $qrCode = $this->getQrCodeByToken($token);
        return Storage::url($qrCode->qr_code_path);
    }

    /**
     * QR kodunu devre dışı bırak
     */
    public function disableQrCode($token)
    {
        return UnitQrCode::where('qr_token', $token)
            ->update(['is_active' => false]);
    }

    /**
     * QR kodunu etkinleştir
     */
    public function enableQrCode($token)
    {
        return UnitQrCode::where('qr_token', $token)
            ->update(['is_active' => true]);
    }

    /**
     * Bir daire için QR kod istatistiklerini al
     */
    public function getQrCodeStats($token)
    {
        $qrCode = $this->getQrCodeByToken($token);
        $requestsCreatedViaQr = ServiceRequest::where('qr_token', $token)->count();

        return [
            'qr_token' => $token,
            'unit_id' => $qrCode->unit_id,
            'project_id' => $qrCode->project_id,
            'scans_count' => $qrCode->scans_count,
            'requests_created' => $requestsCreatedViaQr,
            'last_scanned_at' => $qrCode->last_scanned_at,
            'is_active' => $qrCode->is_active,
            'qr_label' => $qrCode->qr_label,
        ];
    }
}

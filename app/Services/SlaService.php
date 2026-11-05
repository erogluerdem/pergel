<?php

namespace App\Services;

use App\Models\ServiceRequest;
use App\Models\SlaAlert;
use App\Models\SlaConfig;

class SlaService
{
    /**
     * Bir talep için SLA kontrol et ve uyarıları tetikle
     */
    public function checkAndAlertSLA(ServiceRequest $serviceRequest)
    {
        if ($serviceRequest->status === 'completed') {
            return;
        }

        if (!$serviceRequest->sla_target) {
            return;
        }

        $elapsedTime = now()->diffInSeconds($serviceRequest->created_at);
        $slaTarget = $serviceRequest->sla_target;

        // %75 geçtiyse uyarı
        if ($elapsedTime > ($slaTarget * 0.75) && $elapsedTime < $slaTarget) {
            $this->createAlertIfNotExists($serviceRequest, 'warning');
        }

        // %100 geçtiyse kritik
        if ($elapsedTime >= $slaTarget) {
            $this->createAlertIfNotExists($serviceRequest, 'critical');
        }
    }

    /**
     * Uyarı oluştur (varsa oluşturma)
     */
    private function createAlertIfNotExists(ServiceRequest $serviceRequest, $alertType)
    {
        $existingAlert = SlaAlert::where('service_request_id', $serviceRequest->id)
            ->where('alert_type', $alertType)
            ->where('acknowledged_at', null)
            ->first();

        if (!$existingAlert) {
            SlaAlert::create([
                'service_request_id' => $serviceRequest->id,
                'alert_type' => $alertType,
                'triggered_at' => now(),
            ]);
        }
    }

    /**
     * Bir proje için SLA tanımla
     */
    public function configureSLA($projectId, $issueCategory, $responseTime, $resolutionTime)
    {
        return SlaConfig::updateOrCreate(
            [
                'project_id' => $projectId,
                'issue_category' => $issueCategory,
            ],
            [
                'response_time' => $responseTime,
                'resolution_time' => $resolutionTime,
                'is_active' => true,
            ]
        );
    }

    /**
     * Bir proje için tüm SLA istatistikleri al
     */
    public function getProjectSlaStats($projectId)
    {
        $requests = ServiceRequest::where('project_id', $projectId)->get();

        $stats = [
            'total_requests' => $requests->count(),
            'completed_requests' => $requests->where('status', 'completed')->count(),
            'pending_requests' => $requests->where('status', 'pending')->count(),
            'in_progress_requests' => $requests->where('status', 'in_progress')->count(),
            'sla_compliant' => 0,
            'sla_violated' => 0,
            'average_resolution_time' => 0,
            'critical_alerts' => 0,
            'warning_alerts' => 0,
        ];

        foreach ($requests as $request) {
            if ($request->resolution_time) {
                if ($request->sla_compliance === 'compliant') {
                    $stats['sla_compliant']++;
                } else {
                    $stats['sla_violated']++;
                }
            }
        }

        $completedRequests = $requests->where('resolution_time', '!=', null);
        if ($completedRequests->count() > 0) {
            $stats['average_resolution_time'] = round(
                $completedRequests->sum('resolution_time') / $completedRequests->count(),
                0
            );
        }

        $stats['critical_alerts'] = SlaAlert::whereIn('service_request_id', $requests->pluck('id'))
            ->where('alert_type', 'critical')
            ->count();

        $stats['warning_alerts'] = SlaAlert::whereIn('service_request_id', $requests->pluck('id'))
            ->where('alert_type', 'warning')
            ->count();

        return $stats;
    }

    /**
     * SLA uyum yüzdesini hesapla
     */
    public function getSlaCompliancePercentage($projectId)
    {
        $stats = $this->getProjectSlaStats($projectId);
        $totalMeasurable = $stats['sla_compliant'] + $stats['sla_violated'];

        if ($totalMeasurable === 0) {
            return 0;
        }

        return round(($stats['sla_compliant'] / $totalMeasurable) * 100, 2);
    }

    /**
     * Soruna göre SLA hedefini al
     */
    public function getSLATargetByCategory($projectId, $category)
    {
        $config = SlaConfig::where('project_id', $projectId)
            ->where('issue_category', $category)
            ->where('is_active', true)
            ->first();

        return $config?->resolution_time ?? (24 * 3600); // Varsayılan 24 saat
    }

    /**
     * Tüm açık kritik uyarıları al
     */
    public function getCriticalAlerts($projectId)
    {
        return SlaAlert::whereHas('serviceRequest', function ($query) use ($projectId) {
            $query->where('project_id', $projectId)
                ->where('status', '!=', 'completed');
        })
        ->where('alert_type', 'critical')
        ->where('acknowledged_at', null)
        ->with('serviceRequest')
        ->orderBy('triggered_at', 'desc')
        ->get();
    }

    /**
     * Ortalama çözüm süresini hesapla (saat cinsinden)
     */
    public function getAverageResolutionTimeInHours($projectId)
    {
        $stats = $this->getProjectSlaStats($projectId);
        return round($stats['average_resolution_time'] / 3600, 2);
    }
}

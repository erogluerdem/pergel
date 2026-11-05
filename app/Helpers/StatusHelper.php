<?php

namespace App\Helpers;

/**
 * StatusHelper - Status and enum utilities
 */
class StatusHelper
{
    /**
     * Get all project statuses
     * 
     * @return array
     */
    public static function projectStatuses(): array
    {
        return [
            'planning' => 'Planlama',
            'bidding' => 'İhale',
            'contracted' => 'Sözleşmeli',
            'construction' => 'İnşaat',
            'completed' => 'Tamamlandı',
        ];
    }

    /**
     * Get project status label
     * 
     * @param string $status
     * @return string
     */
    public static function projectStatusLabel(string $status): string
    {
        return self::projectStatuses()[$status] ?? ucfirst($status);
    }

    /**
     * Get project status color
     * 
     * @param string $status
     * @return string Bootstrap color class
     */
    public static function projectStatusColor(string $status): string
    {
        return match($status) {
            'planning' => 'secondary',
            'bidding' => 'info',
            'contracted' => 'primary',
            'construction' => 'warning',
            'completed' => 'success',
            default => 'dark',
        };
    }

    /**
     * Get project types
     * 
     * @return array
     */
    public static function projectTypes(): array
    {
        return [
            'residential' => 'Konut',
            'commercial' => 'Ticari',
            'infrastructure' => 'Altyapı',
            'industrial' => 'Endüstriyel',
        ];
    }

    /**
     * Get project type label
     * 
     * @param string $type
     * @return string
     */
    public static function projectTypeLabel(string $type): string
    {
        return self::projectTypes()[$type] ?? ucfirst($type);
    }

    /**
     * Get project health statuses
     * 
     * @return array
     */
    public static function healthStatuses(): array
    {
        return [
            'healthy' => 'Sağlıklı',
            'risky' => 'Riskli',
            'critical' => 'Kritik',
        ];
    }

    /**
     * Get health status label
     * 
     * @param string $status
     * @return string
     */
    public static function healthStatusLabel(string $status): string
    {
        return self::healthStatuses()[$status] ?? ucfirst($status);
    }

    /**
     * Get health status color
     * 
     * @param string $status
     * @return string Bootstrap color class
     */
    public static function healthStatusColor(string $status): string
    {
        return match($status) {
            'healthy' => 'success',
            'risky' => 'warning',
            'critical' => 'danger',
            default => 'dark',
        };
    }

    /**
     * Get priority levels
     * 
     * @return array
     */
    public static function priorities(): array
    {
        return [
            'low' => 'Düşük',
            'medium' => 'Orta',
            'high' => 'Yüksek',
            'urgent' => 'Acil',
        ];
    }

    /**
     * Get priority label
     * 
     * @param string $priority
     * @return string
     */
    public static function priorityLabel(string $priority): string
    {
        return self::priorities()[$priority] ?? ucfirst($priority);
    }

    /**
     * Get priority color
     * 
     * @param string $priority
     * @return string Bootstrap color class
     */
    public static function priorityColor(string $priority): string
    {
        return match($priority) {
            'low' => 'info',
            'medium' => 'secondary',
            'high' => 'warning',
            'urgent' => 'danger',
            default => 'dark',
        };
    }

    /**
     * Get invoice statuses
     * 
     * @return array
     */
    public static function invoiceStatuses(): array
    {
        return [
            'draft' => 'Taslak',
            'sent' => 'Gönderildi',
            'viewed' => 'Görüntülendi',
            'partial' => 'Kısmen Ödendi',
            'paid' => 'Ödendi',
            'overdue' => 'Vadesi Geçti',
            'cancelled' => 'İptal Edildi',
        ];
    }

    /**
     * Get invoice status label
     * 
     * @param string $status
     * @return string
     */
    public static function invoiceStatusLabel(string $status): string
    {
        return self::invoiceStatuses()[$status] ?? ucfirst($status);
    }

    /**
     * Get payment statuses
     * 
     * @return array
     */
    public static function paymentStatuses(): array
    {
        return [
            'pending' => 'Bekleniyor',
            'processing' => 'İşleniyor',
            'completed' => 'Tamamlandı',
            'failed' => 'Başarısız',
            'cancelled' => 'İptal Edildi',
        ];
    }

    /**
     * Get task statuses
     * 
     * @return array
     */
    public static function taskStatuses(): array
    {
        return [
            'pending' => 'Beklemede',
            'active' => 'Aktif',
            'completed' => 'Tamamlandı',
            'cancelled' => 'İptal Edildi',
            'on_hold' => 'Bekletildi',
        ];
    }

    /**
     * Get boolean label
     * 
     * @param bool $value
     * @return string
     */
    public static function booleanLabel(bool $value): string
    {
        return $value ? 'Evet' : 'Hayır';
    }

    /**
     * Get badge class for status
     * 
     * @param string $status
     * @return string Bootstrap badge class
     */
    public static function statusBadgeClass(string $status): string
    {
        // Try project status first
        $color = self::projectStatusColor($status);
        if ($color !== 'dark') {
            return "bg-{$color}";
        }

        // Try health status
        $color = self::healthStatusColor($status);
        if ($color !== 'dark') {
            return "bg-{$color}";
        }

        // Try priority
        $color = self::priorityColor($status);
        return "bg-{$color}";
    }
}

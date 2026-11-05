<?php

use App\Helpers\FormatHelper;
use App\Helpers\DateHelper;
use App\Helpers\StatusHelper;

// ============================================
// FORMAT HELPERS
// ============================================

if (! function_exists('format_budget_short')) {
    /**
     * Format budget to short string like 1.2M, 350k or 1.2B
     * 
     * @param float|int|null $number
     * @return string
     * @deprecated Use FormatHelper::budgetShort() instead
     */
    function format_budget_short($number)
    {
        return FormatHelper::budgetShort($number);
    }
}

if (! function_exists('format_currency')) {
    /**
     * Format currency with symbol and decimals
     * 
     * @param float|int|null $amount
     * @param string $currency
     * @param int $decimals
     * @return string
     */
    function format_currency($amount, string $currency = 'TRY', int $decimals = 2): string
    {
        return FormatHelper::currency($amount, $currency, $decimals);
    }
}

if (! function_exists('format_percentage')) {
    /**
     * Format percentage value
     * 
     * @param float|int|null $value
     * @param int $decimals
     * @param bool $includeSymbol
     * @return string
     */
    function format_percentage($value, int $decimals = 2, bool $includeSymbol = true): string
    {
        return FormatHelper::percentage($value, $decimals, $includeSymbol);
    }
}

if (! function_exists('format_file_size')) {
    /**
     * Format file size from bytes
     * 
     * @param int $bytes
     * @param int $decimals
     * @return string
     */
    function format_file_size(int $bytes, int $decimals = 2): string
    {
        return FormatHelper::fileSize($bytes, $decimals);
    }
}

if (! function_exists('format_phone')) {
    /**
     * Format phone number (Turkish format)
     * 
     * @param string $phone
     * @return string
     */
    function format_phone(string $phone): string
    {
        return FormatHelper::phoneNumber($phone);
    }
}

if (! function_exists('format_iban')) {
    /**
     * Format IBAN for display
     * 
     * @param string $iban
     * @return string
     */
    function format_iban(string $iban): string
    {
        return FormatHelper::iban($iban);
    }
}

// ============================================
// DATE HELPERS
// ============================================

if (! function_exists('date_diff_human')) {
    /**
     * Get human-readable time difference
     * 
     * @param mixed $date
     * @param mixed|null $compareDate
     * @param bool $absolute
     * @return string
     */
    function date_diff_human($date, $compareDate = null, bool $absolute = false): string
    {
        return DateHelper::diffForHumans($date, $compareDate, $absolute);
    }
}

if (! function_exists('date_format_tr')) {
    /**
     * Format date in Turkish
     * 
     * @param mixed $date
     * @param string $format
     * @return string
     */
    function date_format_tr($date = null, string $format = 'd M Y'): string
    {
        return DateHelper::formatTr($date, $format);
    }
}

if (! function_exists('days_between')) {
    /**
     * Get days difference between two dates
     * 
     * @param mixed $startDate
     * @param mixed $endDate
     * @return int
     */
    function days_between($startDate, $endDate): int
    {
        return DateHelper::daysBetween($startDate, $endDate);
    }
}

// ============================================
// STATUS HELPERS
// ============================================

if (! function_exists('get_project_statuses')) {
    /**
     * Get all project statuses
     * 
     * @return array
     */
    function get_project_statuses(): array
    {
        return StatusHelper::projectStatuses();
    }
}

if (! function_exists('get_project_status_label')) {
    /**
     * Get project status label
     * 
     * @param string $status
     * @return string
     */
    function get_project_status_label(string $status): string
    {
        return StatusHelper::projectStatusLabel($status);
    }
}

if (! function_exists('get_priority_label')) {
    /**
     * Get priority label
     * 
     * @param string $priority
     * @return string
     */
    function get_priority_label(string $priority): string
    {
        return StatusHelper::priorityLabel($priority);
    }
}

if (! function_exists('get_priority_color')) {
    /**
     * Get priority color class
     * 
     * @param string $priority
     * @return string
     */
    function get_priority_color(string $priority): string
    {
        return StatusHelper::priorityColor($priority);
    }
}

// ============================================
// PERGEL LICENSE
// ============================================

if (! function_exists('pergel_has_module')) {
    function pergel_has_module(string $module): bool
    {
        try {
            return app(\App\Services\PergelLicenseService::class)->hasModule($module);
        } catch (\Throwable $e) {
            return true;
        }
    }
}

if (! function_exists('pergel_is_pro')) {
    function pergel_is_pro(): bool
    {
        try {
            return app(\App\Services\PergelLicenseService::class)->isPro();
        } catch (\Throwable $e) {
            return false;
        }
    }
}

// ============================================
// PERGEL QUOTAS & LIMITS
// ============================================

if (! function_exists('pergel_can_create_project')) {
    function pergel_can_create_project(): bool
    {
        try {
            return app(\App\Services\PergelQuotaService::class)->canCreateProject();
        } catch (\Throwable $e) {
            return true;
        }
    }
}

if (! function_exists('pergel_can_create_user')) {
    function pergel_can_create_user(): bool
    {
        try {
            return app(\App\Services\PergelQuotaService::class)->canCreateUser();
        } catch (\Throwable $e) {
            return true;
        }
    }
}

if (! function_exists('pergel_can_create_asset')) {
    function pergel_can_create_asset(): bool
    {
        try {
            return app(\App\Services\PergelQuotaService::class)->canCreateAsset();
        } catch (\Throwable $e) {
            return true;
        }
    }
}

if (! function_exists('pergel_can_export')) {
    function pergel_can_export(): bool
    {
        try {
            return app(\App\Services\PergelQuotaService::class)->canExport();
        } catch (\Throwable $e) {
            return true;
        }
    }
}

if (! function_exists('pergel_quota_summary')) {
    function pergel_quota_summary(): array
    {
        try {
            return app(\App\Services\PergelQuotaService::class)->getUsageSummary();
        } catch (\Throwable $e) {
            return [];
        }
    }
}

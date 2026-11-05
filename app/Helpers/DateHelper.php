<?php

namespace App\Helpers;

use Carbon\Carbon;

/**
 * DateHelper - Common date/time utilities
 */
class DateHelper
{
    /**
     * Get human-readable time difference
     * 
     * @param Carbon|string $date
     * @param Carbon|string|null $compareDate
     * @param bool $absolute If true, returns absolute difference
     * @return string
     */
    public static function diffForHumans($date, $compareDate = null, bool $absolute = false): string
    {
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        $compareDate = $compareDate ? 
            (is_string($compareDate) ? Carbon::parse($compareDate) : $compareDate) : 
            now();

        return $date->diffForHumans($compareDate, $absolute);
    }

    /**
     * Format date in Turkish
     * 
     * @param Carbon|string|null $date
     * @param string $format
     * @return string
     */
    public static function formatTr($date = null, string $format = 'd M Y'): string
    {
        if (!$date) {
            return '-';
        }

        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        $months = [
            'Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran',
            'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'
        ];

        $days = [
            'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi', 'Pazar'
        ];

        $output = $format;

        // Month names
        $output = str_replace('M', $months[$date->month - 1], $output);
        $output = str_replace('F', $months[$date->month - 1], $output);

        // Day names
        $output = str_replace('l', $days[$date->dayOfWeek], $output);

        // Standard PHP date format
        $output = $date->format($output);

        return $output;
    }

    /**
     * Check if date is today
     * 
     * @param Carbon|string $date
     * @return bool
     */
    public static function isToday($date): bool
    {
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        return $date->isToday();
    }

    /**
     * Check if date is tomorrow
     * 
     * @param Carbon|string $date
     * @return bool
     */
    public static function isTomorrow($date): bool
    {
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        return $date->isTomorrow();
    }

    /**
     * Check if date is yesterday
     * 
     * @param Carbon|string $date
     * @return bool
     */
    public static function isYesterday($date): bool
    {
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        return $date->isYesterday();
    }

    /**
     * Check if date is in the past
     * 
     * @param Carbon|string $date
     * @return bool
     */
    public static function isPast($date): bool
    {
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        return $date->isPast();
    }

    /**
     * Check if date is in the future
     * 
     * @param Carbon|string $date
     * @return bool
     */
    public static function isFuture($date): bool
    {
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        return $date->isFuture();
    }

    /**
     * Get days difference between two dates
     * 
     * @param Carbon|string $startDate
     * @param Carbon|string $endDate
     * @return int
     */
    public static function daysBetween($startDate, $endDate): int
    {
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }

        return $startDate->diffInDays($endDate);
    }

    /**
     * Get business days between two dates (excluding weekends)
     * 
     * @param Carbon|string $startDate
     * @param Carbon|string $endDate
     * @return int
     */
    public static function businessDaysBetween($startDate, $endDate): int
    {
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }

        $days = 0;
        $current = $startDate->copy();

        while ($current < $endDate) {
            if ($current->isWeekday()) {
                $days++;
            }
            $current->addDay();
        }

        return $days;
    }

    /**
     * Get the quarter of a date
     * 
     * @param Carbon|string|null $date
     * @return int (1-4)
     */
    public static function quarter($date = null): int
    {
        if (!$date) {
            $date = now();
        } elseif (is_string($date)) {
            $date = Carbon::parse($date);
        }

        return (int)ceil($date->month / 3);
    }

    /**
     * Get start date of current month
     * 
     * @return Carbon
     */
    public static function monthStart(): Carbon
    {
        return now()->startOfMonth();
    }

    /**
     * Get end date of current month
     * 
     * @return Carbon
     */
    public static function monthEnd(): Carbon
    {
        return now()->endOfMonth();
    }

    /**
     * Get start date of current year
     * 
     * @return Carbon
     */
    public static function yearStart(): Carbon
    {
        return now()->startOfYear();
    }

    /**
     * Get end date of current year
     * 
     * @return Carbon
     */
    public static function yearEnd(): Carbon
    {
        return now()->endOfYear();
    }

    /**
     * Format time duration in human-readable format
     * 
     * @param int $hours
     * @param int $minutes
     * @param int $seconds
     * @return string
     */
    public static function formatDuration(int $hours = 0, int $minutes = 0, int $seconds = 0): string
    {
        $parts = [];

        if ($hours > 0) {
            $parts[] = "{$hours}s";
        }
        if ($minutes > 0) {
            $parts[] = "{$minutes}d";
        }
        if ($seconds > 0) {
            $parts[] = "{$seconds}sn";
        }

        return implode(' ', $parts) ?: '0sn';
    }
}

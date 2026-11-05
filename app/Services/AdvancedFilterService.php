<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

class AdvancedFilterService
{
    /**
     * Apply advanced filters to a query
     */
    public static function applyFilters(Builder $query, array $filters): Builder
    {
        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('email', 'like', "%{$filters['search']}%")
                  ->orWhere('phone', 'like', "%{$filters['search']}%");
            });
        }

        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (isset($filters['status']) && !empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['sort_by']) && !empty($filters['sort_by'])) {
            $direction = $filters['sort_direction'] ?? 'asc';
            $query->orderBy($filters['sort_by'], $direction);
        }

        return $query;
    }

    /**
     * Apply payment filters
     */
    public static function applyPaymentFilters(Builder $query, array $filters): Builder
    {
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['min_amount'])) {
            $query->where('amount', '>=', $filters['min_amount']);
        }

        if (isset($filters['max_amount'])) {
            $query->where('amount', '<=', $filters['max_amount']);
        }

        if (isset($filters['customer_id'])) {
            $query->whereHas('invoice.customer', function($q) use ($filters) {
                $q->where('id', $filters['customer_id']);
            });
        }

        if (isset($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }

        if (isset($filters['overdue_only']) && $filters['overdue_only']) {
            $query->overdue();
        }

        if (isset($filters['upcoming_days']) && $filters['upcoming_days'] > 0) {
            $query->upcoming($filters['upcoming_days']);
        }

        return $query;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = [
        'asset_id',
        'customer_id',
        'opportunity_id',
        'option_date',
        'expiry_date',
        'duration_days', // Opsiyon süresi (gün)
        'notes',
        'status' // 'active', 'converted_to_project', 'expired', 'cancelled'
    ];

    protected $casts = [
        'option_date' => 'date',
        'expiry_date' => 'date'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class, 'opportunity_id');
    }

    // Opsiyon aktif mi
    public function isActive()
    {
        return $this->status === 'active' && $this->expiry_date >= now()->toDateString();
    }

    // Opsiyon süresi dolmuş mu
    public function isExpired()
    {
        return $this->expiry_date < now()->toDateString();
    }

    // Kalan gün sayısı
    public function getRemainingDaysAttribute()
    {
        if (!$this->isExpired()) {
            return now()->diffInDays($this->expiry_date);
        }
        return 0;
    }

    // Süresi dolan opsiyonları otomatik sil
    public static function expireOldOptions()
    {
        self::where('status', 'active')
            ->where('expiry_date', '<', now()->toDateString())
            ->update(['status' => 'expired']);
    }

    // Uyarı: Süresi 1 gün kalan opsiyonlar
    public function isAboutToExpire()
    {
        return $this->remaining_days === 1;
    }
}

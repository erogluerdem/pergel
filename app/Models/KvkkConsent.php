<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KvkkConsent extends Model
{
    protected $table = 'kvkk_consents';

    protected $fillable = [
        'customer_id',
        'phone',
        'email',
        'sms_code',
        'sms_verified_at',
        'email_verified_at',
        'consent_type', // 'sms', 'email', 'both'
        'consent_status', // 'pending', 'verified', 'rejected', 'expired'
        'consent_date',
        'expiry_date',
        'verification_attempts',
        'last_attempt_at',
        'notes'
    ];

    protected $casts = [
        'sms_verified_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'consent_date' => 'datetime',
        'expiry_date' => 'datetime',
        'last_attempt_at' => 'datetime'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // SMS kodu oluştur
    public static function generateSmsCode()
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    // Kodun geçerli olup olmadığını kontrol et
    public function isCodeExpired()
    {
        return $this->created_at->addMinutes(15) < now();
    }

    // Maksimum deneme sayısına ulaşıldı mı
    public function maxAttemptsReached()
    {
        return $this->verification_attempts >= 3;
    }

    // Onay durumu
    public function isVerified()
    {
        return $this->consent_status === 'verified';
    }

    public function isPending()
    {
        return $this->consent_status === 'pending';
    }

    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date < now();
    }
}

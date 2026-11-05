<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerMeeting extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'user_id',
        'subject',
        'notes',
        'meeting_date',
        'location',
        'outcome',
        'potential_sale_amount',
        'next_followup',
        'next_followup_date',
        'meeting_type',
    ];

    protected $casts = [
        'meeting_date' => 'datetime',
        'next_followup_date' => 'datetime',
        'potential_sale_amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Son görüşme olup olmadığını kontrol et
    public function isLatestMeeting()
    {
        return $this->id === $this->customer->meetings()->latest('meeting_date')->first()?->id;
    }

    // Görüşme sonuç etiketini al
    public function getOutcomeLabel(): string
    {
        $labels = [
            'interested' => 'İlgilendi ✓',
            'not_interested' => 'İlgilenmedi ✗',
            'pending' => 'Bekleme ⏳',
            'converted_to_sale' => 'Satışa Dönüştü 💰',
        ];
        return $labels[$this->outcome] ?? $this->outcome ?? 'Belirtilmedi';
    }

    // Görüşme türü etiketini al
    public function getMeetingTypeLabel(): string
    {
        $labels = [
            'call' => 'Telefon',
            'visit' => 'Ziyaret',
            'email' => 'E-Posta',
            'online' => 'Online',
        ];
        return $labels[$this->meeting_type] ?? $this->meeting_type ?? 'Görüşme';
    }

    // Sonraki takip yapılması gerekiyor mu
    public function needsFollowup(): bool
    {
        return $this->next_followup_date && $this->next_followup_date <= now();
    }
}

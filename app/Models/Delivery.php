<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'delivery_date',
        'invoice_number',
        'delivered_quantity',
        'unit',
        'received_by',
        'notes',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'delivered_quantity' => 'decimal:2',
    ];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}

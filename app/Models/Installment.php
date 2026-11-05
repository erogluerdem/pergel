<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Installment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'payment_plan_id', 'invoice_id', 'installment_number', 'amount',
        'due_date', 'paid_date', 'paid_amount', 'status', 'notes',
        'penalty_amount', 'payment_method', 'transaction_reference', 'received_by'
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_date' => 'date',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'penalty_amount' => 'decimal:2',
    ];

    public function paymentPlan(): BelongsTo
    {
        return $this->belongsTo(PaymentPlan::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    // Check if installment is overdue
    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'pending' && $this->due_date < today();
    }

    // Get remaining amount
    public function getRemainingAmountAttribute(): float
    {
        return (float)($this->amount - $this->paid_amount);
    }

    // Calculate total with penalty
    public function getTotalWithPenaltyAttribute(): float
    {
        return (float)($this->amount + $this->penalty_amount);
    }

    // Mark as paid
    public function markAsPaid($paidAmount, $paidDate = null, $method = null, $reference = null, $receivedBy = null)
    {
        $this->paid_date = $paidDate ?? now()->toDateString();
        $this->paid_amount = $paidAmount;
        $this->payment_method = $method;
        $this->transaction_reference = $reference;
        $this->received_by = $receivedBy;
        $this->status = ($paidAmount >= $this->amount) ? 'paid' : 'partial';
        $this->save();

        // Update related payment plan status
        $this->updatePaymentPlanStatus();

        return $this;
    }

    // Update payment plan status based on installments
    public function updatePaymentPlanStatus()
    {
        $plan = $this->paymentPlan;
        $total = $plan->installments()->sum('amount');
        $paid = $plan->installments()->sum('paid_amount');

        if ($paid >= $total) {
            $plan->update(['status' => 'completed']);
        } elseif ($paid > 0) {
            $plan->update(['status' => 'in_progress']);
        }
    }

    // Scope for overdue installments
    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
            ->where('due_date', '<', today());
    }

    // Scope by status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope for upcoming installments
    public function scopeUpcoming($query, $days = 7)
    {
        return $query->where('status', 'pending')
            ->whereDate('due_date', '>=', today())
            ->whereDate('due_date', '<=', today()->addDays($days));
    }
}

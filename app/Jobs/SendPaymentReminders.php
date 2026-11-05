<?php

namespace App\Jobs;

use App\Models\Installment;
use App\Models\PaymentReminder;
use App\Notifications\PaymentReminderNotification;
use App\Notifications\PaymentReminderSMS;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPaymentReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        // Taksitleri kontrol et: 3 gün sonra ödeme yapılacak olanlar
        $daysAhead = config('payment.reminder_days', 3);

        $upcomingInstallments = Installment::where('status', 'pending')
            ->whereDate('due_date', '=', now()->addDays($daysAhead)->toDateString())
            ->with(['invoice.customer', 'paymentPlan'])
            ->get();

        foreach ($upcomingInstallments as $installment) {
            $customer = $installment->invoice->customer;

            // Email gönder
            if ($customer->email) {
                $reminder = PaymentReminder::firstOrCreate([
                    'installment_id' => $installment->id,
                    'user_id' => $customer->id,
                    'type' => 'email',
                    'days_before' => $daysAhead,
                ]);

                if (!$reminder->sent) {
                    $customer->notify(new PaymentReminderNotification($installment, $daysAhead));
                    $reminder->markAsSent('Email sent successfully');
                }
            }

            // SMS gönder (Müşteri tercihine bağlı)
            if ($customer->phone && $this->shouldSendSMS($customer)) {
                $smsReminder = PaymentReminder::firstOrCreate([
                    'installment_id' => $installment->id,
                    'user_id' => $customer->id,
                    'type' => 'sms',
                    'days_before' => $daysAhead,
                ]);

                if (!$smsReminder->sent) {
                    try {
                        $customer->notify(new PaymentReminderSMS($installment));
                        $smsReminder->markAsSent('SMS sent successfully');
                    } catch (\Exception $e) {
                        $smsReminder->markAsSent('SMS failed: ' . $e->getMessage());
                    }
                }
            }
        }

        // Gecikmiş taksitleri işle (alert)
        $overdueInstallments = Installment::overdue()
            ->with('invoice.customer')
            ->get();

        foreach ($overdueInstallments as $installment) {
            // Log'la ve alert oluştur
            \Log::warning('Overdue installment', [
                'installment_id' => $installment->id,
                'amount' => $installment->amount,
                'customer' => $installment->invoice->customer->name,
            ]);
        }
    }

    private function shouldSendSMS($customer): bool
    {
        return $customer->notifications_enabled ?? true;
    }
}

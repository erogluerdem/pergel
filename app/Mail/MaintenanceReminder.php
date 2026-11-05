<?php

namespace App\Mail;

use App\Models\Asset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MaintenanceReminder extends Mailable
{
    use Queueable, SerializesModels;

    public Asset $asset;
    public $daysUntilMaintenance;

    /**
     * Create a new message instance.
     */
    public function __construct(Asset $asset, $daysUntilMaintenance)
    {
        $this->asset = $asset;
        $this->daysUntilMaintenance = $daysUntilMaintenance;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bakım Hatırlatması: ' . $this->asset->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.maintenance-reminder',
            with: [
                'asset' => $this->asset,
                'daysUntilMaintenance' => $this->daysUntilMaintenance,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

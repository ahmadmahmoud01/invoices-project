<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class AddInvoiceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $invoice;

    /**
     * Create a new notification instance.
     */
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = route('invoices.show', $this->invoice->id);

        return (new MailMessage)
            ->subject('New Invoice Added')
            ->line('A new invoice has been added.')
            ->action('View Invoice', $url)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the database representation of the notification.
     */
    // public function toDatabase(object $notifiable): array
    // {
    //     return [
    //         'invoice_id' => $this->invoice->id,
    //         'user' => Auth::user()->name,
    //         'message' => 'A new invoice has been added by ' . Auth::user()->name,
    //     ];
    // }

    /**
     * Get the array representation of the notification for other channels.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'invoice_id' => $this->invoice->id,
            'user' => Auth::user()->name,
            'message' => 'تم إضافة فاتورة جديدة بواسطة : ' . Auth::user()->name,
        ];
    }
}

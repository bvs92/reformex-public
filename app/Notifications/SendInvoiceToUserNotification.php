<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class SendInvoiceToUserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $user, $invoice;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $invoice)
    {
        $this->user = $user;
        $this->invoice = $invoice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $name = $this->user->last_name . ' ' . $this->user->first_name;
        // $invoice = storage_path('app/public') . '/invoices' . '/' . $this->invoice->name;
        $invoice = Storage::disk('do_spaces')->url('uploads/invoices/' . $this->invoice->name);
        // $mime_type = mime_content_type($invoice);
        return (new MailMessage)
            ->subject('Factură fiscală disponibilă')
            ->greeting('Salutare, ' . $name . '!')
            ->line('Am generat factura fiscală pentru plata cu numărul ' . $this->invoice->payment->uuid)
            ->line('Descarcă factura mai jos. Factura este disponibilă și în contul tău la secțiunea cu plăți.')
            ->attach($invoice, [
                'as' => 'factura-fiscala-plata-' . $this->invoice->payment->uuid . '.' . $this->invoice->extension,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

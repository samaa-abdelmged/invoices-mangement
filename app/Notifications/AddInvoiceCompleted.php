<?php

namespace App\Notifications;

use App\Models\invoices;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class AddInvoiceCompleted extends Notification
{
    use Queueable;
    private $invoices;

    /**
     * Create a new notification instance.
     */
    public function __construct(invoices $invoices)
    {
        $this->invoices = $invoices;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'id' => $this->invoices->id,
            'title' => 'تم اضافة فاتورة جديد بواسطة :',
            'user' => Auth::user()->name,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */

}
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendMail extends Notification
{
    use Queueable;
    private $invoice_id;
    private $user_name;

    /**
     * Create a new notification instance.
     */
    public function __construct($invoice_id, $user_name)
    {
        $this->invoice_id = $invoice_id;
        $this->user_name = $user_name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = "http://127.0.0.1:8000/GetInvoicesDetails" . $this->invoice_id;
        return (new MailMessage)
            ->subject("new invoice")
            ->line($this->user_name)
            ->line('you added a new invoice you can see it here click on this button')
            ->action('View Invoice', $url)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

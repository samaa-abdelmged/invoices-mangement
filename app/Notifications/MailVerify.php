<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MailVerify extends Notification
{
    use Queueable;
    private $user_name;
    private $code;
    private $expire_at;

    /**
     * Create a new notification instance.
     */
    public function __construct($user_name, $code, $expire_at)
    {
        $this->user_name = $user_name;
        $this->code = $code;
        $this->expire_at = $expire_at;

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
        return (new MailMessage)
            ->subject("كود التحقق")
            ->line($this->user_name)
            ->line('كود التحقق الخاص بك هو ')
            ->line($this->code)
            ->line('.قم بنسخ هذا الكود و الصاقة في خانة كود التحقق قبل انتهاء مدة التفعيل')
            ->line('ستنتهي مدة تفعيل هذا الكود في ')
            ->line($this->expire_at)
            ->line('لا تعطي هذا الكود لأي شخص!')
            ->line('شكرا لأستخدامك موقعنا');
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
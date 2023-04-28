<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($url)
    {
        $this->url = $url;
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
        return (new MailMessage)
            ->subject("Reset Password")
            ->greeting("Halo,")
            ->line("Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.")
            ->line("Silakan klik tombol di bawah ini untuk mereset password Anda")
            ->action('Reset Password', $this->url)
            ->line("Perlu diperhatikan bahwa link reset password anda hanya berlaku selama 60 menit. Jika Anda tidak meminta reset password, silahkan abaikan email ini.")
            ->salutation("\n\n\nTerimakasih.");
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [];
    }
}

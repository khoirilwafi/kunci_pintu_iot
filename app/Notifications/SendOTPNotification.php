<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendOTPNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $otp_code;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($otp_code)
    {
        $this->otp_code = $otp_code;
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
            ->subject("Verifikasi OTP")
            ->greeting("Halo,")
            ->line("Anda menerima email ini karena anda belum melakukan verifikasi akun, atau mungkin anda baru saja login pada perangkat baru.")
            ->line("Silakan masukkan kode dibawah ini untuk memverifikasi akun anda.")
            ->line(new HtmlString('<h1 style="text-align:center; margin-top:30px; margin-bottom:30px">' . $this->otp_code . '</h1>'))
            ->line("Perlu diperhatikan bahwa kode tersebut hanya berlaku selama 5 menit. Untuk keamanan anda, jangan berikan kode tersebut kepada pihak lain.")
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

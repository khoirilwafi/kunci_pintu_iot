<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewUserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($password)
    {
        $this->password = $password;
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
            ->subject("Smart Lock Password")
            ->greeting("Halo,")
            ->line("Anda menerima email ini karena alamat email anda telah didaftarkan pada Sistem Penguncian Gedung Berbasis IoT")
            ->line("Silahkan login menggunakan password berikut.")
            ->line(new HtmlString('<h2 style="text-align:center; margin-top:30px; margin-bottom:30px">' . $this->password . '</h2>'))
            ->line("Jangan lupa untuk mengganti password ini setelah anda login.")
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
        return [
            //
        ];
    }
}

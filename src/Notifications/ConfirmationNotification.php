<?php

namespace AlfaDevTeam\AuthApi\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;

class ConfirmationNotification extends Notification
{
    use Queueable;

    protected $code;

    /**
     * Create a new notification instance.
     *
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if (optional($notifiable)->phone) {
            return ['nexmo'];
        }
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Confirmation code for your account')
            ->line($this->code);
    }

    /**
     * Get the Vonage / SMS representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\NexmoMessage
     */
    public function toNexmo($notifiable)
    {
        return (new NexmoMessage)
            ->content("Your confirmation code: $this->code");
    }
}

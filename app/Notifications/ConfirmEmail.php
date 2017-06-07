<?php

namespace App\Notifications;

use App\EmailConfirmation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ConfirmEmail extends Notification
{
    use Queueable;

    /**
     * @var EmailConfirmation
     */
    private $confirmation;

    /**
     * Create a new notification instance.
     *
     * @param EmailConfirmation $confirmation
     */
    public function __construct(EmailConfirmation $confirmation)
    {
        $this->confirmation = $confirmation;
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
        $url = url('api/users/email');
        $duration = config('auth.email_confirmations.expire');

        return (new MailMessage)
                    ->line("Submit **PATCH** request to the following url to confirm your email: `{$url}`")
                    ->line('The **JSON** payload must include the following fields:')
                    ->line("- id: `{$this->confirmation->id}`")
                    ->line("- email: `{$this->confirmation->email}`")
                    ->line("Do not forget to specify appropriate `Accept` and `Content-Type` headers.")
                    ->line("The above data will only be valid for **{$duration} minutes**.");
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

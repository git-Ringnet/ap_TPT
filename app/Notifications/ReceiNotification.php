<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReceiNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private $receiving;
    private $oldState;
    private $newState;

    public function __construct($receiving, $oldState = null, $newState = null)
    {
        $this->receiving = $receiving;
        $this->oldState = $oldState;
        $this->newState = $newState;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'receiving_id' => $this->receiving->id,
            'recei_code' => $this->receiving->form_code_receiving,
            'message' => "{$this->newState}.",
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
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

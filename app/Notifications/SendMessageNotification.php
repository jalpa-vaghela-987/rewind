<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $message;
    public $link;
    public $sender;

    /**
     * Create a new notification instance.
     *
     * @param $message
     */
    public function __construct($message,$link=null,$sender=null)
    {
        $this->message = $message;
        $this->link = $link;
        $this->sender = $sender;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
//        return ['database', 'mail'];
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->view(
            'mails.notifications.message',
            [
                'data' => [
                    'message'  => $this->message,
                    'link'  => $this->link,
                    'sender'   => $this->sender ? $this->sender->name : optional(auth()->user())->name,
                    'receiver' => $notifiable->name,
                ],
            ]
        );
    }


    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message'  => $this->message,
            'link'  => $this->link,
            'sender_id'  => $this->sender_id,
        ];
    }
}

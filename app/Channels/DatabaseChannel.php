<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;
use Illuminate\Support\Facades\Log;

class DatabaseChannel extends IlluminateDatabaseChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function send($notifiable, Notification $notification)
    {
//        dd($notification);
        return $notifiable->routeNotificationFor('database')->create([
            'type'      => get_class($notification),
            'sender_id' => $notification->sender ?  $notification->sender->id : auth()->id(),
            'notifiable_type' => get_class($notifiable),
            'data'      => $notification->message,
            'link'      => $notification->link,
            'read_at'   => null,
        ]);
    }
}

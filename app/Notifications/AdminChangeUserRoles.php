<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminChangeUserRoles extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
        $name = $this->user->last_name . " " . $this->user->first_name;
        $roles = $this->user->roles->pluck('name');
        $roles_list = '';

        foreach ($roles as $key => $value) {
            $roles_list .= ucfirst($value);
            if ($key !== count($roles) - 1) {
                $roles_list .= ', ';
            }
        }

        return (new MailMessage)
            ->subject('Roluri actualizate')
            ->greeting('Salutare, ' . $name)
            ->line('Ți-am actualizat rolurile în platformă.')
            ->line('De acum ai următoarele roluri: ' . $roles_list);

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

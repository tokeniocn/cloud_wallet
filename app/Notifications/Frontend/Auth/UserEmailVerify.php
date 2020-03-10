<?php

namespace App\Notifications\Frontend\Auth;

use App\Models\Auth\UserVerify;
use App\Messages\Frontend\UserVerifyEmailMessage;
use App\Notifications\Middleware\BeforeSend;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserEmailVerify extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var UserVerify
     */
    protected $userVerify;

    /**
     * UserEmailVerify constructor.
     *
     * @param string $email
     * @param string $token
     */
    public function __construct(UserVerify $userVerify)
    {
        $this->userVerify = $userVerify;
    }

    public function middleware()
    {
        return [
            BeforeSend::class
        ];
    }

    public function beforeSend($notifiable)
    {
        $notifiable->withNotificationEmail($this->userVerify->key);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return new UserVerifyEmailMessage($this->userVerify);
    }
}

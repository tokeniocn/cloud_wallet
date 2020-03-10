<?php

namespace App\Messages\Frontend;

use App\Models\Auth\UserVerify;
use Illuminate\Notifications\Messages\MailMessage;

class UserVerifyEmailMessage extends MailMessage
{
    /**
     * @var UserVerify
     */
    protected $userVerify;

    public function __construct(UserVerify $userVerify)
    {
        $this->userVerify = $userVerify;

        $this->init();
    }

    protected function init()
    {
        $this->subject('邮件确认')
             ->line('请点击下面按钮完成邮箱确认')
             ->action('验证邮箱', route('frontend.auth.email.verify', ['token' => $this->userVerify->token]));
    }
}

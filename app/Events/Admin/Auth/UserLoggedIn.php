<?php

namespace App\Events\Admin\Auth;

use App\Models\Admin\AdminUser;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserLoggedIn.
 */
class UserLoggedIn
{
    use SerializesModels;

    /**
     * @var
     */
    public $user;

    /**
     * @param $user
     */
    public function __construct(AdminUser $user)
    {
        $this->user = $user;
    }
}

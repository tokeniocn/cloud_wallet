<?php

namespace App\Http\Controllers\Frontend\Api\Auth;

use App\Models\Auth\User;
use App\Http\Controllers\Controller;
use App\Events\Frontend\Auth\UserRegistered;
use App\Http\Requests\Frontend\Auth\RegisterRequest;

/**
 * Class RegisterController.
 */
class RegisterController extends Controller
{
    /**
     * @param RegisterRequest $request
     *
     * @throws \Throwable
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function register(RegisterRequest $request)
    {
        abort_unless(config('access.registration'), 404);

        /** @var User $user */
        $user = User::create($request->only('username', 'password'));

        $user->refresh();

        event(new UserRegistered($user));

        return $user;
    }
}

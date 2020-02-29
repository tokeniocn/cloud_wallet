<?php

namespace App\Http\Controllers\Admin\Auth\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\User\ManageUserRequest;
use App\Models\Auth\User;

/**
 * Class UserSessionController.
 */
class UserSessionController extends Controller
{
    /**
     * @param ManageUserRequest $request
     * @param User              $user
     *
     * @return mixed
     */
    public function clearSession(ManageUserRequest $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->withFlashDanger(__('exceptions.admin.access.users.cant_delete_own_session'));
        }

        $user->update(['to_be_logged_out' => true]);

        return redirect()->back()->withFlashSuccess(__('alerts.admin.users.session_cleared'));
    }
}

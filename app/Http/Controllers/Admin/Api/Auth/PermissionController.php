<?php

namespace App\Http\Controllers\Admin\Api\Auth;

use App\Events\Admin\Auth\Role\RoleDeleted;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\Permission\ManagePermissionRequest;
use App\Http\Requests\Admin\Auth\Role\StoreRoleRequest;
use App\Http\Requests\Admin\Auth\Role\UpdateRoleRequest;
use App\Models\Auth\Permission;
use App\Repositories\Admin\Auth\PermissionRepository;
use App\Repositories\Admin\Auth\RoleRepository;
use function GuzzleHttp\Promise\all;

/**
 * Class PermissionController.
 */
class PermissionController extends Controller
{


    /**
     * @var PermissionRepository
     */
    protected $permissionRepository;

    /**

     * @param PermissionRepository $permissionRepository
     */
    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * @param ManagePermissionRequest $request
     *
     * @return mixed
     */
    public function index(ManagePermissionRequest $request)
    {
        return $this->permissionRepository
            ->where('guard_name', $request->get('guard', 'admin'))
            ->orderBy('sort')
            ->get();
    }

    /**
     * @param ManageRoleRequest $request
     *
     * @return mixed
     */
    public function create(ManageRoleRequest $request)
    {
        return view('admin.auth.role.create')
            ->withPermissions($this->permissionRepository->get());
    }

    /**
     * @param  StoreRoleRequest  $request
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function store(StoreRoleRequest $request)
    {
        $this->roleRepository->create($request->only('name', 'associated-permissions', 'permissions', 'sort'));

        return redirect()->route('admin.auth.roles')->withFlashSuccess(__('alerts.admin.roles.created'));
    }

    /**
     * @param ManageRoleRequest $request
     * @param Role              $role
     *
     * @return mixed
     */
    public function edit(ManageRoleRequest $request, Role $role)
    {
        if ($role->isAdmin()) {
            return redirect()->route('admin.auth.roles')->withFlashDanger('You can not edit the administrator role.');
        }

        return view('admin.auth.role.edit')
            ->withRole($role)
            ->withRolePermissions($role->permissions->pluck('name')->all())
            ->withPermissions($this->permissionRepository->get());
    }

    /**
     * @param  UpdateRoleRequest  $request
     * @param  Role  $role
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $this->roleRepository->update($role, $request->only('name', 'permissions'));

        return redirect()->route('admin.auth.roles')->withFlashSuccess(__('alerts.admin.roles.updated'));
    }

    /**
     * @param ManageRoleRequest $request
     * @param Role              $role
     *
     * @throws \Exception
     * @return mixed
     */
    public function destroy(ManageRoleRequest $request, Role $role)
    {
        if ($role->isAdmin()) {
            return redirect()->route('admin.auth.roles')->withFlashDanger(__('exceptions.admin.access.roles.cant_delete_admin'));
        }

        $this->roleRepository->deleteById($role->id);

        event(new RoleDeleted($role));

        return redirect()->route('admin.auth.roles')->withFlashSuccess(__('alerts.admin.roles.deleted'));
    }
}

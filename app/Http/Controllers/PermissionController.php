<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kodeine\Acl\Models\Eloquent\Role;
use Kodeine\Acl\Models\Eloquent\Permission;
use App\Http\Resources\Permission\PermissionCollection;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Role::class);
    }

    /**
     * Список прав
     *
     * Get listing of the permissions
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $permissions = Permission::get();

        $headers = $permissions->where('inherit_id', null)->pluck('description');

        $permissions = $permissions->groupBy('inherit_id');

        return response()->json([
            'permissions' => new PermissionCollection($permissions),
            'headers' => $headers
        ], 200);
    }
}

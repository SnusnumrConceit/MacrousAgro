<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kodeine\Acl\Models\Eloquent\Role;
use App\Http\Requests\Role\RoleStoreRequest;
use App\Http\Requests\Role\RoleUpdateRequest;

use App\Http\Resources\Role\Role as RoleResource;
use App\Http\Resources\Role\RoleCollection;

class RoleController extends Controller
{
    public function __construct()
    {
        // TODO добавить политики
//        $this->authorizeResource(Role::class);
    }

    /**
     * Список ролей
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = Role::query();

        $roles->when($request->keyword, function ($q, $keyword) {
            return $q->whereName($keyword);
        });

        $roles = $roles->paginate(10);

        return response()->json([
            'roles' => new RoleCollection($roles)
        ], 200);
    }

    /**
     * Сохранение роли
     *
     * Store a newly created resource in storage.
     *
     * @param  RoleStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleStoreRequest $request)
    {
        Role::create($request->only(['name', 'description', 'slug']));

        return response()->json([
            'message' => __('roles.response.messages.created')
        ], 200);
    }

    /**
     * Информация о роли
     *
     * Display the specified resource.
     *
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Role $role)
    {
        return response()->json([
            'role' => new RoleResource($role->load('permissions'))
        ], 200);
    }

    /**
     * Обновление роли
     *
     * Update the specified resource in storage.
     *
     * @param RoleUpdateRequest $request
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(RoleUpdateRequest $request, Role $role)
    {
        $role->update($request->only(['name', 'description', 'slug']));

        $role->permissions()->sync(request('permissions'));

        return response()->json([
            'message' => __('roles.response.messages.updated')
        ], 200);
    }

    /**
     * Удаление роли
     *
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json([
            'message' => __('roles.response.messages.deleted')
        ], 200);
    }
}

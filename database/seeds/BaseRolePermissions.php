<?php

use Illuminate\Database\Seeder;
use \Kodeine\Acl\Models\Eloquent\Role;
use \Kodeine\Acl\Models\Eloquent\Permission;

class BaseRolePermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Permission::all();
        $permissionsAdmin = $permissions->whereNotNull('inherit_id')->pluck('id')->all();
        $permissionsManager = $permissions->whereNotNull('inherit_id')
            ->whereNotIn('inherit_id', [22, 26]) // идентификаторы Roles и Users
            ->pluck('id')
            ->all();

        $permissionsCustomer = $permissions->whereNotNull('inherit_id')
            ->whereIn('name', [
                'articles_view',
                'categories_view',
                'orders_create',
                'orders_view',
                'products_view',
                'photos_view',
                'videos_view'
            ])->pluck('id')
            ->all();

        $admin = Role::whereName('administrator')->first();
        $admin->permissions()->sync($permissionsAdmin);

        $manager = Role::whereName('manager')->first();
        $manager->permissions()->sync($permissionsManager);

        $customer = Role::whereName('customer')->first();
        $customer->permissions()->sync($permissionsCustomer);
    }
}

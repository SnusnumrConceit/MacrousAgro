<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Kodeine\Acl\Models\Eloquent\Role::updateOrCreate(['name' => 'administrator'], [
            'name'        => 'administrator',
            'description' => 'администратор',
            'slug'        => 'admin'
        ]);

        \Kodeine\Acl\Models\Eloquent\Role::updateOrCreate(['name' => 'customer'], [
            'name'        => 'customer',
            'description' => 'покупатель',
            'slug'        => 'customer'
        ]);

        \Kodeine\Acl\Models\Eloquent\Role::updateOrCreate(['name' => 'manager'], [
            'name'        => 'manager',
            'description' => 'менеджер',
            'slug'        => 'manager'
        ]);

//        $roles = factory(\Kodeine\Acl\Models\Eloquent\Role::class, 3)->create();
    }
}

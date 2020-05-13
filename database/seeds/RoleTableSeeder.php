<?php

use Illuminate\Database\Seeder;
use \Kodeine\Acl\Models\Eloquent\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::updateOrCreate(['name' => 'administrator'], [
            'name'        => 'administrator',
            'description' => 'администратор',
            'slug'        => 'admin'
        ]);

        Role::updateOrCreate(['name' => 'customer'], [
            'name'        => 'customer',
            'description' => 'покупатель',
            'slug'        => 'customer'
        ]);

        Role::updateOrCreate(['name' => 'manager'], [
            'name'        => 'manager',
            'description' => 'менеджер',
            'slug'        => 'manager'
        ]);

//        $roles = factory(Role::class, 3)->create();
    }
}

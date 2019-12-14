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
        $roles = factory(\Kodeine\Acl\Models\Eloquent\Role::class, 3)->create();
    }
}

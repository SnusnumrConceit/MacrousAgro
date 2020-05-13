<?php

use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $users = factory(App\User::class, 20)->create()->each(function ($user) {
//            $user->assignRole('customer');
//        });

        $administrator = User::updateOrCreate(
            [
                'email' => 'mocrous@admin.ru',
            ],
            [
                'first_name' => 'Виктория',
                'last_name'  => 'Кривцова',
                'birthday'   => '2002-01-01',
                'email'      => 'mocrous@admin.ru',
                'password'   => bcrypt('mocrous_agro')
            ]
        )->assignRole('admin');

        $manager = User::updateOrCreate(
            [
                'email' => 'mocrous@manage.ru',
            ],
            [
                'first_name' => 'Александр',
                'last_name'  => 'Иванов',
                'birthday'   => '2002-02-02',
                'email'      => 'mocrous@manage.ru',
                'password'   => bcrypt('mocrous_manage')
            ]
        )->assignRole('manager');

        $customer = User::updateOrCreate(
            [
                'email' => 'mocrous@mail.ru',
            ],
            [
                'first_name' => 'Дмитрий',
                'last_name'  => 'Васильев',
                'email'      => 'mocrous@mail.ru',
                'birthday'   => '2002-03-03',
                'password'   => bcrypt('mocrous_client')
            ])->assignRole('customer');
    }
}

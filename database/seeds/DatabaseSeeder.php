<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CategoryTableSeeder::class);
        $this->call(ProductTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(UserTableSeeder::class);
//        $this->call(ArticleTableSeeder::class);
//        $this->call(PhotoTableSeeder::class);
//        $this->call(RoleTableSeeder::class);
//        $this->call(VideoTableSeeder::class);
    }
}

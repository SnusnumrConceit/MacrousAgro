<?php

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** Создание категории Комбикорма **/
        $combi_category = Category::firstOrCreate([
            'name' => 'Комбикорм'
        ]);
    }
}

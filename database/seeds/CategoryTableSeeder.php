<?php

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
        $combi_category = \App\Models\Category::firstOrCreate([
            'name' => 'Комбикорм'
        ]);

        /** Создание категории Животноводства **/
        $animal_category = \App\Models\Category::firstOrCreate([
            'name' => 'Животноводство'
        ]);
    }
}

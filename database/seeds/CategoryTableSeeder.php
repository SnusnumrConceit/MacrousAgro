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

//        $combi_category->attributes()->delete();
//
//        $combi_category->attributes()->create([
//            'name' => 'вес',
//            'unit' => 'кг'
//        ]);

//        $combi_category->attributes()->create([
//            'name' => 'вес',
//            'unit' => 'т'
//        ]);



//        $animal_category->attributes()->delete();
//
//        $animal_category->attributes()->create([
//            'name' => 'количество голов'
//        ]);
//
//        $animal_category->attributes()->delete();

//        $categories = factory(App\Models\Category::class, 5)->create();
    }
}

<?php

use App\Models\Photo;
use Illuminate\Database\Seeder;

class PhotoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $photos = factory(Photo::class, 30)->create();
    }
}

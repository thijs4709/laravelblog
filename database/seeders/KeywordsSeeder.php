<?php

namespace Database\Seeders;

use App\Models\Keyword;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KeywordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $keywords = ["classic", "retro", "modern"];

        foreach ($keywords as $keyword) {
            Keyword::create(["name" => $keyword]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $categories = [
            "Politics",
            "Lifestyle",
            "Travel",
            "Health",
            "Entertainment",
            "Sport",
        ];

        foreach ($categories as $category) {
            $slug = str::slug($category, "-");
            Category::create(["name" => $category, "slug" => $slug]);
        }
    }
}

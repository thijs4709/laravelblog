<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //\App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        $this->call([
            RoleSeeder::class,
            PhotoSeeder::class,
            UsersTableSeeder::class,
            UsersRolesTableSeeder::class,
            PostSeeder::class,
            CategorySeeder::class,
            PostCategorySeeder::class,
            CommentSeeder::class,
            KeywordsSeeder::class,
        ]);
        DB::statement("SET FOREIGN_KEY_CHECKS=1;");
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Memo;
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
        $this->call(UsersTableSeeder::class);
        $this->call(AdminsTableSeeder::class);
        $this->call(CatgoriesTableSeeder::class);
        $this->call(MemosTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(MemoTagTableSeeder::class);
    }
}

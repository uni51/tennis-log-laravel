<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Memo;
use Illuminate\Database\Seeder;
use Cviebrock\EloquentTaggable\Models\Tag;

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
        $this->call(TaggableTagsTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(MemoTagTableSeeder::class);

        // cviebrock/eloquent-taggable 用の処理（taggable_taggablesテーブルへのデータ挿入）
        $dummyTags = Tag::get();
        $dummyMemos = Memo::get();
        foreach ($dummyMemos as $dummyMemo) {
            $dummyTags = $dummyTags->shuffle();
            $selectdTags = $dummyTags->random(3);
            $dummyMemo->tag($selectdTags);
        }
    }
}

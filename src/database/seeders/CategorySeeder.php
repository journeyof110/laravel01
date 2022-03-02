<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->delete();

        $categories = [
            [
                'id' => 1,
                'name' => 'テストデータ',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'name' => 'プログラミング',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('categories')->insert($categories);
    }
}

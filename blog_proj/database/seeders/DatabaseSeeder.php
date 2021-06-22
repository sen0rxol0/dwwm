<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;

function rand_str($length)
{
    $charset = str_split(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdfghijklmnopqrstuvwxyz0123456789|/\-_,.~`^[]{}()!@?>=<+;:#$%&*"'));
    $str = '';
    for ($i = 0; $i <= $length; $i++) {
        $str .= $charset[random_int(0, count($charset) - 1)];
    }
    return $str;
}

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(1)->create();
        for($i = 0; $i < 20; $i++) {
            Category::create([
                'name' => rand_str(16)
            ]);
        }
        User::create([
            "name" => "vw",
            "email" => "vw@gmail.com",
            "password" => Hash::make('vw@gmail.com'),
            "role" => "admin"
        ]);
    }
}

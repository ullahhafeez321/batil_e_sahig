<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Hash;
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
        // \App\Models\User::factory(1)->create();

        $password = Hash::make('fariajan'); # my little sister

        \App\Models\User::factory()->create([
            'name' => 'Hafeez Qadir',
            'email' => 'ullahhafeez321@gmail.com',
            'password' => $password,
        ]);
    }
}

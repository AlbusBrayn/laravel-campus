<?php

namespace Database\Seeders;

use App\Models\PostTitle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('admins')->insert([
            'name' => 'Test Admin',
            'email' => 'test@test.com',
            'password' => \Hash::make('12345678'),
        ]);
    }
}

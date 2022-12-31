<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('schools')->insert([
            'name' => 'Yeditepe Üniversitesi',
            'email_pattern' => 'std.yeditepe.edu.tr',
            'latitude' => '40.97185883728523',
            'longitude' => '29.15220554654341',
            'latitude_delta' => '0.015',
            'longitude_delta' => '0.0121'
        ]);

        \DB::table('majors')->insert([
            [
                'title' => 'Antropoloji Bölümü'
            ],
            [
                'title' => 'Beslenme ve Diyetetik Bölümü'
            ],
            [
                'title' => 'Bilgi Güvenliği Teknolojisi Bölümü'
            ],
            [
                'title' => 'Bilgisayar Mühendisliği'
            ]
        ]);

        \DB::table('school_majors')->insert([
            [
                'school_id' => 1,
                'major_id' => 1
            ],
            [
                'school_id' => 1,
                'major_id' => 2
            ],
            [
                'school_id' => 1,
                'major_id' => 3
            ],
            [
                'school_id' => 1,
                'major_id' => 4
            ]
        ]);
    }
}

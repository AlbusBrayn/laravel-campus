<?php

namespace Database\Seeders;

use App\Models\PostTitle;
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

        \DB::table('courses')->insert([
            [
                'school_id' => 1,
                'major_id' => 1,
                'name' => 'Antropolojiye Giriş'
            ],
            [
                'school_id' => 1,
                'major_id' => 1,
                'name' => 'Kültür ve Arkeoloji'
            ],
            [
                'school_id' => 1,
                'major_id' => 2,
                'name' => 'Beslenme İlkeleri'
            ],
            [
                'school_id' => 1,
                'major_id' => 2,
                'name' => 'Temel Kimya'
            ],
            [
                'school_id' => 1,
                'major_id' => 3,
                'name' => 'Siber Güvenliğe Giriş'
            ],
            [
                'school_id' => 1,
                'major_id' => 3,
                'name' => 'Bilgi Teknolojilerine Giriş'
            ],
            [
                'school_id' => 1,
                'major_id' => 4,
                'name' => 'Genel Matematik'
            ],
            [
                'school_id' => 1,
                'major_id' => 4,
                'name' => 'Diferansiyal Denklemler'
            ]
        ]);

        \DB::table('teachers')->insert([
            [
                'name' => 'Prof. Dr. Mehmet Ali Özkan',
                'is_admin' => false
            ],
            [
                'name' => 'Doç. Dr. Özlem Özkan',
                'is_admin' => false
            ],
            [
                'name' => 'Prof. Dr. Necati Özkan',
                'is_admin' => false
            ],
            [
                'name' => 'Prof. Dr. Ekmelettin Buhranoğlu',
                'is_admin' => false
            ],
            [
                'name' => 'Prof. Dr. Necbahat Bayraktar',
                'is_admin' => false
            ],
            [
                'name' => 'Prof. Dr. Mehmet Ali Bayraktar',
                'is_admin' => false
            ],
            [
                'name' => 'Prof. Dr. Serpil Damsız',
                'is_admin' => false
            ],
            [
                'name' => 'Doç. Dr. Mehmet Ali Erbil',
                'is_admin' => false
            ],
            [
                'name' => 'Prof. Dr. Remzi Ütübaşlı',
                'is_admin' => false
            ],
            [
                'name' => 'Doç. Dr. Remziye Ütenoğlu',
                'is_admin' => false
            ],
            [
                'name' => 'Prof. Dr. Mohammad Alim',
                'is_admin' => false
            ],
            [
                'name' => 'Doç. Dr. Rümeysa Konakoğlu',
                'is_admin' => false
            ],
            [
                'name' => 'Prof. Dr. Şabahat Taşralı',
                'is_admin' => false
            ],
            [
                'name' => 'Doç. Dr. Rukiye Romanov',
                'is_admin' => false
            ],
            [
                'name' => 'Prof. Dr. İbram İbramov',
                'is_admin' => false
            ],
            [
                'name' => 'Doç. Dr. Kadir Kerim Kerimoğulları',
                'is_admin' => false
            ]
        ]);

        \DB::table('teacher_courses')->insert([
            [
                'teacher_id' => 1,
                'course_id' => 1
            ],
            [
                'teacher_id' => 2,
                'course_id' => 1
            ],
            [
                'teacher_id' => 3,
                'course_id' => 2
            ],
            [
                'teacher_id' => 4,
                'course_id' => 2
            ],
            [
                'teacher_id' => 5,
                'course_id' => 3
            ],
            [
                'teacher_id' => 6,
                'course_id' => 3
            ],
            [
                'teacher_id' => 7,
                'course_id' => 4
            ],
            [
                'teacher_id' => 8,
                'course_id' => 4
            ],
            [
                'teacher_id' => 9,
                'course_id' => 5
            ],
            [
                'teacher_id' => 10,
                'course_id' => 5
            ],
            [
                'teacher_id' => 11,
                'course_id' => 6
            ],
            [
                'teacher_id' => 12,
                'course_id' => 6
            ],
            [
                'teacher_id' => 13,
                'course_id' => 7
            ],
            [
                'teacher_id' => 14,
                'course_id' => 7
            ],
            [
                'teacher_id' => 15,
                'course_id' => 8
            ],
            [
                'teacher_id' => 16,
                'course_id' => 8
            ]
        ]);

        PostTitle::create([
           'tr' => [
               'title' => 'Kampüs Hayatı'
           ],
          'en' => [
            'title' => 'Campus Life'
          ]
        ]);

        PostTitle::create([
            'tr' => [
                'title' => 'Bölüm Dersleri'
            ],
            'en' => [
                'title' => 'Department Courses'
            ]
        ]);
    }
}

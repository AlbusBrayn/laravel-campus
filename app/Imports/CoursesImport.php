<?php

namespace App\Imports;

use App\Models\Courses;
use App\Models\TeacherCourses;
use App\Models\Teachers;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CoursesImport implements ToModel, WithHeadingRow, WithBatchInserts
{
    use Importable;

    /**
     * @param array $row
     * @return null
     */
    public function model(array $row)
    {
        DB::transaction(function () use ($row) {
            $course = Courses::create([
                'school_id' => $row['school_id'],
                'major_id' => $row['major_id'],
                'name' => $row['name'],
                'is_active' => $row['is_active']
            ]);

            $teacherNames = explode(',', $row['teachers']);
            foreach ($teacherNames as $teacherName) {
                $teacher = Teachers::firstOrCreate([
                    'name' => $teacherName,
                    'is_active' => true
                ]);

                TeacherCourses::create([
                    'teacher_id' => $teacher->id,
                    'course_id' => $course->id
                ]);
            }
        });

       
        return null;
    }

    public function batchSize(): int
    {
        return 1000;
    }
}

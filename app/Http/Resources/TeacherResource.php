<?php

namespace App\Http\Resources;

use App\Models\UserTeacher;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array..
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = $request->user();
        $selected = false;
        if ($user) {
            $userTeacher = UserTeacher::where(['user_id' => $user->id, 'teacher_course_id' => $this->id])->first();
            if ($userTeacher) {
                $selected = true;
            }
        }

        return [
            'id' => $this->teacher->id,
            'name' => $this->teacher->name,
            'is_admin' => (bool)$this->teacher->is_admin,
            'selected' => $selected
        ];
    }
}

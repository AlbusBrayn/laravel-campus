<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserTeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->teacherCourse->teacher->id,
            'name' => $this->teacherCourse->teacher->name,
            'is_admin' => (bool)$this->teacherCourse->teacher->is_admin,
            'points' => 10,
            'color' => 'green'
        ];
    }
}

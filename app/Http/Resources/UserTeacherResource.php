<?php

namespace App\Http\Resources;

use App\Models\TeacherVote;
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
        $id = $this->id ?? $this->resource->id;
        $name = $this->name ?? $this->resource->name;
        $is_admin = $this->is_admin ?? $this->resource->is_admin;
        $tpoint = $this->point ?? $this->resource->point;

        if (TeacherVote::where(['teacher_id' => $id])->exists()) {
            $teacherPoints = TeacherVote::find($id);
            $point = ($teacherPoints->quality + $teacherPoints->attitude + $teacherPoints->performance) / 3;
        } else {
            $point = 10;
        }
        return [
            'id' => $id,
            'name' => $name,
            'is_admin' => (bool)$is_admin,
            'points' => $tpoint ?? $point,
            'color' => getColor($tpoint ?? $point),
        ];
    }
}

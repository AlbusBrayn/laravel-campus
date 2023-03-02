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
        if (!isset($this->id)) {
            $this->id = $this->resource->id;
            $this->name = $this->resource->name;
            $this->is_admin = $this->resource->is_admin;
            if (isset($this->respource->point)) {
                $this->point = $this->resource->point;
            }
        }

        if (TeacherVote::where(['teacher_id' => $this->id])->exists()) {
            $teacherPoints = TeacherVote::find($this->id);
            $point = ($teacherPoints->quality + $teacherPoints->attitude + $teacherPoints->performance) / 3;
        } else {
            $point = 10;
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_admin' => (bool)$this->is_admin,
            'points' => $this->point ?? $point,
            'color' => getColor($this->point ?? $point),
        ];
    }
}

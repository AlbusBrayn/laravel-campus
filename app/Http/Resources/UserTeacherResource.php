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
        dd($this->id);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_admin' => (bool)$this->is_admin,
            'points' => $this->point ?? $point,
            'color' => getColor($this->point ?? $point),
        ];
    }
}

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
        if (TeacherVote::where(['teacher_id' => $this->id])->exists()) {
            $votes = TeacherVote::where(['teacher_id' => $this->id])->get();
            $quality = 0;
            $attitude = 0;
            $performance = 0;
            foreach ($votes as $vote) {
                $quality += $vote->quality;
                $attitude += $vote->attitude;
                $performance += $vote->performance;
            }
            $qualityRate = $quality / count($votes);
            $attitudeRate = $attitude / count($votes);
            $performanceRate = $performance / count($votes);
            $point = ($qualityRate + $attitudeRate + $performanceRate) / 3;
        } else {
            $point = 10;
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_admin' => (bool)$this->is_admin,
            'points' => $point,
            'qualityRate' => $qualityRate ?? 10,
            'attitudeRate' => $attitudeRate ?? 10,
            'performanceRate' => $performanceRate ?? 10,
            'color' => getColor($this->point ?? $point),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherVoteResource extends JsonResource
{
    /**
     * Transform the resource into an array..
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $point = ($this->quality + $this->attitude + $this->performance) / 3;
        return [
            'id' => $this->id,
            'user' => new UserResource($this->user),
            'point' => $point,
            'pointColor' => getColor($point),
            'quality' => $this->quality,
            'qualityColor' => getColor($this->quality),
            'attitude' => $this->attitude,
            'attitudeColor' => getColor($this->attitude),
            'performance' => $this->performance,
            'performanceColor' => getColor($this->performance),
            'comment' => $this->comment,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

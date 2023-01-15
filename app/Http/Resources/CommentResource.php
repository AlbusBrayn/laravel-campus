<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'id' => $this->id,
            'body' => $this->body,
            'avatar' => $this->user->avatar,
            'parent_id' => $this->parent_id,
            'replies' => (count($this->replies) > 0) ? CommentResource::collection($this->replies) : [],
        ];
    }
}

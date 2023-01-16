<?php

namespace App\Http\Resources;

use App\Models\Avatar;
use App\Models\Comment;
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
            'user_id' => $this->id,
            'name' => $this->user->name,
            'avatar' => Avatar::where(['user_id' => $this->user->id])->first(),
            'parent_id' => $this->parent_id,
            'replies' => CommentResource::collection($this->replies),
            'created_at' => $this->created_at,
        ];
    }
}

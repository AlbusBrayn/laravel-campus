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
        $user = $request->user();

        return [
            'id' => $this->id,
            'body' => $this->body,
            'user_id' => $this->user->id,
            'is_admin' => $user->id === $this->user->id,
            'name' => $this->user->name,
            'avatar' => Avatar::where(['user_id' => $this->user->id])->first(),
            'parent_id' => $this->parent_id,
            'replies' => CommentResource::collection($this->replies),
            'like' => $this->like_count,
            'dislike' => $this->dislike_count,
            'created_at' => $this->created_at,
        ];
    }
}

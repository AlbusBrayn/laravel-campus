<?php

namespace App\Http\Resources;

use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'title' => $this->title,
            'content' => $this->content,
            'like' => $this->like,
            'dislike' => $this->dislike,
            'writer' => User::find($this->user_id)->name,
            'action' => isLiked($request->user()->id, $this->id),
            'is_admin' => $request->user()->id === $this->user_id,
            'profile' => User::find($this->user_id)->avatar,
        ];
    }
}

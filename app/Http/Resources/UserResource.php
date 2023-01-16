<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        dd($request->sender_id);
        $sender = User::find($request->sender_id);

        return [
            'name' => $sender->name,
            'avatar' => $sender->avatar,
            'created_at' => $request->created_at
        ];
    }
}

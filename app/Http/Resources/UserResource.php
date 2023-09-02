<?php

namespace App\Http\Resources;

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
        return [
            'id' => $this->id,
            // ニックネームが設定されないことは基本的にはない筈だが、念の為の処理
            'nickname' => $this->nickname ?? $this->email,
            // ネームが設定されないことは基本的にはない筈だが、念の為の処理
            'name' => $this->name ?? $this->email,
        ];
    }
}

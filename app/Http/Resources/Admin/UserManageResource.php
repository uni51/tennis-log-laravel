<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class UserManageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'firebase_uid' => $this->firebase_uid,
            'name' => $this->name,
            'nickname' => $this->nickname,
            'email' => $this->email,
        ];
    }
}

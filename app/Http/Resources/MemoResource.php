<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MemoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user_name' => $this->user->name,
            'user_nickname' => $this->user->nickname,
            'title' => $this->title,
            'body' => $this->body,
            'category_id' => $this->category_id,
            'category_name' => $this->category->name,
            'tag_list' => [
                'tags' => $this->tagArray,
                'normalized' => $this->tagArrayNormalized,
            ],
            'status' => $this->status,
            'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('Y年m月d日'),
            'updated_at' => Carbon::createFromFormat('Y-m-d H:i:s', $this->updated_at)->format('Y年m月d日'),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MemoResource extends JsonResource
{
    /**
     * @param $request
     * @return array
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
                'tags' => $this->tags->pluck('name'),
            ],
            'status' => $this->status,
            'favorites_count' => $this->bookmarkedBy()->count(), // お気に入りの件数を追加
            'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('Y年m月d日'),
            'updated_at' => Carbon::createFromFormat('Y-m-d H:i:s', $this->updated_at)->format('Y年m月d日'),
        ];
    }
}

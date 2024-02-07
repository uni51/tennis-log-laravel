<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentTaggable\Taggable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeletedMemo extends Model
{
    use HasFactory, Taggable;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'body',
        'status',
        'force_deleted',
    ];

    public function category() {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}

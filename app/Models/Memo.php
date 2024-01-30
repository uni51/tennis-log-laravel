<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentTaggable\Taggable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int|mixed|string|null $user_id
 * @property mixed $category_id
 * @property mixed $status
 * @property mixed $title
 * @property mixed $body
 * @method static findOrFail(int $id)
 * @method static where(string $string, mixed $value)
 * @method static find(int $id)
 */
class Memo extends Model
{
    use HasFactory, Taggable;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'body',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}

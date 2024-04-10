<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int|mixed|string|null $user_id
 * @property mixed $category_id
 * @property mixed $status
 * @property mixed $title
 * @property mixed $body
 * @method static findOrFail(int $id)
 * @method static where(string $string, mixed $value)
 * @method static find(int $id)
 * @method static create(array $array)
 */
class Memo extends Model
{
//    use HasFactory, Taggable;
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'body',
        'status',
        'chatgpt_review_status',
        'chatgpt_reviewed_at',
        'admin_review_status',
        'admin_reviewed_at',
        'status_at_review',
        'times_notified_to_fix',
        'times_attempt_to_fix',
        'approved_by',
        'approved_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'memo_tag', 'memo_id', 'tag_id')
                    ->withTimestamps();
    }
}

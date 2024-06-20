<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static firstOrCreate(array $array)
 */
class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'created_by',
        'created_by_admin'
    ];

    public function memos(): BelongsToMany
    {
        return $this->belongsToMany(Memo::class, 'memo_tag', 'tag_id', 'memo_id')
                    ->withTimestamps();
    }
}

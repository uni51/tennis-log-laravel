<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeletedTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'tag_id',
        'name',
        'created_by',
        'created_by_admin',
        'tag_created_at',
        'tag_updated_at',
        'is_force_deleted'
    ];

    public function category() {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}

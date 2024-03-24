<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForceDeletedMemo extends Model
{
    use HasFactory;

    protected $fillable = [
        'memo_id',
        'user_id',
        'category_id',
        'title',
        'body',
        'status',
        'chatgpt_review_status',
        'chatgpt_reviewed_at',
        'admin_review_status',
        'admin_reviewed_at',
        'times_notified_to_fix',
        'times_attempt_to_fix_after_notified',
        'approved_at',
        'memo_created_at',
        'memo_updated_at',
    ];

    public function category() {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}

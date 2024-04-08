<?php

namespace App\Lib;

use App\Enums\MemoAdminReviewStatusType;
use App\Enums\MemoChatGptReviewStatusType;
use App\Enums\MemoStatusType;
use App\Models\Memo;

class MemoHelper
{
    public static function setReviewValueByChatGpt(array $validated, bool $isNotTennisRelated): array
    {
        if ($isNotTennisRelated) {
            /* テニスに関連のないメモとChatGPTに判断された場合は、管理者でレビューするために以下の情報をセット */
            $validated['chatgpt_review_status'] = MemoChatGptReviewStatusType::NG_GPT_REVIEW;
            $validated['admin_review_status'] = MemoAdminReviewStatusType::REVIEW_REQUIRED;
        } else {
            $validated['chatgpt_review_status'] = MemoChatGptReviewStatusType::PASSED_GPT_REVIEW;
        }
        $validated['chatgpt_reviewed_at'] = now()->format('Y-m-d H:i:s');

        return $validated;
    }

    public static function setFixReviewValueByChatGpt(array $validated, bool $isNotTennisRelated, Memo $memo): array
    {
        if ($isNotTennisRelated) {
            /* テニスに関連のないメモとChatGPTに判断された場合は、管理者でレビューするために以下の情報をセット */
            $validated['chatgpt_review_status'] = MemoChatGptReviewStatusType::NG_GPT_REVIEW;
            $validated['admin_review_status'] = MemoAdminReviewStatusType::REVIEW_REQUIRED;
        } else {
            $validated['chatgpt_review_status'] = MemoChatGptReviewStatusType::PASSED_GPT_REVIEW;
            $validated['admin_review_status'] = MemoAdminReviewStatusType::AFTER_FIX_REQUIRED_PASSED_GPT_REVIEW;
            $validated['status'] = $memo->status_at_review ?? MemoStatusType::DRAFT;
            $validated['approved_at'] = now()->format('Y-m-d H:i:s');
        }

        $validated['chatgpt_reviewed_at'] = now()->format('Y-m-d H:i:s');
        $validated['times_attempt_to_fix_after_notified'] = $memo->times_attempt_to_fix_after_notified + 1;

        return $validated;
    }
}

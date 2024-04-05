<?php

namespace App\Lib;

use App\Enums\MemoAdminReviewStatusType;
use App\Enums\MemoChatGptReviewStatusType;

class MemoHelper
{
    public static function setReviewValueByChatGpt(array $validated, bool $isNotTennisRelated): array
    {
        if ($isNotTennisRelated) {
            /* テニスに関連のないメモとChatGPTに判断された場合は、管理者でレビューするために以下の情報をセット */
            $validated['chatgpt_review_status'] = MemoChatGptReviewStatusType::NG_CHAT_GPT_REVIEW;
            $validated['chatgpt_reviewed_at'] = now()->format('Y-m-d H:i:s');
            $validated['admin_review_status'] = MemoAdminReviewStatusType::REVIEW_REQUIRED;
        } else {
            $validated['chatgpt_review_status'] = MemoChatGptReviewStatusType::PASSED_CHAT_GPT_REVIEW;
            $validated['chatgpt_reviewed_at'] = now()->format('Y-m-d H:i:s');
        }
        return $validated;
    }
}

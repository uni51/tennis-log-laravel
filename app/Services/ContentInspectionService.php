<?php

namespace App\Services;

use App\Events\NotTennisRelatedNotificationEvent;
use App\Models\User;
use App\Models\Memo;
use App\Mail\NotTennisRelatedMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class ContentInspectionService
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function inspectContentAndRespond(array $validated, User $user): ?JsonResponse
    {
        // タイトルの不適切な表現のチェック
        if ($this->openAIService->checkForInappropriateContent($validated['title'])) {
            return $this->generateErrorResponse($user,'title', 'タイトルに不適切な表現が含まれています。修正してください。');
        }

        // 本文の不適切な表現のチェック
        if ($this->openAIService->checkForInappropriateContent($validated['body'])) {
            return $this->generateErrorResponse($user,'body', '本文に不適切な表現が含まれています。修正してください。');
        }

        // タグの不適切な表現のチェック
        foreach ($validated['tags'] as $tag) {
            if ($this->openAIService->checkForInappropriateContent($tag)) {
                return $this->generateErrorResponse($user,'tags', 'タグに不適切な表現が含まれています。修正してください。');
            }
        }

        // 全てのチェックが通った場合
        return null;
    }

    /**
     * @param User $user
     * @param string $field
     * @param string $message
     * @return JsonResponse
     */
    protected function generateErrorResponse(User $user, string $field, string $message): JsonResponse
    {
        $user->increment('inappropriate_posts_count');
        $user->save();

        return response()->json([
            'errors' => [$field => [$message]],
        ], 422);
    }

    public function notifyAdminNotTennisRelatedEmail(array $validated, Memo $memo, User $user): void
    {
            $content = "<p>タイトル: {$validated['title']}</p>
<p>本文: {$validated['body']}</p>
<p>タグ: " . implode(', ', $validated['tags'])."</p>";

            // テニスに関連のない記事の場合は、管理者にメール送信
            event(new NotTennisRelatedNotificationEvent($content, $user, $memo));
    }
}

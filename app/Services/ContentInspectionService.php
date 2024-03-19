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

    public function inspectContentAndRespond(array $validated, User $user, ?Memo $memo): ?JsonResponse
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

        // テニスに関する内容ではないかどうかの判定は、title, body, tag の全てを一緒にして行う
        // タイトル、本文、タグを結合
        $combinedContent = $validated['title'] . "\n" . $validated['body'] . "\n" . implode("\n", $validated['tags']);

        // 結合したテキストがテニスに関連する内容かどうかを判断
        $isNotTennisRelated = $this->openAIService->isNotTennisRelated($combinedContent);

        if ($isNotTennisRelated) {
            $content = "<p>タイトル: {$validated['title']}</p>
<p>本文: {$validated['body']}</p>
<p>タグ: " . implode(', ', $validated['tags'])."</p>";

            // 管理者にメール送信
            // Mail::to($adminEmail)->send(new NotTennisRelatedEmail($content));
            event(new NotTennisRelatedNotificationEvent($content, $user, $memo));

            // 通常の処理を続けるか、適切なレスポンスを返す
        }

        // 全てのチェックが通った場合
        return null;
    }

    /**
     * エラーレスポンスを生成する
     *
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
}

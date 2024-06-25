<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class ContentInspectionService
{
    protected OpenAIService $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    /**
     * @param array $validated
     * @param User $user
     * @return JsonResponse|null
     */
    public function validateIsInappropriate(array $validated, User $user): ?JsonResponse
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
        // 不適切と判断された投稿の回数をインクリメント
        $user->increment('count_inappropriate_posts');
        $user->save();

        return response()->json([
            'errors' => [$field => [$message]],
        ], 422);
    }
}

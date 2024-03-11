<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    protected $apiKey;
    protected $url;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
        $this->url = "https://api.openai.com/v1/chat/completions";
    }

    private function getDefaultHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ];
    }

    public function generalInquiries($question, $targetContent)
    {
        $headers = $this->getDefaultHeaders();

        $data = [
            "model" => "gpt-3.5-turbo",
            "messages" => [
                ["role" => "system", "content" => $question],
                ["role" => "user", "content" => $targetContent]
            ]
        ];

        $response = Http::withHeaders($headers)->post($this->url, $data);

        if ($response->json('error')) {
            return $response->json('error')['message'];
        }

        return $response->json('choices')[0]['message']['content'];
    }

    /**
     * @param string $targetContent
     * @return bool | JsonResponse
     */
    public function checkForInappropriateContent(string $targetContent): bool | JsonResponse
    {
        $headers = $this->getDefaultHeaders();

        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => "この文章に不適切な表現（差別的、暴力的、性的な表現や誹謗中傷）が含まれていますか。yes or noで答えてください。\n\n" . $targetContent]
            ],
        ];

        $response = Http::withHeaders($headers)->post($this->url, $data);

        if ($response->json('error')) {
            return $response->json('error')['message'];
        }

        $reply = $response->json('choices')[0]['message']['content'];

        // yes が含まれているかどうかを返す true（1） or false（0）
        return str_contains(strtolower($reply), 'yes');
    }

    /**
     * @param string $targetContent
     * @return bool | JsonResponse
     */
    public function isNotTennisRelated(string $targetContent): bool | JsonResponse
    {
        $headers = $this->getDefaultHeaders();

        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => "この文章はテニスに関する内容ですか？。yes or noで答えてください。\n\n" . $targetContent]
            ],
        ];

        $response = Http::withHeaders($headers)->post($this->url, $data);

        if ($response->json('error')) {
            return $response->json('error')['message'];
        }

        $reply = $response->json('choices')[0]['message']['content'];

        // no が含まれているかどうかを返す true（1） or false（0）
        return str_contains(strtolower($reply), 'no');
    }
}

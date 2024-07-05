<?php
namespace App\Services;

use App\Repositories\ProfileRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ProfileService
{
    /**
     * コンストラクタ
     *
     * @param ProfileRepository|null $repository
     */
    public function __construct(ProfileRepository $repository = null)
    {
        $this->repository = $repository ?? app(ProfileRepository::class);
    }

    /**
     * プロフィールの登録処理
     *
     * @param array $validated
     * @return JsonResponse
     * @throws Exception
     */
    public function createProfile(array $validated): JsonResponse
    {
        try {
            $this->repository->profileCreate($validated);
            return response()->json([
                'message' => 'プロフィールの登録に成功しました。'
            ], 201);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception('プロフィールの登録に失敗しました。');
        }
    }
}

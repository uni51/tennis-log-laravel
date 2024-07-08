<?php
namespace App\Services;

use App\Http\Resources\ProfileResource;
use App\Models\User;
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
            $this->repository->createProfile($validated);
            return response()->json([
                'message' => 'プロフィールの登録に成功しました。'
            ], 201);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception('プロフィールの登録に失敗しました。');
        }
    }

    /**
     * プロフィールの取得処理
     *
     * @return ProfileResource
     * @throws Exception
     */
    public function getProfile(): ProfileResource
    {
        try {
            $profile = $this->repository->getProfile();
            return new ProfileResource($profile);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception('プロフィールの取得に失敗しました。');
        }
    }

    public function editProfile(array $validated, User $user): JsonResponse
    {
        try {
            $this->repository->editProfile($validated, $user);
            return response()->json([
                'message' => 'プロフィールの編集に成功しました。'
            ], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception('プロフィールの編集に失敗しました。');
        }
    }
}

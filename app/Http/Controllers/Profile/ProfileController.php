<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\DashboardMemos\DashboardMemoEditRequest;
use App\Http\Requests\Profile\ProfileCreateRequest;
use App\Http\Requests\Profile\ProfileEditRequest;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use App\Services\DashboardMemoService;
use Exception;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class ProfileController extends Controller
{

    /**
     * プロフィールの登録処理
     *
     * @param ProfileCreateRequest $request
     * @param ProfileService $service
     * @return JsonResponse
     * @throws Exception
     */
    public function createProfile(ProfileCreateRequest $request, ProfileService $service): JsonResponse
    {
        $validated = $request->validated();
        return $service->createProfile($validated);
    }

    /**
     * プロフィールの取得処理
     *
     * @param ProfileService $service
     * @return ProfileResource
     * @throws Exception
     */
    public function getProfile(ProfileService $service): ProfileResource
    {
        return $service->getProfile();
    }

    public function editProfile(ProfileEditRequest $request, ProfileService $service): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $validated = $request->validated();
        return $service->editProfile($validated, $user);
    }
}

<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\ProfileCreateRequest;
use Exception;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
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
}

<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\User;
use App\Models\UserBadge;
use App\Models\UserPreferences;
use App\Models\UserProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * Class ProfileController
 * @package App\Http\Controllers
 */
class ProfileController extends BaseController
{
    /**
     * Get Public User Data
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getPublicData(Request $request): JsonResponse
    {
        $userData = User::where('username', $request->input('name'))->first();

        if ($userData == null)
            return response()->json('', 404);

        $userPreferences = UserPreferences::find($userData->uniqueId);

        $userData->selectedBadges = UserBadge::where('user_id', $userData->uniqueId)->where('slot_id', '>', 0)->get() ?? [];
        $userData->profileVisible = $userPreferences == null ? true : $userPreferences->profileVisible == '1';

        return response()->json($userData);
    }

    /**
     * Get Public User Profile
     *
     * @param int $userId
     * @return JsonResponse
     */
    public function getPublicProfile($userId): JsonResponse
    {
        $userData = User::find($userId);

        if ($userData == null)
            return response()->json('', 404);

        $userPreferences = UserPreferences::find($userData->uniqueId);

        if ($userPreferences != null && $userPreferences->profileVisible == '0')
            return response()->json('', 404);

        return response()->json(new UserProfile($userData));
    }

    /**
     * Get Private User Profile
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getProfile(Request $request): JsonResponse
    {
        return response()->json(new UserProfile($request->user()));
    }

    /**
     * Get User Stories
     *
     * @TODO: Implement Habbo Stories
     *
     * @return JsonResponse
     */
    public function getStories(): JsonResponse
    {
        return response()->json([]);
    }

    /**
     * Get User Photos
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getPhotos(Request $request): JsonResponse
    {
        if (Photo::where('creator_id', $request->user()->uniqueId)->count() == 0)
            return response()->json([]);

        $userPhotos = Photo::where('creator_id', $request->user()->uniqueId)->get();

        return response()->json($userPhotos);
    }
}

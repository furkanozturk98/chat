<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileSettingFormRequest;
use App\Http\Resources\ProfileSettingResource;
use App\Services\ProfileService;

class ProfileController extends Controller
{
    public function __construct(public ProfileService $profileService)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return ProfileSettingResource
     */
    public function index(): ProfileSettingResource
    {
        return new ProfileSettingResource(auth()->user());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProfileSettingFormRequest $request
     *
     * @return ProfileSettingResource
     */
    public function update(ProfileSettingFormRequest $request): ProfileSettingResource
    {
        $data = $this->profileService->updateProfile($request->validated());

        return new ProfileSettingResource($data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileSettingFormRequest;
use App\Http\Resources\ProfileSettingResource;
use App\Repositories\ProfileSettingRepository;

class ProfileSettingController extends Controller
{

    private ProfileSettingRepository $profileSettingRepository;

    public function __construct(ProfileSettingRepository $profileSettingRepository){
        $this->profileSettingRepository = $profileSettingRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return ProfileSettingResource
     */
    public function index()
    {
        return new ProfileSettingResource(auth()->user());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProfileSettingFormRequest $request
     * @return ProfileSettingResource
     */
    public function update(ProfileSettingFormRequest $request)
    {

        $data = $this->profileSettingRepository->updateProfile($request->validated());

        return new ProfileSettingResource($data);

    }

}

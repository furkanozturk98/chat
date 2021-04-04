<?php


namespace App\Repositories;


class ProfileSettingRepository
{

    public function updateProfile(array $attributes)
    {

        $attributes = $this->prepareData($attributes);

        if (array_key_exists('image',$attributes)) {
            $this->removeOldImage(auth()->user()->image);
        }

        auth()->user()->update($attributes);

        return auth()->user();
    }

    public function prepareData($attributes)
    {

        if (!$attributes['image']) {
            unset($attributes['image']);

            return $attributes;
        }

        $imageName = time() . '.' . $attributes['image']->getClientOriginalExtension();
        $attributes['image']->move(public_path('images'), $imageName);
        $attributes['image'] = $imageName;

        return $attributes;
    }

    public function removeOldImage($oldImage)
    {
        if($oldImage === 'profile.jpg'){
            return;
        }

        $path = public_path() . '/images/' . $oldImage;

        if (file_exists($path)) {
            @unlink($path);
        }
    }

}

<?php

namespace App\Services;

use Illuminate\Contracts\Auth\Authenticatable;

class ProfileService
{
    /**
     * @param array $attributes
     *
     * @return ?Authenticatable
     */
    public function updateProfile(array $attributes): ?Authenticatable
    {
        $attributes = $this->prepareData($attributes);

        if (array_key_exists('image', $attributes)) {
            $this->removeOldImage(auth()->user()->image);
        }

        $user = auth()->user();

        $user->update($attributes);

        return $user;
    }

    /**
     * @param $attributes
     *
     * @return array
     */
    public function prepareData($attributes): array
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

    /**
     * @param $oldImage
     *
     * @return void
     */
    public function removeOldImage($oldImage): void
    {
        if ($oldImage === 'profile.jpg') {
            return;
        }

        $path = public_path() . '/images/' . $oldImage;

        if (file_exists($path)) {
            @unlink($path);
        }
    }
}

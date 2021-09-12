<?php

namespace App\Http\Controllers;

use App\Profile;
use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ProfilesController extends Controller
{


    public function index(User $user)
    {
        $follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;

        $postCount = Cache::remember(
            'count.posts.' . $user->id,
            now()->addSeconds(30),
             function () use ($user) {
            return   $user->posts->count();
        });

        $followers = Cache::remember(
            'count.followers.' . $user->id,
            now()->addSeconds(1),
             function () use ($user) {
            return     $user->profile->followers->count() ;
        });



        $followingCount = Cache::remember(
            'count.following.' . $user->id,
            now()->addSeconds(1),
             function () use ($user) {
            return  $user->following->count();
        });


        return view('profiles.index', compact('user','follows','postCount','followingCount','followers'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user->profile);

        return view('profiles.edit', compact('user'));

    }

    public function update( User $user)
    {
        $this->authorize('update', $user->profile);

        $data=request()->validate([
            'bio' => ['sometimes', 'string', 'nullable'],
            'website' => ['url','sometimes', 'nullable'],
            'image' => ''

        ]);

        //  $this->storeImage($user);

        if (request('image')) {
            $imagePath = request('image')->store('profile', 'public');
            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
            $image->save();

            $imageArray = ['image' => $imagePath];

        }

        auth()->user()->profile->update(array_merge(
            $data,
            $imageArray ?? []
        ));


        return  redirect("/profile/{$user->id}");
    }

}
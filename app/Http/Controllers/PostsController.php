<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\Facades\Image;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = auth()->user()->following()->pluck('profiles.user_id');
       $posts= Post::whereIn('user_id',$users)->with('user')->latest()->get();

       $user = Auth::User();
      return view('posts.index' ,compact('posts','user'));
    }

    public function explore()
    {

    }
    public function create()
    {
        return view('posts.create');

    }

    public function store(Request $request)
    {
       $post= Post::create($request->validate([
        'caption' => ['sometimes', 'string', 'max:255'],
        'image' => ['required', 'image', 'max:3000'],
        'user_id' => ['required'],
    ]));
        $this->storeImage($post);
        $posts= Post::all();

        return redirect()->route('home.index');


    }

    public function update(Request $request, Post $post)
    {
        $user = Auth::User();
        $post->where('user_id', $user->id)->update($request->validate([
            'caption' => ['nullable', 'string', 'max:255']

        ]));

        return redirect()->route('home.index');
    }



    public function destroy(Post $post)

    {
        $post->delete();
        return redirect()->route('home.index');


    }

    public function show(Post $post)
    {

    }

    public function updatelikes(Request $request, $post)
    {

    }

    // methods for vue api requests
    public function vue_index()
    {

    }

    private function storeImage($post) {

        if(request()->has('image')) {
            $post->update([
                'image' => request()->image->store('uploads', 'public')
            ]);
        }
    }
}
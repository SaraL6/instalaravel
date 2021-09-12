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
         $userId = Auth::User()->id;
         $user = Auth::User();
         $multiusers= [$users , $userId];
       // dd($users,$user);
       $posts= Post::whereIn('user_id',$multiusers)->with('user')->latest()->get() ;



      return view('posts.index' ,compact('posts','user'));
    }

    public function explore()
    {

    }
    public function create()
    {
        $user = Auth::User();
        return view('posts.create' ,compact('user'));

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

        return view('posts.show',compact('post'));

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
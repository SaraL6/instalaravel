<?php

namespace App\Http\Controllers;

use App\Like;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LikeController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = 3;
        $like = Like::where('user_id', $user)->where('post_id', $id)->get();
    }

    public function update2($id)
    {

        $user = Auth::User();

        $like = Like::where('user_id', $user->id)->where('post_id', $id)->first();

        if ($like) {
            // if its true we give it false, if its false we give it true
            $like->State = !$like->State;

            $like->save();
        } else {
            $like = Like::create([
                "user_id" => $user->id,
                "post_id" => $id,
                "State" => true

            ]);
        }
        $conditions = [
            ['State', 1]
        ];
          //return Redirect::to('/');.
         // $likes= Like::all();
          $likes= Like::where('post_id', $id)->where($conditions)->get();
        //   dd($likes);
         return [$like,$likes];
        // return route('home.index', compact('like','likes'));

    }

}

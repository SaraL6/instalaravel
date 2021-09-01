<?php

namespace App\Http\Controllers;

use App\Profile;
use App\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    public function edit(User $user)
    {

        return view('profiles.edit', compact('user'));

    }

    public function index( $user)
    {
        $user = User::findOrfail($user);

        return view('profiles.index', compact('user'));
    }
}
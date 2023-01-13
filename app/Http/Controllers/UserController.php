<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.profile');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'dob' => 'required',
            'phone' => 'required|min:10|max:14',
        ]);

        User::where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'dob' => $request->dob,
            'phone' => $request->phone,
        ]);

        return redirect('/profile');
    }

    public function updateImage(Request $request, $id)
    {

        $request->validate([
            'imageUrl' => 'required',
        ]);

        User::where('id', $id)->update([
            'imageUrl' => $request->imageUrl
        ]);

        return redirect('/profile');
    }
}

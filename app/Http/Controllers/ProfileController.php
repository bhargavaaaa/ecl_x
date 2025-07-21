<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateProfileRequest;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->view('profile.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request, string $id)
    {
        $requestData = $request->safe()->all();
        $avatar = auth()->user()->avatar;
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            deleteStoredImage(auth()->user()->avatar);
            $avatar = $request->file('avatar')->store('public/users');
        }

        $requestData['avatar'] = $avatar;
        auth()->user()->update($requestData);
        return redirect()->back()->with('success', 'User details has been updated.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_password(Request $request)
    {
        $request->validate([
            'current_password' => ['required', function($attribute, $value, $fail) {
                if(!Hash::check($value, auth()->user()->password)) {
                    $fail("Invalid current password.");
                }
            }],
            'password' => 'required|string|min:6|different:current_password|confirmed',
        ]);

        auth()->user()->update([
            'password' => bcrypt($request->password)
        ]);

        return redirect()->back()->with('success', 'Password has been updated.');
    }

}

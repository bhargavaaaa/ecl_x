<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\UserAuthorizable;
use App\DataTables\UserDataTable;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    use UserAuthorizable;

    /**
     * Display a listing of the resource.
     */
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = getCountries();
        $roles = Role::pluck('name', 'id');
        return response()->view('user.create', compact('roles', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $input = $request->safe()->except('password', 'role_id');
        $input["password"] = bcrypt($request->password);
        $user = User::create($input);
        $user->syncRoles([$request->role_id]);

        return redirect()->route('user.index')->with('success', 'User has been created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $countries = getCountries();
        $user = User::whereNot('id', User::first()->id)->findOrFail($id);
        $roles = Role::pluck('name', 'id');

        return response()->view('user.edit', compact('user', 'roles', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $user = User::whereNot('id', User::first()->id)->findOrFail($id);
        $input = $request->safe()->except('password', 'role_id');
        if(!empty($request->password)) {
            $input["password"] = bcrypt($request->password);
        }
        $user->update($input);
        $user->syncRoles([$request->role_id]);

        return redirect()->route('user.index')->with('success', 'User has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::whereNot('id', User::first()->id)->findOrFail($id);
        $user->syncRoles([]);
        $user->delete();

        return response()->json(["status" => true, "message" => "User has been deleted."]);
    }

    public function checkEmailUnique(Request $request, string $id = null)
    {
        $email = $request->input('email');
        if(empty($id)) {
            $isUnique = User::where('email', $email)->count() === 0;
        } else {
            $isUnique = User::where('email', $email)->whereNot('id', $id)->count() === 0;
        }

        return $isUnique ? "true" : "false";
    }

    public function checkPhoneUnique(Request $request, string $id = null)
    {
        $phone = $request->input('phone');
        if(empty($id)) {
            $isUnique = User::where('phone', $phone)->count() === 0;
        } else {
            $isUnique = User::where('phone', $phone)->whereNot('id', $id)->count() === 0;
        }

        return $isUnique ? "true" : "false";
    }
}

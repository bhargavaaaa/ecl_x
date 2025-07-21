<?php

namespace App\Http\Controllers;

use App\DataTables\RoleDataTable;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use App\Traits\RoleAuthorizable;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    use RoleAuthorizable;

    /**
     * Display a listing of the resource.
     */
    public function index(RoleDataTable $dataTable)
    {
        return $dataTable->render('role.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        return response()->view('role.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $input = $request->safe()->all();
        $role = Role::create($input);
        $role->permissions()->sync($request->permissions);

        return redirect()->route('role.index')->with('success', 'Role has been created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $role)
    {
        $role = Role::notAdmin()->findOrFail($role)->load('permissions');
        $permissions = Permission::all();

        return response()->view('role.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, string $role)
    {
        $role = Role::notAdmin()->findOrFail($role);
        $input = $request->safe()->all();
        $role->update($input);
        $role->permissions()->sync($request->permissions);

        return redirect()->route('role.index')->with('success', 'Role has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $role)
    {
        $role = Role::notAdmin()->findOrFail($role);
        $role->delete();

        return response()->json(["status" => true, "message" => "Role has been deleted."]);
    }

    public function checkNameUnique(Request $request, string $role = null)
    {
        $name = $request->input('name');
        if (empty($role)) {
            $isUnique = Role::where('name', $name)->count() === 0;
        } else {
            $isUnique = Role::where('name', $name)->whereNot('id', $role)->count() === 0;
        }

        return $isUnique ? "true" : "false";
    }
}

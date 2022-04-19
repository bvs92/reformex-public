<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('volgh.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $validated = $request->validate([
        //     'name' => 'required|min:2|max:255|alpha_dash|unique:roles',
        // ]);

        // if (!$role = Role::create($validated)) {
        //     return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        // }

        // return redirect()->route('roles.show', $role->id)->with('success', 'Rolul a fost salvat.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);
        $users = $role->users;
        $users->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'profile', 'stripe_id']);
        // $permissions = Permission::all();
        // dd($role->permissions->contains($permissions->first()));
        return view('volgh.roles.show', compact(['role', 'users']));
    }

    public function showByName($name)
    {
        $role = Role::where('name', $name)->firstOrFail();
        $users = $role->users;
        $users->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'profile', 'stripe_id']);
        // $permissions = Permission::all();
        // dd($role->permissions->contains($permissions->first()));
        return view('volgh.roles.show', compact(['role', 'users']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $role = Role::findOrFail($id);
        // return view('roles.edit', compact(['role']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $role = Role::findOrFail($id);

        // $validated = $request->validate([
        //     'name' => 'required|min:2|max:255|alpha_dash|unique:roles,name,' . $role->id,
        // ]);

        // $role->name = $validated['name'];

        // if (!$role->save()) {
        //     return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        // }

        // return redirect()->route('roles.show', $role->id)->with('success', 'Rolul a fost modificat cu succes.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd('hit');
        $role = Role::findOrFail($id);

        // Delete permissions associated
        $role->permissions()->detach();

        // Delete user's associated role.
        $role->users()->detach();

        // elimina doar daca nu a fost atasat niciunui user

        // Delete role
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Rolul a fost eliminat cu succes.');
    }

    public function giveRolePermissions(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        // dd($request->all());

        $validated = $request->validate([
            'name' => 'required|exists:permissions,id',
        ]);

        // Find Permissions
        $permissions = Permission::findOrFail($validated['name']);

        // Associate with role
        $role->syncPermissions($permissions);

        return redirect()->route('roles.show', $role->id)->with('success', 'Operatie efectuata cu succes.');

        // dd($permissions->pluck(['name']));
    }

    public function resetRolePermissions($id)
    {
        $role = Role::findOrFail($id);

        $role->permissions()->detach();

        return redirect()->route('roles.show', $role->id)->with('success', 'Operatie efectuata cu succes.');
    }
}

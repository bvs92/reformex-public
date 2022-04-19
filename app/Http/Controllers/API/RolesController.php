<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{

    public function getAllRoles()
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $roles = Role::all();

        $roles = $roles->map(function ($item) {
            $item->users_count = $item->users->count();
            return $item;
        });

        $roles->makeHidden(['users']);

        return response()->json(['roles' => $roles, 'total' => $roles->count()]);
    }

    public function update(Request $request, $id)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $role = Role::find($id);

        if (!$role) {
            return response()->json(['errors' => 'Rolul nu exista.']);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|alpha',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_request = $validator->valid();

        $role->name = $valid_request['name'];

        if (!$role->save()) {
            return response()->json(['errors' => 'Am intampinat erori. Incercati mai tarziu.']);
        }

        return response()->json(['success' => 'Ok.']);

    }

    public function store(Request $request)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|alpha|unique:roles,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $valid_request = $validator->valid();

        if (!$role = Role::create(['name' => $valid_request['name']])) {
            return response()->json(['errors' => 'Am intampinat erori. Incercati mai tarziu.']);
        }

        return response()->json(['success' => 'Ok.', 'role' => $role]);

    }
}

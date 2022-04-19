<?php

namespace App\Http\Controllers\API;

use App\Credit;
use App\Http\Controllers\Controller;
use App\Professional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class ProfessionalsController extends Controller
{
    public function activate(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['errors' => true]);
        }

        if ($user->isPro()) {
            return response()->json(['errors' => true]);
        }

        DB::beginTransaction();
        if (!Professional::create([
            'user_id' => auth()->user()->id,
        ])) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        // Creare profil Credit
        if (!auth()->user()->credit) {
            if (!Credit::create([
                'user_id' => auth()->user()->id,
                'amount' => 0,
            ])) {
                DB::rollBack();
                return response()->json(['errors' => true]);
            }
        }

        // Adauga rol de professional
        $role = Role::where('name', 'professional')->first();
        if (!auth()->user()->syncRoles($role)) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        DB::commit();
        return response()->json(['success' => true]);
    }
}

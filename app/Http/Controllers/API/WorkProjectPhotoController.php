<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\WorkProjectPhoto;
use Illuminate\Support\Facades\DB;

class WorkProjectPhotoController extends Controller
{
    public function destroy($id)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $project_photo = WorkProjectPhoto::find($id);
        if (!$project_photo) {
            return response()->json(['errors' => true]);
        }

        if (!$user->isAdmin()) {
            if ($project_photo->user_id != $user->id) {
                return response()->json(['errors' => true]);
            }
        }

        DB::beginTransaction();

        if (!$project_photo->delete()) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        DB::commit();

        return response()->json(['result' => true]);

    }

    public function destroyAsAdmin($id)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        }

        $project_photo = WorkProjectPhoto::find($id);
        if (!$project_photo) {
            return response()->json(['errors' => true]);
        }

        DB::beginTransaction();

        if (!$project_photo->delete()) {
            DB::rollBack();
            return response()->json(['errors' => true]);
        }

        DB::commit();

        return response()->json(['result' => true]);

    }
}

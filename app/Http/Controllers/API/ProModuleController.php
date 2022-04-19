<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class ProModuleController extends Controller
{

    public function getSelectedCategories()
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $my_categories = $user->getAssocCategories();
        $my_categories->makeHidden(['price', 'pivot']);

        return response()->json(['categories' => $my_categories, 'success' => true]);
    }

    public function getExistingLocation()
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $location = $user->professional ? $user->professional : null;

        return response()->json(['location' => $location, 'success' => true]);
    }
}

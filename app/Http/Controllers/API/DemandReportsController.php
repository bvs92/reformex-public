<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class DemandReportsController extends Controller
{
    public function destroy($id)
    {
        try {
            $user = auth()->user();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        // if (!$user->isAdmin()) {
        //     return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        // }

        $report = \App\DemandReport::find($id);

        if (!$report) {
            return response()->json(['errors' => 'Nu am gasit raportul invalida.']);
        }

        // check if is mine
        // if ($user->id !== $report->user_id || !$user->isAdmin()) {
        //     return response()->json(['errors' => 'Nu aveti permisiunea sa accesati aceasta sectiune.']);
        // }

        $this->authorize('update', $report);

        if (!$report->delete()) {
            return response()->json(['errors' => 'Am intampinat probleme. Incercati mai tarziu.']);
        }

        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Events\QuoteFileDeletedEvent;
use App\Events\TimelineClientMessageFileDeletedEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{

    public function checkFileExists($path, $name)
    {
        // $pathToFile = storage_path('app/public') . '/' . $path . '/' . $name;
        // if (file_exists($pathToFile)) {
        //     return response()->json(true);
        // } else {
        //     return response()->json(false);
        // }

        $pathToFile = 'uploads/' . $path . '/' . $name;
        if (Storage::disk('do_spaces')->exists($pathToFile)) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    public function get_quotes($file_id)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $files = \App\QuoteFile::where('quote_id', $file_id)->get();

        if (!$files) {
            return response()->json([
                'error' => 'Unable to continue.',
            ]);
        }

        return response()->json($files);

    }

    public function delete_file_quote($file_id)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        if (!$user->isPro()) {
            return response()->json([
                'error' => 'Unable to continue.',
            ]);
        }

        $file = \App\QuoteFile::find($file_id);

        if (!$file) {
            return response()->json([
                'error' => 'Unable to continue.',
            ]);
        }

        $quote = $file->quote;
        if (!$user->can('update', $quote)) {
            return response()->json(['error' => 'Error getting the data specified.']);
        }

        // return response()->json([
        //     $user->isPro()
        // ]);

        if (!$file->delete()) {
            return response()->json(['error' => 'Unable to delete. Try again later.']);
        }

        // emit event to get real time
        // event(new TimelineQuoteFileDeletedEvent($quote->timeline, $file->name));
        event(new QuoteFileDeletedEvent($quote->timeline, $quote));

        return response()->json(['result' => 'ok']);
    }

    public function delete_file_client_message($file_id)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $file = \App\ClientMessageFile::find($file_id);

        if (!$file) {
            return response()->json([
                'error' => 'Unable to continue.',
            ]);
        }

        $client_message = $file->clientMessage;
        if (!$user->can('update', $client_message)) {
            return response()->json(['error' => 'Error getting the data specified.']);
        }

        // return response()->json([
        //     $user->isPro()
        // ]);

        if (!$file->delete()) {
            return response()->json(['error' => 'Unable to delete. Try again later.']);
        }

        // emit event to get real time
        event(new TimelineClientMessageFileDeletedEvent($client_message->timeline, $client_message));

        return response()->json(['result' => 'ok']);
    }

    public function delete_file_demand_admin($id)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        // return response()->json([
        //     'hehe' => 'Suntem aici.',
        //     'id' => $id,
        // ]);

        if (!$user->isAdmin()) {
            return response()->json([
                'errors' => 'Nu aveti aceasta permisiune.',
            ]);
        }

        $file = \App\DemandFile::find($id);

        if (!$file) {
            return response()->json([
                'errors' => 'Fisierul nu exista.',
            ]);
        }

        if (!$file->delete()) {
            return response()->json(['errors' => 'Am intampinat erori. Incercati mai tarziu.']);
        }

        return response()->json(['result' => 'ok']);
    }

    public function delete_attachment_demand_admin($id)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        // return response()->json([
        //     'hehe' => 'Suntem aici.',
        //     'id' => $id,
        // ]);

        if (!$user->isAdmin()) {
            return response()->json([
                'errors' => 'Nu aveti aceasta permisiune.',
            ]);
        }

        $file = \App\DemandAttachment::find($id);

        if (!$file) {
            return response()->json([
                'errors' => 'Fisierul nu exista.',
            ]);
        }

        $pathToFile = 'uploads/demands/' . $file->name;
        if (Storage::disk('do_spaces')->exists($pathToFile)) {
            Storage::disk('do_spaces')->delete($pathToFile);
        }

        if (!$file->delete()) {
            return response()->json(['errors' => 'Am intampinat erori. Incercati mai tarziu.']);
        }

        return response()->json(['result' => 'ok']);
    }

    public function download($type, $file_name)
    {
        // $filepath = public_path('storage/' . $type . '/' . $file_name);
        // $filepath = storage_path('app/public') . '/' . $type . '/' . $file_name;

        // return response()->json($filepath);

        $pathToFile = 'uploads/' . $type . '/' . $file_name;
        if (Storage::disk('do_spaces')->exists($pathToFile)) {

            // $mime_type = mime_content_type($filepath);

            // $headers = [
            //     'Content-Type' => $mime_type,
            //     'Content-Disposition' => 'inline; filename="' . $file_name . '"',
            // ];

            return Storage::disk('do_spaces')->download('uploads/' . $type . '/' . $file_name, $file_name);
        }

        // if (file_exists($filepath)) {
        //     $mime_type = mime_content_type($filepath);

        //     $headers = [
        //         'Content-Type' => $mime_type,
        //         'Content-Disposition' => 'inline; filename="' . $file_name . '"',

        //     ];

        //     return response()->download($filepath, $file_name, $headers);

        // }

    }
}

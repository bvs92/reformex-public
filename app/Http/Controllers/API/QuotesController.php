<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\QuoteFileDeletedEvent;
use App\Events\TimelineMessageDeletedProEvent;

class QuotesController extends Controller
{
    

    public function get($id)
    {
        try {
            $user = auth()->userOrFail();
        } catch(\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e){
            return response()->json(['error' => $e->getMessage()]);
        }

        $quote = \App\Quote::find($id);

        // $quote = $quote->map(function($item){
        //     $item->files = $item->files;
        //     return $item;
        // });
        $quote->files = $quote->files;

        return response()->json($quote);
    }

    public function destroy($id)
    {
        try {
            $user = auth()->userOrFail();
        } catch(\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e){
            return response()->json(['error' => $e->getMessage()]);
        }

        $quote = \App\Quote::where('id', $id)->first();

        $timeline = $quote->timeline;


        if(!$user->can('update', $quote)){
            return response()->json(['error' => 'Error getting the data specified.']);
        }

        if(!$quote){
            return response()->json([
                'error' => 'Unable to continue.'
            ]);
        }


        // Creeaza colectie cu fisierele din ticket.
        if($quote->files && $quote->files->count() > 0){
            
            foreach($quote->files as $theFile){
                // $files->push($theFile);  
                // $pathToFile = public_path() . '/storage\/quotes\/' . $theFile->name;
                // if(file_exists($pathToFile)){
                //     unlink($pathToFile);
                // }

                $theFile->delete();
            }
        }

        // event(new QuoteFileDeletedEvent($timeline, $quote));
        $result = $quote->delete();

        event(new TimelineMessageDeletedProEvent($timeline));

        return response()->json($result);
    }
}

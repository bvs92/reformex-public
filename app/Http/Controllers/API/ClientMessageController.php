<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\TimelineMessageDeletedClientEvent;

class ClientMessageController extends Controller
{
    public function destroy($id)
    {

        try {
            $user = auth()->userOrFail();
        } catch(\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e){
            return response()->json(['error' => $e->getMessage()]);
        }

        $client_message = \App\ClientMessage::where('id', $id)->first();

        $timeline = $client_message->timeline;


        if(!$user->can('update', $client_message)){
            return response()->json(['error' => 'Error getting the data specified.']);
        }

        if(!$client_message){
            return response()->json([
                'error' => 'Unable to continue.'
            ]);
        }


        // Creeaza colectie cu fisierele din ticket.
        if($client_message->files && $client_message->files->count() > 0){
            
            foreach($client_message->files as $theFile){
                // $files->push($theFile);  
                // $pathToFile = public_path() . '/storage\/client_messages\/' . $theFile->name;
                // if(file_exists($pathToFile)){
                //     unlink($pathToFile);
                // }

                $theFile->delete();
            }
        }

        event(new TimelineMessageDeletedClientEvent($timeline));

        $result = $client_message->delete();


        return response()->json($result);
    }
}

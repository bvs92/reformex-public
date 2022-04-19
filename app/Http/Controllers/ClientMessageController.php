<?php

namespace App\Http\Controllers;

use App\Demand;
use App\Timeline;
use App\ClientMessage;
use App\ClientMessageFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TimelineMessageNotification;

class ClientMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }


    public function storeMessage(Request $request, $timeline_id)
    {
        // $demand = Demand::findOrFail($demand_id);
        $timeline = Timeline::findOrFail($timeline_id);

        // check if has rights on current timeline
        $this->authorize('update_client', $timeline);

        // if(!$timeline->demand->belongsToMe()){
        //     return redirect()->back();
        // }

        $validated = $request->validate([
            'message'   => 'required|min:2',
            'files_client'  => 'nullable|sometimes|mimetypes:image/jpeg,image/png,image/webp,application/pdf,text/csv,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain'
        ]);

        $validated['demand_id'] = $timeline->demand->id;
        $validated['timeline_id'] = $timeline->id;
        $validated['user_id'] = auth()->user()->id;

        if(!$client_message = ClientMessage::create($validated)){
            return redirect()->back()->with('error', 'Eroare. Incercati mai tarziu.');
        }


        if($request->hasFile('files_client')){
          
            $file = $request->file('files_client');
            $infos = $this->uploadFile($file);
            
            $clientMessageFile = new ClientMessageFile();
            $clientMessageFile->client_message_id = $client_message->id;
            $clientMessageFile->user_id = auth()->user()->id;
            $clientMessageFile->name = $infos['file_name'];
            $clientMessageFile->extension = $infos['ext'];
            // $clientMessageFile->path = null;
            $clientMessageFile->mime_type = $infos['mime_type'];

            $clientMessageFile->save();

        }

        return redirect()->route('timeline.show.client', $timeline->id)->with('success', 'Mesajul a fost trimis.');
    }


    public function storeMessageMany(Request $request, $timeline_id)
    {
        // $demand = Demand::findOrFail($demand_id);
        $timeline = Timeline::findOrFail($timeline_id);

        // check if has rights on current timeline
        $this->authorize('update_client', $timeline);

        // if(!$timeline->demand->belongsToMe()){
        //     return redirect()->back();
        // }

        $validated = $request->validate([
            'message'   => 'required|min:2',
            'files_client[]'  => 'nullable|sometimes|mimetypes:image/jpeg,image/png,image/webp,application/pdf,text/csv,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain'
        ]);

        $validated['demand_id'] = $timeline->demand->id;
        $validated['timeline_id'] = $timeline->id;
        $validated['user_id'] = auth()->user()->id;

        if(!$client_message = ClientMessage::create($validated)){
            return redirect()->back()->with('error', 'Eroare. Incercati mai tarziu.');
        }


        if($request->hasFile('files_client')){
            $all_files = $request->file('files_client');

            foreach($all_files as $one_file){
                $infos = $this->uploadFile($one_file);

                $clientMessageFile = new ClientMessageFile();
                $clientMessageFile->client_message_id = $client_message->id;
                $clientMessageFile->user_id = auth()->user()->id;
                $clientMessageFile->name = $infos['file_name'];
                $clientMessageFile->extension = $infos['ext'];
                // $clientMessageFile->path = null;
                $clientMessageFile->mime_type = $infos['mime_type'];

                $clientMessageFile->save();
            }
            
        }


        // Notify pro
        $the_response_message = $validated['message'];
        Notification::send($timeline->professional->user, new TimelineMessageNotification($timeline, auth()->user(), $the_response_message, 'pro'));

        return redirect()->route('timeline.show.client', $timeline->id)->with('success', 'Mesajul a fost trimis.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $clientMessage = ClientMessage::findOrFail($id);

        $this->authorize('update', $clientMessage);

        $timeline = Timeline::where('demand_id', $clientMessage->demand_id)->first();
        // Creeaza colectie cu fisierele din ticket.
        if($clientMessage->files && $clientMessage->files->count() > 0){
            
            foreach($clientMessage->files as $theFile){
                // $files->push($theFile);  
                // $pathToFile = public_path() . '/storage\/quotes\/' . $theFile->name;
                // if(file_exists($pathToFile)){
                //     unlink($pathToFile);
                // }

                $theFile->delete();
            }
        }

        if(!$clientMessage->delete()){
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        return redirect()->route('timeline.show.client', $timeline->id)->with('success', 'Operatiune realizata cu succes.');
    }



    protected function uploadFile($file)
    {

        // Get image original extension
        $ext = $file->getClientOriginalExtension();
        $mimeType = $file->getClientMimeType();
        $random = substr(md5(mt_rand()), 0, 5);

        // dd($mimeType);
        
        // Compose the name
        $file_name = 'client-mesage-file-' . time() . "-" . $random . "-" . auth()->user()->id . '.' . $ext;

        $infos = [
            'ext' => $ext,
            'mime_type' => $mimeType,
            'file_name' => $file_name
        ];
        

        // Save file
        // Image::make($file)
        // ->resize(300, 300, function ($constraint) {
        //     $constraint->aspectRatio();
        // })
        // ->save(public_path('images/avatars/' . $file_name));

        // Register file in Files table.

        Storage::disk('public')->putFileAs('client_messages', $file, $file_name);

        return $infos;
    }


}

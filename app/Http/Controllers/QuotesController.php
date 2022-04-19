<?php

namespace App\Http\Controllers;

use App\Quote;
use App\Demand;
use App\Timeline;
use App\QuoteFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TimelineMessageNotification;

class QuotesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');    
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function personalQuotes()
    {
        $quotes = auth()->user()->professional->quotes;
        $activity_spent = auth()->user()->activities->sum('amount');
        return view('volgh.quotes.index', compact(['quotes', 'activity_spent']));
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
    public function store(Request $request, Demand $demand)
    {

        if(!auth()->user()->isPro())
            return redirect()->back();

        // check if has rights on current timeline
        $this->authorize('update_pro', $timeline);

        // dd("Here");
        $validated = $request->validate([
            'price'   => 'nullable|numeric',
            'message' => 'required|min:5',
            'files_quote'  => 'nullable|sometimes|mimetypes:image/jpeg,image/png,image/webp,application/pdf,text/csv,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain'
        ]);

        $validated['demand_id'] = $demand->id;
        $validated['professional_id'] = auth()->user()->professional->id;
        $validated['user_id'] = auth()->user()->id;

        if(!$quote = Quote::create($validated)){
            return redirect()->back()->with('error', 'Ceva nu a mers bine. Va rugam incercati mai tarziu.');
        }

        if($request->hasFile('files_quote')){
          
            $file = $request->file('files_quote');
            $infos = $this->uploadFile($file);
            
            $quoteFile = new QuoteFile();
            $quoteFile->quote_id = $quote->id;
            $quoteFile->user_id = auth()->user()->id;
            $quoteFile->name = $infos['file_name'];
            $quoteFile->extension = $infos['ext'];
            // $quoteFile->path = null;
            $quoteFile->mime_type = $infos['mime_type'];

            $quoteFile->save();

        }

        return redirect()->route('demands.show', $demand)->with('success', 'Cotatie de pret trimisa cu success.');
    }



    public function storeMany(Request $request, Demand $demand, Timeline $timeline)
    {
        // dd($request->file('files_quote'));
        
        if(!auth()->user()->isPro())
            return redirect()->back();

            // check if has rights on current timeline
        $this->authorize('update_pro', $timeline);

        $validated = $request->validate([
            'price'   => 'nullable|numeric',
            'message' => 'required|min:5',
            'files_quote[]'  => 'nullable|sometimes|mimetypes:image/jpeg,image/png,image/webp,application/pdf,text/csv,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain'
        ]);

        $validated['demand_id'] = $demand->id;
        $validated['professional_id'] = auth()->user()->professional->id;
        $validated['user_id'] = auth()->user()->id;
        $validated['timeline_id'] = $timeline->id;

        if(!$quote = Quote::create($validated)){
            return redirect()->back()->with('error', 'Ceva nu a mers bine. Va rugam incercati mai tarziu.');
        }


        if($request->hasFile('files_quote')){
            $all_files = $request->file('files_quote');

            foreach($all_files as $one_file){
                $infos = $this->uploadFile($one_file);
            
                $quoteFile = new QuoteFile();
                $quoteFile->quote_id = $quote->id;
                $quoteFile->user_id = auth()->user()->id;
                $quoteFile->name = $infos['file_name'];
                $quoteFile->extension = $infos['ext'];
                // $quoteFile->path = null;
                $quoteFile->mime_type = $infos['mime_type'];

                $quoteFile->save();
            }
            
        }

        // if($request->hasFile('files_quote')){
          
        //     $file = $request->file('files_quote');
        //     $infos = $this->uploadFile($file);
            
        //     $quoteFile = new QuoteFile();
        //     $quoteFile->quote_id = $quote->id;
        //     $quoteFile->user_id = auth()->user()->id;
        //     $quoteFile->name = $infos['file_name'];
        //     $quoteFile->extension = $infos['ext'];
        //     // $quoteFile->path = null;
        //     $quoteFile->mime_type = $infos['mime_type'];

        //     $quoteFile->save();

        // }


        // Notify client
        $the_response_message = $validated['message'];
        Notification::send($demand->user, new TimelineMessageNotification($timeline, auth()->user(), $the_response_message, 'client'));

        return redirect()->route('timeline.show.pro', $timeline->id)->with('success', 'Mesajul a fost trimis cu success.');
    }




    public function storeMessageMany(Request $request, Demand $demand, Timeline $timeline)
    {

        if(!auth()->user()->isPro())
            return redirect()->back();

        // check if has rights on current timeline
        $this->authorize('update_pro', $timeline);

        // $this->authorize('create');

        $validated = $request->validate([
            'message_one' => 'required|min:5',
            'files_message[]'  => 'nullable|sometimes|mimetypes:image/jpeg,image/png,image/webp,application/pdf,text/csv,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain'
        ]);

        $validated['demand_id'] = $demand->id;
        $validated['price'] = NULL;
        $validated['professional_id'] = auth()->user()->professional->id;
        $validated['message'] = $validated['message_one'];
        $validated['timeline_id'] = $timeline->id;

        if(!$quote = Quote::create($validated)){
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }


        if($request->hasFile('files_message')){
            $all_files = $request->file('files_message');

            foreach($all_files as $one_file){
                $infos = $this->uploadFile($one_file);
            
                $quoteFile = new QuoteFile();
                $quoteFile->quote_id = $quote->id;
                $quoteFile->user_id = auth()->user()->id;
                $quoteFile->name = $infos['file_name'];
                $quoteFile->extension = $infos['ext'];
                // $quoteFile->path = null;
                $quoteFile->mime_type = $infos['mime_type'];

                $quoteFile->save();
            }
            
        }

        // if($request->hasFile('files_quote')){
          
        //     $file = $request->file('files_quote');
        //     $infos = $this->uploadFile($file);
            
        //     $quoteFile = new QuoteFile();
        //     $quoteFile->quote_id = $quote->id;
        //     $quoteFile->user_id = auth()->user()->id;
        //     $quoteFile->name = $infos['file_name'];
        //     $quoteFile->extension = $infos['ext'];
        //     // $quoteFile->path = null;
        //     $quoteFile->mime_type = $infos['mime_type'];

        //     $quoteFile->save();

        // }

        // Notify client
        $the_response_message = $validated['message'];
        Notification::send($demand->user, new TimelineMessageNotification($timeline, auth()->user(), $the_response_message, 'client'));

        // return redirect()->route('demands.show', $demand)->with('success', 'Cotatie de pret trimisa cu success.');
        return redirect()->route('timeline.show.pro', $timeline->id)->with('success', 'Mesajul a fost trimis cu success.');
    }





    /**
     * Display the specified resource.
     *
     * @param  \App\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function show(Quote $quote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function edit(Quote $quote)
    {
        return view('volgh.quotes.edit', compact('quote'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quote $quote)
    {

        $this->authorize('update', $quote);

        // dd($quote);
        $validated = $request->validate([
            'price'     => 'nullable|numeric',
            'message'   => 'required|min:3'
        ]);

        if(!$quote->update($validated)){
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        return redirect()->route('demands.show', $quote->demand)->with('success', 'Operatiune realizata cu succes.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quote $quote)
    {
        $this->authorize('update', $quote);
        
        $demand = $quote->demand;
        $timeline = Timeline::where('demand_id', $demand->id)->first();

        

        // $files = collect();
        
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

        if(!$quote->delete()){
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        return redirect()->route('timeline.show.pro', $timeline->id)->with('success', 'Operatiune realizata cu succes.');
    }




    protected function uploadFile($file)
    {

        // Get image original extension
        $ext = $file->getClientOriginalExtension();
        $mimeType = $file->getClientMimeType();
        $random = substr(md5(mt_rand()), 0, 5);

        // dd($mimeType);
        
        // Compose the name
        $file_name = 'quote-file-' . time() . "-" . $random . "-" . auth()->user()->id . '.' . $ext;

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

        Storage::disk('public')->putFileAs('quotes', $file, $file_name);

        return $infos;
    }

}

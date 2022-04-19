<?php

namespace App\Http\Controllers;

use App\Events\TicketEvent;
use App\Notifications\TicketNotification;
use App\Ticket;
use App\TicketFile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class TicketsInactiveController extends Controller
{
    // for inactive users
    public function getAll()
    {
        // get tickets based on roles

        // $tickets = auth()->user()->tickets()->orderBy('created_at', 'desc')->paginate(20);

        return view('volgh.tickets.index-inactive');
    }

    public function createNew()
    {
        return view('volgh.tickets.create-inactive');
    }

    public function storeNew(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|min:2',
            'message' => 'required|string|min:2',
            'file_ticket[]' => 'nullable|sometimes|mimetypes:image/jpeg,image/png,image/webp,application/pdf,text/csv,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain',
        ]);

        $validated['uuid'] = $this->generateUUID();

        $validated['user_id'] = auth()->user()->id;
        $validated['status'] = 0;
        $validated['department_id'] = 0;
        $validated['priority'] = 1;

        if (!$ticket = Ticket::create($validated)) {
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        if ($request->hasFile('file_ticket')) {

            $all_files = $request->file('file_ticket');

            foreach ($all_files as $one_file) {
                $infos = $this->uploadFile($one_file);

                $ticketFile = new TicketFile();
                $ticketFile->ticket_id = $ticket->id;
                $ticketFile->user_id = auth()->user()->id;
                $ticketFile->name = $infos['file_name'];
                $ticketFile->extension = $infos['ext'];
                $ticketFile->mime_type = $infos['mime_type'];

                $ticketFile->save();
            }
        }

        $to_users = User::role(['admin', 'editor', 'moderator'])->get();
        Notification::send($to_users, new TicketNotification($ticket, 'ticket_created'));
        event(new \App\Events\TicketEvent($ticket));

        return redirect()->route('tickets.get.all')->with('success', 'Tichetul a fost trimis cu succes.');
    }

    public function show($uuid)
    {
        $ticket = \App\Ticket::where('uuid', $uuid)->first();

        return view('volgh.tickets.show-inactive', compact('ticket'));
    }

    private function generateUUID()
    {
        // genereaza id
        $res = \Illuminate\Support\Str::uuid();
        // $res = rand(0, 99);
        $id = substr($res, 0, 8);

        // echo 'SUS: ' . $id . '<br/>';
        // verifica daca exista in db
        while (\App\Ticket::where('uuid', $id)->get()->count() > 0) {
            // regenereaza daca exista
            $res = \Illuminate\Support\Str::uuid();
            // $res = rand(0, 99);
            $id = substr($res, 0, 8);
            // echo $id . '<br/>';
        }

        // echo 'JOS: ' . $id . '<br/>';
        return $id;

        // => id este unic si poate fi atasat unei cereri.
    }

    protected function uploadFile($file)
    {

        // Get image original extension
        $ext = $file->getClientOriginalExtension();
        $mimeType = $file->getClientMimeType();
        $random = substr(md5(mt_rand()), 0, 5);

        // dd($mimeType);

        // Compose the name
        $file_name = 'file-' . time() . "-" . $random . '-' . auth()->user()->id . '.' . $ext;

        $infos = [
            'ext' => $ext,
            'mime_type' => $mimeType,
            'file_name' => $file_name,
        ];

        // Save file
        // Image::make($file)
        // ->resize(300, 300, function ($constraint) {
        //     $constraint->aspectRatio();
        // })
        // ->save(public_path('images/avatars/' . $file_name));

        // Register file in Files table.

        Storage::disk('public')->putFileAs('tickets', $file, $file_name);

        return $infos;
    }

    // end for inactive users
}

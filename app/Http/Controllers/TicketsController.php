<?php

namespace App\Http\Controllers;

use App\Events\ModeratorJoinTicketEvent;
use App\Events\TicketDeletedEvent;
use App\Events\TicketStatusChangedEvent;
use App\Notifications\TicketChatActionNotification;
use App\Notifications\TicketMessageNotification;
use App\Notifications\TicketNotification;
use App\ResponseFile;
use App\ResponseTicket;
use App\Ticket;
use App\TicketFile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class TicketsController extends Controller
{

    public function __construct()
    {
        // $this->middleware('role:admin,editor,moderator')->only(['destroy', 'destroyUUID']);
    }

    function list() {
        return view('volgh.tickets.index-vue');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get tickets based on roles

        if (auth()->user()->hasRole('admin|editor|moderator')) {
            $tickets = Ticket::paginate(20);
        } else {
            $tickets = auth()->user()->tickets()->paginate(20);
        }

        return view('volgh.tickets.index', compact([
            'tickets',
        ]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('volgh.tickets.create');
    }

    public function createVue()
    {
        return view('volgh.tickets.create-vue');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|min:2',
            'priority' => 'required|numeric|min:0|max:3',
            'message' => 'required|min:2',
            'file_ticket' => 'nullable|sometimes|mimetypes:image/jpeg,image/png,image/webp,application/pdf,text/csv,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain',
            'department' => 'numeric|between:0,2',
        ]);

        $validated['uuid'] = $this->generateUUID();

        $validated['user_id'] = auth()->user()->id;
        $validated['status'] = 0;
        $validated['department_id'] = $validated['department'];

        if (!$ticket = Ticket::create($validated)) {
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        if ($request->hasFile('file_ticket')) {

            $file = $request->file('file_ticket');
            $infos = $this->uploadFile($file);

            $ticketFile = new TicketFile();
            $ticketFile->ticket_id = $ticket->id;
            $ticketFile->user_id = auth()->user()->id;
            $ticketFile->name = $infos['file_name'];
            $ticketFile->extension = $infos['ext'];
            $ticketFile->mime_type = $infos['mime_type'];

            $ticketFile->save();

        }

        $to_users = User::role('admin|editor|moderator')->get();
        Notification::send($to_users, new TicketNotification($ticket, 'ticket_created'));

        event(new \App\Events\TicketEvent($ticket));

        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Tichetul a fost trimis cu succes.');
    }

    public function storeMany(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|min:2',
            'priority' => 'required|numeric|min:0|max:3',
            'message' => 'required|min:2',
            'file_ticket[]' => 'nullable|sometimes|mimetypes:image/jpeg,image/png,image/webp,application/pdf,text/csv,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain',
            'department' => 'numeric|between:0,2',
        ]);

        $validated['uuid'] = $this->generateUUID();

        $validated['user_id'] = auth()->user()->id;
        $validated['status'] = 0;
        $validated['department_id'] = $validated['department'];

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

        return redirect()->route('tickets.show.vue.uuid', $ticket->uuid)->with('success', 'Tichetul a fost trimis cu succes.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);

        $this->authorize('update', $ticket);

        $responses = $ticket->responses;
        return view('volgh.tickets.show', compact([
            'ticket',
            'responses',
        ]));
    }

    public function showUUID($uuid)
    {
        $ticket = Ticket::where('uuid', $uuid)->first();

        $this->authorize('update', $ticket);

        $responses = $ticket->responses;

        return view('volgh.tickets.show', compact([
            'ticket',
            'responses',
        ]));
    }

    public function showVue($uuid)
    {
        $ticket = Ticket::where('uuid', $uuid)->firstOrFail();

        $resolvers = $ticket->resolvers()->pluck('user_id');
        $moderators = \App\User::role(['admin'])->where('id', '!=', auth()->user()->id)->get();
        $disponible_moderators = $moderators->whereNotIn('id', $resolvers)->all();
        $ticket->files;

        $this->authorize('update', $ticket);

        $ticket->user->makeHidden(['email_verified_at', 'password', 'remember_token', 'stripe_id', 'card_brand', 'card_last_four']);

        // dd($ticket->user);

        // $responses = $ticket->responses;
        return view('volgh.tickets.show-vue', compact([
            'ticket',
            'disponible_moderators',
        ]));
    }

    public function resolve($ticket_id)
    {
        $ticket = Ticket::findOrFail($ticket_id);

        $user = auth()->user();
        if (!$user->hasRole('admin|editor|moderator')) {
            return redirect()->back()->with('error', 'Nu aveti permisiunea de a participa la acest tichet.');
        }

        if ($ticket->resolvers()->where('user_id', $user->id)->first()) {
            return redirect()->back()->with('error', 'Aveti deja permisiunea de a participa la acest tichet.');
        }

        // $newResolver = \App\TicketResolver();
        // $newResolver->user_id =

        // add resolver to ticket
        if (!$ticket->resolvers()->create([
            'user_id' => $user->id,
        ])) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        // send event to event owner?
        // broadcast(new ModeratorJoinTicketEvent($ticket->id))->toOthers();

        return redirect()->route('tickets.show.vue.uuid', $ticket->uuid)->with('success', 'Felicitari! De acum puteti rezolva acest tichet.');
    }

    public function delegate($ticket_id, $user_id)
    {

        $ticket = Ticket::findOrFail($ticket_id);
        $user = User::findOrFail($user_id);

        if (!auth()->user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'Nu aveti permisiunea de a participa la acest tichet.');
        }

        if (!$user->hasRole('admin|editor|moderator')) {
            return redirect()->back()->with('error', 'Utilizatorul nu are drepturi pentru aceasta actiune.');
        }

        if ($ticket->resolvers()->where('user_id', $user->id)->first()) {
            return redirect()->back()->with('error', 'Aveti deja permisiunea de a participa la acest tichet.');
        }

        // $newResolver = \App\TicketResolver();
        // $newResolver->user_id =

        // add resolver to ticket
        if (!$ticket->resolvers()->create([
            'user_id' => $user->id,
        ])) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        // send event to event owner?
        broadcast(new ModeratorJoinTicketEvent($ticket->id))->toOthers();

        // send notification to Moderator that he wa added to the Ticket Chat

        Notification::send($user, new TicketChatActionNotification($ticket, $user, 'delegate'));
        event(new \App\Events\TicketDelegateEvent($ticket, $user, auth()->user()));

        // save action
        // \App\TicketAction::create([
        //     'ticket_id' => $ticket->id,
        //     'user_id' => $user->id,
        //     'sender_id' => auth()->user()->id,
        //     'type' => 'joins',
        // ]);

        return redirect()->route('tickets.show.vue.uuid', $ticket->uuid)->with('success', 'Felicitari! Ati delegat acest tichet.');
    }

    public function subscribing($uuid)
    {
        $ticket = Ticket::where('uuid', $uuid)->firstOrFail();

        if (!auth()->user()->hasRole('admin|editor|moderator')) {
            return redirect()->back()->with('error', 'Nu aveti drepturi sa modificati aceasta proprietate.');
        }

        $user = auth()->user();

        $resolver = $ticket->resolvers()->where('user_id', $user->id)->first();

        if ($resolver->count() < 1) {
            return redirect()->back();
        }

        $resolver->subscribed = !$resolver->subscribed;
        if (!$resolver->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        return redirect()->route('tickets.show.vue.uuid', $ticket->uuid)->with('success', 'Actiune executata cu succes.');
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
        $ticket = Ticket::findOrFail($id);

        $files = collect();

        // Creeaza colectie cu fisierele din ticket.
        if ($ticket->responses) {
            if ($ticket->responses->count() > 0) {
                foreach ($ticket->responses as $response) {
                    if ($response->responseFiles && $response->responseFiles->count() > 0) {
                        foreach ($response->responseFiles as $file) {
                            $files->push($file);
                        }
                    }

                }
            }

            // Itereaza colectie si elimina fisiere de pe disk
            if ($files->count() > 0) {
                foreach ($files as $file) {
                    // dd(public_path() . "/storage\/tickets\/" . $file->name);
                    $pathToFile = public_path() . '/storage\/tickets\/' . $file->name;
                    if (file_exists($pathToFile)) {
                        unlink($pathToFile);
                    }
                }
            }
            $ticket->responses()->delete();
        }

        // dd($files);

        if ($ticket->files && $ticket->files->count() > 0) {
            foreach ($ticket->files as $file) {
                $pathToFile = public_path() . '/storage\/tickets\/' . $file->name;
                if (file_exists($pathToFile)) {
                    unlink($pathToFile);
                }
            }
        }

        if (auth()->user()->roles) {
            if (auth()->user()->hasRole('admin')) {
                $owner = $ticket->user;
                Notification::send($owner, new TicketNotification($ticket, 'ticket_deleted'));
            }
        }

        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Tichetul a fost eliminat.');
    }

    // only admin
    public function destroyUUID($uuid)
    {
        $ticket = Ticket::where('uuid', $uuid)->firstOrFail();

        if (!$ticket) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        if (!auth()->user()->hasRole('admin|editor|moderator')) {
            return redirect()->back()->with('error', 'Nu aveti drepturi sa eliminati acest articol.');
        }

        $owner = $ticket->user;
        Notification::send($owner, new TicketNotification($ticket, 'ticket_deleted'));
        // send event for real time
        $ticket_id = $ticket->id;
        $ticket_uuid = $ticket->uuid;
        broadcast(new TicketDeletedEvent($ticket_id, $ticket_uuid))->toOthers();

        $files = collect();

        // Creeaza colectie cu fisierele din ticket.
        if ($ticket->responses) {
            if ($ticket->responses->count() > 0) {
                foreach ($ticket->responses as $response) {
                    if ($response->responseFiles && $response->responseFiles->count() > 0) {
                        foreach ($response->responseFiles as $file) {
                            $files->push($file);
                        }
                    }

                }
            }

            // Itereaza colectie si elimina fisiere de pe disk
            if ($files->count() > 0) {
                foreach ($files as $file) {
                    // dd(public_path() . "/storage\/tickets\/" . $file->name);
                    $pathToFile = public_path() . '/storage\/tickets\/' . $file->name;
                    if (file_exists($pathToFile)) {
                        unlink($pathToFile);
                    }
                }
            }
            $ticket->responses()->delete();
        }

        // dd($files);

        if ($ticket->files && $ticket->files->count() > 0) {
            foreach ($ticket->files as $file) {
                $pathToFile = public_path() . '/storage\/tickets\/' . $file->name;
                if (file_exists($pathToFile)) {
                    unlink($pathToFile);
                }
            }

            $ticket->files()->delete();
        }

        if ($ticket->resolvers) {
            $ticket->resolvers()->delete();
        }

        if ($ticket->actions) {
            $ticket->actions()->delete();
        }

        if (!$ticket->delete()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }
        return redirect()->route('tickets.list.all')->with('success', 'Tichetul a fost eliminat.');

    }

    public function changeStatus($id)
    {
        $ticket = Ticket::findOrFail($id);

        // Verifica daca utilizator are drepturi (admin, moderator ?)

        $this->authorize('update', $ticket);

        if ($ticket->status == '0') {
            $ticket->status = '1';
        } else {
            $ticket->status = '0';
        }

        $ticket->save();

        // notify Owner when ticket status is changed.
        if (auth()->user()->hasRole('admin|editor|moderator')) {
            $owner = $ticket->user;
            Notification::send($owner, new TicketNotification($ticket, 'ticket_status_changed'));

        }

        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Actiune executata cu succes.');
    }

    public function changeStatusUUID($uuid)
    {
        $ticket = Ticket::where('uuid', $uuid)->first();

        // Verifica daca utilizator are drepturi (admin, moderator ?)

        $this->authorize('update', $ticket);

        if ($ticket->status == '0') {
            $ticket->status = '1';
        } else {
            $ticket->status = '0';
        }

        $ticket->save();

        // notify Owner when ticket status is changed.
        if (auth()->user()->hasRole('admin|editor|moderator')) {
            $owner = $ticket->user;
            Notification::send($owner, new TicketNotification($ticket, 'ticket_status_changed'));
        }
        broadcast(new TicketStatusChangedEvent($ticket))->toOthers();

        return redirect()->route('tickets.show.vue.uuid', $ticket->uuid)->with('success', 'Actiune executata cu succes.');
    }

    public function respond(Request $request, $id)
    {
        if (!$ticket = Ticket::findOrFail($id)) {
            return redirect()->back();
        }

        $this->authorize('update', $ticket);

        $validated = $request->validate([
            'message' => 'required|min:2',
            'file_response' => 'sometimes|file|mimetypes:image/jpeg,image/png,image/webp,application/pdf,text/csv,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain',
        ]);

        $validated['ticket_id'] = $ticket->id;
        $validated['user_id'] = auth()->user()->id;

        // dd($validated);

        if (!$responseTicket = ResponseTicket::create($validated)) {
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        if ($request->hasFile('file_response')) {

            $file = $request->file('file_response');
            $infos = $this->uploadFile($file);

            $responseFile = new ResponseFile();
            $responseFile->response_ticket_id = $responseTicket->id;
            $responseFile->name = $infos['file_name'];
            $responseFile->extension = $infos['ext'];
            $responseFile->mime_type = $infos['mime_type'];

            $responseFile->save();

        }

        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Mesajul a fost trimis cu succes.');
    }

    public function respondUUID(Request $request, $uuid)
    {
        if (!$ticket = Ticket::where('uuid', $uuid)->first()) {
            return redirect()->back();
        }

        $this->authorize('update', $ticket);

        $validated = $request->validate([
            'message' => 'required|min:2',
            'file_response' => 'sometimes|file|mimetypes:image/jpeg,image/png,image/webp,application/pdf,text/csv,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain',
        ]);

        $validated['ticket_id'] = $ticket->id;
        $validated['user_id'] = auth()->user()->id;

        // dd($validated);

        if (!$responseTicket = ResponseTicket::create($validated)) {
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        if ($request->hasFile('file_response')) {

            $file = $request->file('file_response');
            $infos = $this->uploadFile($file);

            $responseFile = new ResponseFile();
            $responseFile->response_ticket_id = $responseTicket->id;
            $responseFile->name = $infos['file_name'];
            $responseFile->extension = $infos['ext'];
            $responseFile->mime_type = $infos['mime_type'];

            $responseFile->save();

        }

        return redirect()->route('tickets.show.uuid', $ticket->uuid)->with('success', 'Mesajul a fost trimis cu succes.');
    }

    public function respondMany(Request $request, $id)
    {

        // dd('aici');

        if (!$ticket = Ticket::findOrFail($id)) {
            return redirect()->back();
        }

        $this->authorize('update', $ticket);

        $validated = $request->validate([
            'message' => 'required|min:2',
            'file_response[]' => 'sometimes|file|mimetypes:image/jpeg,image/png,image/webp,application/pdf,text/csv,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain',
        ]);

        $validated['ticket_id'] = $ticket->id;
        $validated['user_id'] = auth()->user()->id;

        // dd($validated);

        if (!$responseTicket = ResponseTicket::create($validated)) {
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        if ($request->hasFile('file_response')) {

            $all_files = $request->file('file_response');

            foreach ($all_files as $one_file) {
                $infos = $this->uploadFile($one_file);

                $responseFile = new ResponseFile();
                $responseFile->response_ticket_id = $responseTicket->id;
                $responseFile->name = $infos['file_name'];
                $responseFile->extension = $infos['ext'];
                $responseFile->mime_type = $infos['mime_type'];

                $responseFile->save();
            }
        }

        if (auth()->user()->roles) {
            if (auth()->user()->hasRole('admin')) {
                $owner = $ticket->user;
                Notification::send($owner, new TicketMessageNotification($ticket, $responseTicket));
            }
        }

        // if is the owner of the ticket
        if (auth()->user()->id == $ticket->user_id) {
            $admin = auth()->user()->role('admin')->get();
            Notification::send($admin, new TicketMessageNotification($ticket, $responseTicket));
        }

        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Mesajul a fost trimis cu succes.');
    }

    public function respondManyUUID(Request $request, $uuid)
    {

        // dd('aici');

        if (!$ticket = Ticket::where('uuid', $uuid)->first()) {
            return redirect()->back();
        }

        $this->authorize('update', $ticket);

        $validated = $request->validate([
            'message' => 'required|min:2',
            'file_response[]' => 'sometimes|file|mimetypes:image/jpeg,image/png,image/webp,application/pdf,text/csv,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain',
        ]);

        $validated['ticket_id'] = $ticket->id;
        $validated['user_id'] = auth()->user()->id;

        // dd($validated);

        if (!$responseTicket = ResponseTicket::create($validated)) {
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        if ($request->hasFile('file_response')) {

            $all_files = $request->file('file_response');

            foreach ($all_files as $one_file) {
                $infos = $this->uploadFile($one_file);

                $responseFile = new ResponseFile();
                $responseFile->response_ticket_id = $responseTicket->id;
                $responseFile->name = $infos['file_name'];
                $responseFile->extension = $infos['ext'];
                $responseFile->mime_type = $infos['mime_type'];

                $responseFile->save();
            }
        }

        if (auth()->user()->roles) {
            if (auth()->user()->hasRole('admin')) {
                $owner = $ticket->user;
                Notification::send($owner, new TicketMessageNotification($ticket, $responseTicket));
            }
        }

        // if is the owner of the ticket
        if (auth()->user()->id == $ticket->user_id) {
            $admin = auth()->user()->role('admin')->get();
            Notification::send($admin, new TicketMessageNotification($ticket, $responseTicket));
        }

        return redirect()->route('tickets.show.uuid', $ticket->uuid)->with('success', 'Mesajul a fost trimis cu succes.');
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

}

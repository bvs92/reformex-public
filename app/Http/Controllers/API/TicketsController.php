<?php

namespace App\Http\Controllers\API;

use App\Events\ModeratorJoinTicketEvent;
use App\Http\Controllers\Controller;
use App\Notifications\TicketChatActionNotification;
use App\Notifications\TicketNotification;
use App\Ticket;
use App\TicketFile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class TicketsController extends Controller
{

    public function initializeForAdmin(Request $request)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        if (!$user->isAdmin()) {
            return response()->json(['errors' => true]);
        }

        $type = $request->input('type');

        if ($type == 'opened') {
            $tickets = \App\Ticket::where('status', 0)->orderBy('created_at', 'desc')->get();
        } else if ($type == 'closed') {
            $tickets = \App\Ticket::where('status', 1)->orderBy('created_at', 'desc')->get();
        } else {
            $tickets = \App\Ticket::orderBy('created_at', 'desc')->get();
        }

        $tickets = $tickets->map(function ($item) {
            $item['email'] = $item->user->email;
            $item['total_resolvers'] = $item->resolvers ? $item->resolvers->count() : 0;
            $item->makeHidden(['user', 'resolvers']);
            return $item;
        });

        $department = $request->input('department');

        if ($department == 'general') {
            $tickets = $tickets->filter(function ($item, $key) {
                if ($item->department_id == 0) {
                    return $item;
                }
            });

        } else if ($department == 'commercial') {
            $tickets = $tickets->filter(function ($item, $key) {
                if ($item->department_id == 1) {
                    return $item;
                }
            });

        } else if ($department == 'technical') {
            $tickets = $tickets->filter(function ($item, $key) {
                if ($item->department_id == 2) {
                    return $item;
                }
            });

        }

        $tickets = $tickets->map(function ($item) {
            $item['total_unread'] = \App\ResponseTicketInformation::where('ticket_id', $item->id)->where('user_id', '=', auth()->user()->id)->whereNull('read_at')->count();
            return $item;
        });

        return response()->json(['tickets' => $tickets, 'total' => $tickets->count()]);
    }

    public function initializePersonal(Request $request)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $tickets = $user->tickets()->orderBy('created_at', 'desc')->get();
        $tickets = $tickets->map(function ($item) {
            $item['total_unread'] = \App\ResponseTicketInformation::where('ticket_id', $item->id)->where('user_id', '=', $item->user_id)->whereNull('read_at')->count();
            return $item;
        });

        return response()->json(['tickets' => $tickets, 'total' => $tickets->count(), 'user_status' => auth()->user()->status]);
    }

    public function getTicket($uuid)
    {

        try {
            $user = auth()->userOrFail();
            // $ticket = \App\Ticket::findOrFail($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $ticket = \App\Ticket::where('uuid', $uuid)->first();
        $new_ticket = $ticket->only(['id', 'uuid', 'user_id', 'created_at', 'updated_at', 'department_id', 'message', 'priority', 'status', 'subject']);
        // $new_ticket->user = $ticket->user->only(['first_name', 'last_name', 'email', 'status', 'username']);
        $the_user = $ticket->user->only(['first_name', 'last_name', 'email', 'status', 'username']);
        $the_user['complete_name'] = $ticket->user->getName();
        $the_user['is_pro'] = $ticket->user->isPro();
        $the_user['profile_photo'] = $ticket->user->getFullProfilePhoto();

        // Iterate through responses to get user data for each
        // $responses = $ticket->responses()->latest()->paginate(25);
        $responses = $ticket->responses->map(function ($item) {
            $item->user->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'profile', 'stripe_id']);
            $user_details = $item->user->only(['first_name', 'last_name', 'email', 'status', 'username']);

            $item->class_type = class_basename($item);
            $user_details['complete_name'] = $item->user->getName();
            $user_details['is_pro'] = $item->user->isPro();
            $user_details['profile_photo'] = $item->user->getFullProfilePhoto();
            $item->user_details = $user_details;
            $item->attached_files = $item->responseFiles;
            return $item;
        });

        $unread_messages = $ticket->responses()->where('user_id', '!=', $user->id)->where('read', 0)->count(); // unread messages that don't belongsto auth user.

        $conversations = $responses;

        if ($ticket->ticket_actions && $ticket->ticket_actions()->count() > 0) {
            $actions = $ticket->ticket_actions->map(function ($item) {
                $item->class_type = class_basename($item);
                $item->sender_name = $item->sender->getName();
                $item->user_name = $item->user->getName();
                return $item;
            });
            // all conversation
            $conversations = $responses->concat($actions);
        }

        // for chunks
        // $conversations = $conversations->sortByDesc('created_at')->take(25);
        // $conversations = $conversations->flatten();

        // return response()->json(['conversations' => $conversations]);

        if (!$user->can('update', $ticket)) {
            return response()->json(['error' => 'Error getting the data specified.']);
        }

        $resolvers = ($ticket->resolvers && $ticket->resolvers()->count() > 0) ? $ticket->resolvers : null;

        if ($resolvers) {
            $resolvers = $resolvers->map(function ($item) {
                $item->user->makeHidden(['email_verified_at', 'password', 'remember_token', 'stripe_id', 'card_brand', 'card_last_four']);
                $item->details = $item->user;
                $item->details['profile_photo'] = $item->user->getFullProfilePhoto();
                return $item;
            });
        }

        return response()->json([
            'ticket' => $new_ticket,
            'resolvers' => $resolvers,
            'responses' => $conversations,
            'user' => $the_user,
            'unread_messages' => $unread_messages,
        ]);

    }

    public function getPersonalTickets()
    {

        try {
            $user = auth()->userOrFail();
            // $ticket = \App\Ticket::findOrFail($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        if ($user->hasRole('admin|editor|moderator')) {
            // $tickets = \App\Ticket::all();
            $ids = $user->resolvers()->where('subscribed', true)->pluck('ticket_id');
            // preia doar tickets subscribed?
            $tickets = \App\Ticket::whereIn('id', $ids)->get();

            $tickets = $tickets->map(function ($item) use ($user) {
                // $item->unread_responses = $item->responses()->where('user_id', '!=', $user->id)->where('read', false)->count();
                $item->unread_responses = \App\ResponseTicketInformation::where('ticket_id', $item->id)->where('user_id', '=', $user->id)->where('read_at', null)->count();
                $item->last_response = $item->responses()->where('user_id', '!=', $user->id)->latest()->first();
                return $item;
            });

            return response()->json([
                'tickets' => $tickets,
            ]);
        }

        $tickets = $user->tickets;

        $tickets = $tickets->map(function ($item) use ($user) {
            // $item->unread_responses = $item->responses()->where('user_id', '!=', $user->id)->where('read', false)->count();
            $item->unread_responses = \App\ResponseTicketInformation::where('ticket_id', $item->id)->where('user_id', '=', $user->id)->where('read_at', null)->count(); // make this work, register ticket owner id in table when message send
            $item->last_response = $item->responses()->where('user_id', '!=', $user->id)->latest()->first();
            return $item;
        });

        // $tickets = $tickets->sortByDesc(function ($item, $key) {
        //     return $item->unread_responses;
        // });

        return response()->json([
            'tickets' => $tickets,
        ]);

    }

    public function addUserToTicket($ticket_id, $user_id)
    {
        $ticket = Ticket::findOrFail($ticket_id);
        $user = User::findOrFail($user_id);

        // $user = auth()->user();
        if (!$user->hasRole('admin|editor|moderator')) {
            return response()->json(['error' => 'Utilizatorul nu are permisiunea de a participa la acest tichet.']);
        }

        if ($ticket->resolvers()->where('user_id', $user->id)->first()) {
            return response()->json(['error' => 'Aveti deja permisiunea de a participa la acest tichet.']);
        }

        // $user = auth()->user();
        if (!auth()->user()->hasRole('admin|editor|moderator')) {
            return response()->json(['error' => 'Utilizatorul nu are permisiunea de a participa la acest tichet.']);
        }

        $existing_resolvers = $ticket->resolvers()->pluck('user_id')->flatten();
        if (!in_array(auth()->user()->id, $existing_resolvers->all())) {
            return response()->json(['error' => 'Nu aveti permisiunea de a adauga alti utilizatori la acest tichet.']);
        }

        // return response()->json(['error' => $existing_resolvers->all(), 'existing' => auth()->user()->id]);

        // $newResolver = \App\TicketResolver();
        // $newResolver->user_id =

        // add resolver to ticket
        if (!$ticket->resolvers()->create([
            'user_id' => $user->id,
        ])) {
            return response()->json(['error' => 'Am intampinat erori. Incercati mai tarziu.']);
        }

        // send event to event owner?
        broadcast(new ModeratorJoinTicketEvent($ticket->id))->toOthers();

        // send notification to Moderator that he wa added to the Ticket Chat
        if ($user->hasRole(['admin', 'moderator', 'editor'])) {
            Notification::send($user, new TicketChatActionNotification($ticket, $user, 'joins'));
            event(new \App\Events\TicketActionsEvent($ticket, $user));
        }

        // save action
        \App\TicketAction::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'sender_id' => auth()->user()->id,
            'type' => 'joins',
        ]);

        return response()->json(['success' => 'Actiune executata cu succes.']);
    }

    public function delegateTicketToUser($ticket_id, $user_id)
    {

        $ticket = Ticket::findOrFail($ticket_id);
        $user = User::findOrFail($user_id);

        // $user = auth()->user();
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['error' => 'Nu aveti permisiunea de a participa la acest tichet.']);
        }

        if (!$user->hasRole('admin|editor|moderator')) {
            return response()->json(['error' => 'Utilizatorul nu are drepturi pentru aceasta actiune.']);
        }

        if ($ticket->resolvers()->where('user_id', $user->id)->first()) {
            return response()->json(['error' => 'Utilizatorul are deja permisiunea de a participa la acest tichet.']);
        }

        // $newResolver = \App\TicketResolver();
        // $newResolver->user_id =

        // add resolver to ticket
        if (!$ticket->resolvers()->create([
            'user_id' => $user->id,
        ])) {
            return response()->json(['error' => 'Am intampinat erori. Incercati mai tarziu.']);
        }

        // send event to event owner?
        broadcast(new ModeratorJoinTicketEvent($ticket->id))->toOthers();

        // send notification to Moderator that he wa added to the Ticket Chat
        if ($user->hasRole(['admin', 'moderator', 'editor'])) {
            Notification::send($user, new TicketChatActionNotification($ticket, $user, 'delegate'));
            event(new \App\Events\TicketDelegateEvent($ticket, $user, auth()->user()));
        }

        // save action
        // \App\TicketAction::create([
        //     'ticket_id' => $ticket->id,
        //     'user_id' => $user->id,
        //     'sender_id' => auth()->user()->id,
        //     'type' => 'joins',
        // ]);

        return response()->json(['success' => 'Actiune executata cu succes.']);
    }

    public function getResolvers($ticket_id)
    {
        $ticket = \App\Ticket::findOrFail($ticket_id);

        try {
            $resolvers = $ticket->resolvers;

            if ($resolvers->count() > 0) {
                // $resolvers = $resolvers->map(function ($item) {
                //     return $item->user;
                // });
                $resolvers = $resolvers->map(function ($item) {
                    $item->user->makeHidden(['email_verified_at', 'password', 'remember_token', 'stripe_id', 'card_brand', 'card_last_four']);
                    $item->details = $item->user;
                    $item->details['profile_photo'] = $item->user->getFullProfilePhoto();
                    return $item;
                });

                // $resolvers = $resolvers->each(function ($item) {
                //     $item['profile_photo'] = $item->getFullProfilePhoto();
                //     $item->makeHidden(['card_brand', 'card_last_four', 'email_verified_at', 'profile', 'stripe_id']);

                //     // return $item->user;
                // });
            }

            // $ticket = \App\Ticket::findOrFail($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        if (!$resolvers) {
            $resolvers = null;
        }

        return response()->json(['resolvers' => $resolvers]);
    }

    public function store(Request $request)
    {

        // return response()->json(['tickets' => $request->file('the_files')]);

        $validator = Validator::make($request->all(), [
            'subject' => 'required|min:2',
            'priority' => 'required|numeric',
            'message' => 'required|min:2',
            'the_files[]' => 'nullable|sometimes|mimetypes:image/jpeg,image/png,image/webp,application/pdf,text/csv,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain',
            'department' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $validated = $validator->valid();

        $validated['uuid'] = $this->generateUUID();

        $validated['user_id'] = auth()->user()->id;
        $validated['status'] = 0;

        $deprt = intval($validated['department']);
        if ($deprt >= 0 && $deprt <= 2) {
            $validated['department_id'] = $deprt;
        } else {
            $validated['department_id'] = 0;
        }

        $prty = intval($validated['priority']);
        if ($prty >= 0 && $prty <= 2) {
            $validated['priority'] = $prty;
        } else {
            $validated['priority'] = 3;
        }

        if (!$ticket = Ticket::create($validated)) {
            return response()->json(['errors' => true]);
        }

        if ($request->hasFile('the_files')) {

            $all_files = $request->file('the_files');

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

        // $to_users = User::role(['admin', 'editor', 'moderator'])->get();
        $to_users = User::role(['admin'])->get();
        Notification::send($to_users, new TicketNotification($ticket, 'ticket_created'));
        event(new \App\Events\TicketEvent($ticket));

        return response()->json(['success' => true]);
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

        // Storage::disk('public')->putFileAs('tickets', $file, $file_name);

        if (exif_imagetype($file)) {
            // your image is valid

            // Save file
            Image::make($file)
                ->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('storage/tickets/' . $file_name));

        } else {
            Storage::disk('public')->putFileAs('tickets', $file, $file_name);
        }

        return $infos;
    }

}

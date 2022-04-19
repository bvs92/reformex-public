<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Notifications\TicketMessageInactiveUserNotification;
use App\Notifications\TicketMessageNotification;
use App\Notifications\TicketMessageToResolverNotification;
use App\ResponseFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ResponseTicketController extends Controller
{

    public function getTicketResponses(Request $request, $uuid)
    {
        try {
            $user = auth()->userOrFail();
            // $ticket = \App\Ticket::findOrFail($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $ticket = \App\Ticket::where('uuid', $uuid)->first();

        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found.']);
        }

        if (!$user->can('update', $ticket)) {
            return response()->json(['error' => 'Error getting the data specified.']);
        }

        $total = $request->total;

        if ($total < 15) {
            $total = 15;
        }

        // $total_responses = $ticket->responses->count();

        $the_responses = $ticket->responses()->latest()->paginate($total);

        // Iterate through responses to get user data for each
        $responses = $the_responses->map(function ($item) {
            $user_details = $item->user->only(['first_name', 'last_name', 'email', 'status', 'username']);

            $item->class_type = class_basename($item);
            $user_details['complete_name'] = $item->user->getName();
            $user_details['is_pro'] = $item->user->isPro();
            $user_details['profile_photo'] = $item->user->getFullProfilePhoto();
            $item->user_details = $user_details;
            $item->attached_files = $item->responseFiles;
            return $item;
        });

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

        $unread_messages = $ticket->responses()->where('user_id', '!=', $user->id)->where('read', 0)->count(); // unread messages that don't belongsto auth user.

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
            'responses' => $conversations,
            'ticket' => $ticket,
            'resolvers' => $resolvers,
            'user' => $user,
            'unread_messages' => $unread_messages,
            'total_responses' => $ticket->responses->count(),
        ]);
    }

    public function getAllByTicket($id)
    {
        try {
            $user = auth()->userOrFail();
            // $ticket = \App\Ticket::findOrFail($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $ticket = \App\Ticket::find($id);

        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found.']);
        }

        if (!$user->can('update', $ticket)) {
            return response()->json(['error' => 'Error getting the data specified.']);
        }

        // Iterate through responses to get user data for each
        $responses = $ticket->responses->map(function ($item) {
            $user_details = $item->user->only(['first_name', 'last_name', 'email', 'status', 'username']);

            $item->class_type = class_basename($item);
            $user_details['complete_name'] = $item->user->getName();
            $user_details['is_pro'] = $item->user->isPro();
            $user_details['profile_photo'] = $item->user->getFullProfilePhoto();
            $item->user_details = $user_details;
            $item->attached_files = $item->responseFiles;
            return $item;
        });

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

        return response()->json(['responses' => $conversations]);
    }

    public function getLastUnreadResponse($id)
    {
        try {
            $user = auth()->userOrFail();
            // $ticket = \App\Ticket::findOrFail($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $ticket = \App\Ticket::find($id);

        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found.']);
        }

        if (!$user->can('update', $ticket)) {
            return response()->json(['error' => 'Error getting the data specified.']);
        }

        // Iterate through responses to get user data for each
        $response = $ticket->responses()->where('user_id', '!=', $user->id)->where('read', false)->latest()->first();

        return response()->json(['response' => $response]);
    }

    // get unread responses for ticket that don't == to auth user

    public function store(Request $request, $id)
    {
        try {
            $user = auth()->userOrFail();
            // $ticket = \App\Ticket::findOrFail($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $ticket = \App\Ticket::find($id);

        if (!$ticket) {
            return response()->json(['code' => 404, 'message' => 'Ticket not found.']);
        }

        // $user = \App\User::find(1);

        if (!$user->can('update', $ticket)) {
            return response()->json(['code' => 401, 'message' => 'Error getting the data specified.']);
        }

        // $request->validate([
        //     'the_files' => 'required|file'
        // ]);

        // message nullable
        // files nullable
        // but at least one must be required.

        $newResponse = new \App\ResponseTicket();

        $newResponse->user_id = $user->id;
        $newResponse->ticket_id = $id;
        $newResponse->filename = null; // eliminat?
        $newResponse->message = $request->message ? $request->message : '';
        if (!$result = $newResponse->save()) {
            return response()->json(['error' => 'Error saving data.']);
        }

        // $newResponse->information()->create();

        if (auth()->user()->id == $ticket->user_id) {
            if ($ticket->resolvers && $ticket->resolvers()->count() > 0) {

                // get only subscribed resolvers.
                $active_resolvers = $ticket->resolvers()->where('subscribed', true)->get();

                foreach ($active_resolvers as $resolver) {
                    if (auth()->user()->id != $resolver->user_id) {
                        $newResponse->information()->create([
                            'ticket_id' => $ticket->id,
                            'user_id' => $resolver->user_id,
                        ]);
                        // notify each resolver. if subscribed
                        Notification::send($resolver->user, new TicketMessageToResolverNotification($ticket, auth()->user()));
                    }
                }
            }
        } else {

            // save for all resolvers except current user.
            if ($ticket->resolvers && $ticket->resolvers()->count() > 0) {

                // get only subscribed resolvers

                $active_resolvers = $ticket->resolvers()->where('subscribed', true)->get();

                foreach ($active_resolvers as $resolver) {
                    if (auth()->user()->id != $resolver->user_id) {
                        $newResponse->information()->create([
                            'ticket_id' => $ticket->id,
                            'user_id' => $resolver->user_id,
                        ]);
                    }
                }
            }

            // save for ticket owner
            $newResponse->information()->create([
                'ticket_id' => $ticket->id,
                'user_id' => $ticket->user_id,
            ]);

            // if ticket owner is inactive, send notification on email ?

            $owner = $ticket->user;
            if (!$owner->isActive()) {
                Notification::send($owner, new TicketMessageInactiveUserNotification($ticket));
            } else {
                // send notif to owner
                Notification::send($owner, new TicketMessageNotification($ticket, $newResponse));
            }

        }

        // return response()->json(['$newResponse->id' => $newResponse->id]);

        if ($request->hasFile('the_files')) {
            $all_files = $request->file('the_files');
            foreach ($all_files as $one_file) {
                $infos = $this->uploadFile($one_file);

                $responseFile = new ResponseFile();
                $responseFile->response_ticket_id = $newResponse->id;
                $responseFile->name = $infos['file_name'];
                $responseFile->extension = $infos['ext'];
                $responseFile->mime_type = $infos['mime_type'];
                $responseFile->save();
            }
        }

        // working
        //broadcast(new TicketMessageEvent($newResponse->ticket))->toOthers();
        //event(new UserTicketsMessageEvent($newResponse->ticket));

        $user_details = $newResponse->user->only(['first_name', 'last_name', 'email', 'status', 'username']);
        $user_details['complete_name'] = $newResponse->user->getName();
        $user_details['is_pro'] = $newResponse->user->isPro();
        $user_details['profile_photo'] = $newResponse->user->getFullProfilePhoto();
        $newResponse->user_details = $user_details;
        $newResponse->attached_files = $newResponse->responseFiles;

        return response()->json($newResponse);

    }

    public function markAsRead(Request $request, $id)
    {
        try {
            $user = auth()->userOrFail();
            // $ticket = \App\Ticket::findOrFail($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        if (!$result = \App\ResponseTicketInformation::where('user_id', '=', $user->id)->where('ticket_id', $id)->where('read_at', null)->update(['read_at' => now()])) {
            return response()->json(['error' => 'Error saving data.']);
        }

        return response()->json($result);
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

        // Register file in Files table.

        return $infos;
    }

    public function destroy($message_id)
    {
        try {
            $user = auth()->userOrFail();
            // $ticket = \App\Ticket::findOrFail($id);
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $message = \App\ResponseTicket::find($message_id);

        $ticket_id = $message->ticket_id;

        if (!$message) {
            return response()->json(['code' => 404, 'message' => 'Message not found.']);
        }

        // $user = \App\User::find(1);

        if (!$user->can('update', $message)) {
            return response()->json(['code' => 401, 'message' => 'Error getting the data specified.']);
        }

        if ($message->responseFiles) {
            if ($message->responseFiles()->count() > 0) {
                foreach ($message->responseFiles as $file) {
                    $pathToFile = public_path() . '/storage\/tickets\/' . $file->name;
                    if (file_exists($pathToFile)) {
                        unlink($pathToFile);
                    }
                }

                $message->responseFiles()->delete();
            }
        }

        if (!$message->delete()) {
            return response()->json(['code' => 422, 'error' => 'Error deleting message.']);
        }

        // broadcast(new TicketSingleEvent($ticket_id))->toOthers();

        // return response()->json($message);
        return response()->json(['result' => true]);
    }

}

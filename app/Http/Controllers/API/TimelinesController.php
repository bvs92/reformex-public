<?php

namespace App\Http\Controllers\API;

use App\ClientMessageFile;
use App\Events\TimelineClientTurnOffConversationEvent;
use App\Events\TimelineClientTurnOnConversationEvent;
use App\Events\TimelineMessageClientToProEvent;
use App\Events\TimelineMessageProToClientEvent;
use App\Http\Controllers\Controller;
use App\QuoteFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TimelinesController extends Controller
{

    public function personalClient()
    {

        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $timelines = $user->timelines()->where('delete_client', 0)->get();

        // $timelines = $timelines->map(function($item){
        //     $user = \App\User::where('id', $item->data['user_id'])->first();
        //     $item->user_details = $user->only('first_name', 'last_name', 'email');
        //     // $item->unix_time = strtotime($item->created_at);
        //     return $item;
        // });

        $count = $timelines->count();
        // $count = auth()->user()->unreadNotifications()->count();
        return response()->json([
            'timelines' => $timelines,
            'totalTimelines' => $count,
        ]);

    }

    public function personalPro()
    {

        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        if (!$user->isPro()) {
            return response()->json([
                'timelines' => [],
                'totalTimelines' => 0,
            ]);
        }

        $timelines = \App\Timeline::where('professional_id', $user->professional->id)->where('delete_pro', 0)->get();

        // $timelines = $timelines->map(function($item){
        //     $user = \App\User::where('id', $item->data['user_id'])->first();
        //     $item->user_details = $user->only('first_name', 'last_name', 'email');
        //     // $item->unix_time = strtotime($item->created_at);
        //     return $item;
        // });

        $count = $timelines->count();
        // $count = auth()->user()->unreadNotifications()->count();
        return response()->json([
            'timelines' => $timelines,
            'totalTimelines' => $count,
        ]);

    }

    public function conversation($uuid)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $timeline = \App\Timeline::where('uuid', $uuid)->first();

        $conversations = collect();

        // $quotes = $professional->quotes()->where('demand_id', $demand->id)->get();
        $quotes = $timeline->quotes()->orderBy('created_at')->get();
        $quotes = $quotes->map(function ($item) {
            $item->files = $item->files;

            $item->files->map(function ($elem) {
                $elem->type = class_basename($elem);
                return $elem;
            });

            return $item;
        });

        // get user (demand owner) messages
        $messages = $timeline->client_messages()->orderBy('created_at')->get();
        $messages = $messages->map(function ($item) {
            $item->files = $item->files;

            $item->files->map(function ($elem) {
                $elem->type = class_basename($elem);
                return $elem;
            });

            return $item;
        });

        // $prospects = $timeline->prospects()->get(); // dupa ce schimb relatia de la hasOne -> HasMany
        $prospects = \App\Prospect::where('timeline_id', $timeline->id)->orderBy('created_at')->get();
        $prospects = $prospects->map(function ($item) {
            $item->response = $item->pro_response;
            return $item;
        });

        // get the review.| using get() to return array of objects
        $review = \App\Review::where('demand_id', $timeline->demand_id)->where('professional_id', $timeline->professional->id)->get();
        // $review = \App\Review::where('demand_id', $timeline->demand->id)->get();

        // winner
        if ($timeline->demand->winners) {
            $winners = \App\Winner::where('demand_id', $timeline->demand->id)->get();
        }

        // return response()->json($winners);

        // Combine quotes, messages and prospects into one collection
        $conversations = $quotes->concat($messages);
        $conversations = $conversations->concat($prospects);
        $conversations = $conversations->concat($review); // add the review

        if ($timeline->demand->winners) {
            $conversations = $conversations->concat($winners); // add the winner
        }

        $conversations = $conversations->map(function ($item) {
            $item->type = class_basename($item);
            return $item;
        });

        $pro = $timeline->professional->user->only('first_name', 'last_name', 'email', 'id', 'username', 'status');
        $pro['complete_name'] = $timeline->professional->user->getTheName();
        $pro['professional_id'] = $timeline->professional->id;

        $client = $timeline->user->only('first_name', 'last_name', 'email', 'id', 'username', 'status');
        $client['complete_name'] = $timeline->user->getTheName();

        return response()->json([
            'conversation' => $conversations->sortBy('created_at'),
            'client' => $client,
            'pro' => $pro,
            'review' => $review->first(),
            'winner' => $winners->where('status', '1')->first() ?? null,
        ]);

    }

    public function conversation_without_demand($uuid)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $timeline = \App\Timeline::where('uuid', $uuid)->first();

        $conversations = collect();

        // $quotes = $professional->quotes()->where('demand_id', $demand->id)->get();
        $quotes = $timeline->quotes()->orderBy('created_at')->get();
        $quotes = $quotes->map(function ($item) {
            $item->files = $item->files;
            return $item;
        });

        // get user (demand owner) messages
        $messages = $timeline->client_messages()->orderBy('created_at')->get();
        $messages = $messages->map(function ($item) {
            $item->files = $item->files;
            return $item;
        });

        // $prospects = $timeline->prospects()->get(); // dupa ce schimb relatia de la hasOne -> HasMany
        $prospects = \App\Prospect::where('timeline_id', $timeline->id)->orderBy('created_at')->get();
        $prospects = $prospects->map(function ($item) {
            $item->response = $item->pro_response;
            return $item;
        });

        // get the review.| using get() to return array of objects
        $review = \App\Review::where('demand_id', $timeline->demand_id)->where('professional_id', $timeline->professional->id)->get();
        // $review = \App\Review::where('demand_id', $timeline->demand->id)->get();

        // winner
        $winner = \App\Winner::where('demand_id', $timeline->demand_id)->get();

        // return response()->json($review);

        // Combine quotes, messages and prospects into one collection
        $conversations = $quotes->concat($messages);
        $conversations = $conversations->concat($prospects);
        $conversations = $conversations->concat($review); // add the review

        if ($winner && $winner->count() > 0) {
            $conversations = $conversations->concat($winner); // add the winner
        }

        $conversations = $conversations->map(function ($item) {
            $item->type = class_basename($item);
            return $item;
        });

        $pro = $timeline->professional->user->only('first_name', 'last_name', 'email', 'id', 'username', 'status');
        $pro['complete_name'] = $timeline->professional->user->getTheName();
        $pro['professional_id'] = $timeline->professional->id;

        $client = $timeline->user->only('first_name', 'last_name', 'email', 'id', 'username', 'status');
        $client['complete_name'] = $timeline->user->getTheName();

        return response()->json([
            'conversation' => $conversations->sortBy('created_at'),
            'client' => $client,
            'pro' => $pro,
            'review' => $review->first(),
            'winner' => $winner ? $winner->first() : null,
        ]);

    }

    public function conversation_without_demand_pro($uuid)
    {
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $timeline = \App\Timeline::where('uuid', $uuid)->first();

        $conversations = collect();

        // $quotes = $professional->quotes()->where('demand_id', $demand->id)->get();
        $quotes = $timeline->quotes()->orderBy('created_at')->get();
        $quotes = $quotes->map(function ($item) {
            $item->files = $item->files;
            return $item;
        });

        // get user (demand owner) messages
        $messages = $timeline->client_messages()->orderBy('created_at')->get();
        $messages = $messages->map(function ($item) {
            $item->files = $item->files;
            return $item;
        });

        // $prospects = $timeline->prospects()->get(); // dupa ce schimb relatia de la hasOne -> HasMany
        $prospects = \App\Prospect::where('timeline_id', $timeline->id)->orderBy('created_at')->get();
        $prospects = $prospects->map(function ($item) {
            $item->response = $item->pro_response;
            return $item;
        });

        // get the review.| using get() to return array of objects
        $review = \App\Review::where('demand_id', $timeline->demand_id)->where('professional_id', $timeline->professional->id)->get();
        // $review = \App\Review::where('demand_id', $timeline->demand->id)->get();

        // winner
        $winner = \App\Winner::where('demand_id', $timeline->demand_id)->get();

        // return response()->json($review);

        // Combine quotes, messages and prospects into one collection
        $conversations = $quotes->concat($messages);
        $conversations = $conversations->concat($prospects);
        $conversations = $conversations->concat($review); // add the review

        if ($winner && $winner->count() > 0) {
            $conversations = $conversations->concat($winner); // add the winner
        }

        $conversations = $conversations->map(function ($item) {
            $item->type = class_basename($item);
            return $item;
        });

        $pro = $timeline->professional->user->only('first_name', 'last_name', 'email', 'id', 'username', 'status');
        $pro['complete_name'] = $timeline->professional->user->getTheName();
        $pro['professional_id'] = $timeline->professional->id;

        $client = $timeline->user->only('first_name', 'last_name', 'email', 'id', 'username', 'status');
        $client['complete_name'] = $timeline->user->getTheName();

        return response()->json([
            'conversation' => $conversations->sortBy('created_at'),
            'client' => $client,
            'pro' => $pro,
            'review' => $review->first(),
            'winner' => $winner ? $winner->first() : null,
        ]);

    }

    public function storeQuote(Request $request, $uuid)
    {

        // validare date.

        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $timeline = \App\Timeline::where('uuid', $uuid)->first();

        if (!$timeline) {
            return response()->json([
                'error' => 'Unable to continue.',
            ]);
        }

        if (!$timeline->isActive()) {
            return response()->json([
                'error' => 'Nu puteti comunica. Conversatia este inactiva.',
            ]);
        }

        if (!$user->can('update_pro', $timeline)) {
            return response()->json(['error' => 'Error getting the data specified.']);
        }

        $quote = new \App\Quote;
        $quote->timeline_id = $timeline->id;
        $quote->demand_id = $timeline->demand_id;
        $quote->professional_id = $timeline->professional_id;
        $quote->user_id = $user->id;
        $quote->message = $request->message;
        $result = $quote->save();

        if ($request->hasFile('the_files')) {
            $all_files = $request->file('the_files');
            foreach ($all_files as $one_file) {
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

            $quote['files'] = $quote->files;
        }

        $quote['type'] = class_basename($quote);

        event(new TimelineMessageProToClientEvent($timeline));

        return response()->json([
            $quote,
        ]);

    }

    public function storeMessage(Request $request, $uuid)
    {

        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $timeline = \App\Timeline::where('uuid', $uuid)->first();

        if (!$timeline) {
            return response()->json([
                'error' => 'Unable to continue.',
            ]);
        }

        if (!$timeline->isActive()) {
            return response()->json([
                'error' => 'Nu puteti comunica. Conversatia este inactiva.',
            ]);
        }

        if (!$user->can('update_client', $timeline)) {
            return response()->json(['error' => 'Error getting the data specified.']);
        }

        $client_message = new \App\ClientMessage;
        $client_message->timeline_id = $timeline->id;
        $client_message->demand_id = $timeline->demand_id;
        $client_message->user_id = $timeline->user_id;
        $client_message->message = $request->message;

        $result = $client_message->save();

        if ($request->hasFile('the_files')) {
            $all_files = $request->file('the_files');
            foreach ($all_files as $one_file) {
                $infos = $this->uploadFileMessage($one_file);

                $clientMessageFile = new ClientMessageFile();
                $clientMessageFile->client_message_id = $client_message->id;
                $clientMessageFile->user_id = auth()->user()->id;
                $clientMessageFile->name = $infos['file_name'];
                $clientMessageFile->extension = $infos['ext'];
                // $clientMessageFile->path = null;
                $clientMessageFile->mime_type = $infos['mime_type'];

                $clientMessageFile->save();
            }

            $client_message['files'] = $client_message->files;
        }

        $client_message['type'] = class_basename($client_message);

        event(new TimelineMessageClientToProEvent($timeline));

        return response()->json([
            $client_message,
        ]);
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
            'file_name' => $file_name,
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

    protected function uploadFileMessage($file)
    {

        // Get image original extension
        $ext = $file->getClientOriginalExtension();
        $mimeType = $file->getClientMimeType();
        $random = substr(md5(mt_rand()), 0, 5);

        // dd($mimeType);

        // Compose the name
        $file_name = 'message-file-' . time() . "-" . $random . "-" . auth()->user()->id . '.' . $ext;

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

        Storage::disk('public')->putFileAs('client_messages', $file, $file_name);

        return $infos;
    }

    public function getTimelineByUUID($uuid)
    {

        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $timeline = \App\Timeline::where('uuid', $uuid)->first();

        if (!$timeline) {
            return response()->json([
                'error' => 'Unable to continue.',
            ]);
        }

        return response()->json($timeline);
    }

    public function changeStatusUUID(Request $request, $uuid)
    {

        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $timeline = \App\Timeline::where('uuid', $uuid)->first();

        if (!$timeline) {
            return response()->json([
                'error' => 'Unable to continue. Cannot find the timeline.',
            ]);
        }

        $this->authorize('update_client', $timeline);

        if ($timeline->isActive()) {
            // dd('este activa');
            $timeline->status = '1';

            // send event to notify PRO
            event(new TimelineClientTurnOffConversationEvent($timeline));

        } else {
            // dd('este terminata');
            $timeline->status = '0';
            // send event to notify PRO
            event(new TimelineClientTurnOnConversationEvent($timeline));

        }

        if (!$timeline->save()) {
            return response()->json([
                'error' => 'Am intampinat erori.',
            ]);
        }

        return response()->json([
            'success' => 'Status conversatie modificat.',
            'status' => $timeline->status,
        ]);
    }

    public function deleteByClient($uuid)
    {

        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $timeline = \App\Timeline::where('uuid', $uuid)->first();

        if (!$timeline) {
            return response()->json([
                'error' => 'Unable to continue. Cannot find the timeline.',
            ]);
        }

        $this->authorize('update_client', $timeline);

        // eliminam mesajele din conversatie?
        if ($timeline->client_messages && $timeline->client_messages()->count() > 0) {
            foreach ($timeline->client_messages as $client_message) {
                if ($client_message->files && $client_message->files->count() > 0) {

                    foreach ($client_message->files as $theFile) {
                        $theFile->delete();
                    }
                }

                $client_message->delete();
            }
        }

        // check if PRO marked for deleting. - mark timeline available for delete: delete_client = true
        // Daca PRo a sters conversatia sa si a marcat ca TRUE delete_pro, atunci elimina timeline complet.
        if ($timeline->deleteFromPro() == 1) {

            // verifica si sterge winners
            $winner = Winner::where('demand_id', $timeline->demand_id)->where('professional_id', $timeline->professional_id)->delete();

            // verifica si sterge prospects
            if ($timeline->prospects && $timeline->prospects()->count() > 0) {
                $timeline->prospects()->delete();
            }

            // verifica si sterge demand_professional (cumparator cerere)
            DB::table('demand_professional')->where('demand_id', $timeline->demand_id)->where('professional_id', $timeline->professional_id)->delete();

            if (!$timeline->delete()) {
                return response()->json([
                    'error' => 'Am intampinat erori. Va rugam incercati mai tarziu.',
                ]);
            }
        } else {
            $timeline->delete_client = true;
            if ($timeline->isActive()) {
                $timeline->status = '1'; // mark as inactive.
            }
            $timeline->save();
        }

        return response()->json([
            'success' => 'Actiune executata cu succes.',
        ]);
    }

}

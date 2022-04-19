<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Controller;
use Illuminate\Http\Request;

class NotificationsController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $notification = auth()->user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->delete();
            return response()->json(['result' => true]);
        }
        return response()->json(['result' => false]);
    }

    public function count()
    {

        $user = \App\User::first();
        $count = $user->unreadNotifications()->count();
        // $count = auth()->user()->unreadNotifications()->count();
        return response()->json([
            'unreadNotifications' => $count,
        ]);
    }

    public function personal()
    {

        // $user = \App\User::first();
        // $user = auth()->user();

        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        // return response()->json(['user' => auth()->userOrFail()]);

        $notifications = $user->unreadNotifications()->paginate(10);

        $notifications = $notifications->map(function ($item) {
            if (isset($item->data['user_id'])) {
                $user = \App\User::where('id', $item->data['user_id'])->first();
                $item->user_details = $user->makeVisible('first_name', 'last_name', 'email');
            }
            $item->unix_time = strtotime($item->created_at);
            return $item;
        });

        $count = $user->unreadNotifications()->count();
        // $count = auth()->user()->unreadNotifications()->count();
        return response()->json([
            'paginate_result' => $user->unreadNotifications()->paginate(10),
            'personalNotifications' => $notifications,
            'unreadNotifications' => $count,
        ]);
    }

    public function personalPaginated($page)
    {

        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        // return response()->json(['user' => auth()->userOrFail()]);

        if ($page && $page > 1) {
            $notifications = $user->unreadNotifications()->skip($page - 1 * 15)->paginate(15);
            $pagination_result = $user->unreadNotifications()->skip($page - 1 * 15)->paginate(15);
        } else {
            $pagination_result = $user->unreadNotifications()->paginate(15);
            $notifications = $user->unreadNotifications()->paginate(15);
        }

        $notifications = $notifications->map(function ($item) {
            $user = \App\User::where('id', $item->data['user_id'])->first();
            $item->user_details = $user->only('first_name', 'last_name', 'email');
            $item->unix_time = strtotime($item->created_at);
            return $item;
        });

        $count = $user->unreadNotifications()->count();
        // $count = auth()->user()->unreadNotifications()->count();
        return response()->json([
            'paginate_result' => $pagination_result,
            'personalNotifications' => $notifications,
            'unreadNotifications' => $count,
        ]);
    }

    public function loadPersonal(Request $request)
    {

        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        // return response()->json(['user' => auth()->userOrFail()]);

        $from = $request->input('from');
        $limit = $request->input('limit');

        if ($from && $from > 0) {
            $notifications = $user->unreadNotifications()->skip($from)->take($limit)->get();
        } else {
            $notifications = $user->unreadNotifications()->take($limit)->get();
        }

        $notifications = $notifications->map(function ($item) {
            if (isset($item->data['user_id'])) {
                $user = \App\User::where('id', $item->data['user_id'])->first();
                $item->user_details = $user->only('first_name', 'last_name', 'email');
            }
            $item->unix_time = strtotime($item->created_at);
            return $item;
        });

        $count = $user->unreadNotifications()->count();
        // $count = auth()->user()->unreadNotifications()->count();
        return response()->json([
            'personalNotifications' => $notifications,
            'unreadNotifications' => $count,
        ]);
    }

    public function totalPersonal()
    {

        // $user = \App\User::first();
        // $user = auth()->user();

        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        return response()->json([
            'paginate_result' => $user->notifications()->orderBy('created_at', 'desc')->paginate(25),

        ]);
    }

    public function totalPersonalPaginated($page)
    {

        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        // return response()->json(['user' => auth()->userOrFail()]);

        if ($page && $page > 1) {
            // $notifications = $user->notifications()->orderBy('created_at', 'desc')->skip($page - 1 * 25)->paginate(25);
            $pagination_result = $user->notifications()->orderBy('created_at', 'desc')->skip($page - 1 * 25)->paginate(25);
        } else {
            $pagination_result = $user->notifications()->orderBy('created_at', 'desc')->paginate(25);
            // $notifications = $user->notifications()->orderBy('created_at', 'desc')->paginate(25);
        }

        // $notifications = $notifications->map(function ($item) {
        //     $user = \App\User::where('id', $item->data['user_id'])->first();
        //     $item->user_details = $user->only('first_name', 'last_name', 'email');
        //     $item->unix_time = strtotime($item->created_at);
        //     return $item;
        // });

        // $count = $user->notifications()->count();
        // $count = auth()->user()->unreadNotifications()->count();
        return response()->json([
            'paginate_result' => $pagination_result,
            // 'personalNotifications' => $notifications,
        ]);
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['result' => true]);
        }
        return response()->json(['result' => false]);
    }

    public function markAsReadSelected(Request $request)
    {
        if (count($request->_ids) < 1) {
            return response()->json(['error' => 'Am intampinat erori.']);
        }

        $notifications = auth()->user()->notifications()->whereIn('id', $request->_ids)->get();
        $notifications->markAsRead();
        return response()->json(['success' => 'Actiune executata cu succes.']);
    }

    public function deleteSelected(Request $request)
    {
        if (count($request->_ids) < 1) {
            return response()->json(['error' => 'Am intampinat erori.']);
        }

        if (!auth()->user()->notifications()->whereIn('id', $request->_ids)->delete()) {
            return response()->json(['error' => 'Am intampinat erori.']);
        }
        return response()->json(['success' => 'Actiune executata cu succes.']);
    }

}

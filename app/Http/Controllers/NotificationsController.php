<?php

namespace App\Http\Controllers;

use App\Demand;
use App\Ticket;
use App\User;
use Illuminate\Support\Facades\Http;

class NotificationsController extends Controller
{

    public function index()
    {
        // $notifications = auth()->user()->notifications;

        // daca USER = ADMIN => nu prelua raspunsurile cererilor.

        // daca USER = ADMIN => preia raportarile firmelor la cereri

        // Daca USER = client => preia doar ce este necesar

        // Daca User = firma => preia doar ce este necesar ETC in cunctie de roluri

        // $notifications = auth()->user()->notifications()->where('type', '=','App\Notifications\DemandBought')->orWhere('type', '=','App\Notifications\TicketNotification')->orWhere('type', '=','App\Notifications\TimelineAction')->orWhere('type', '=','App\Notifications\ReportDemandNotification')->orWhere('type', '=','App\Notifications\ResponseForReportedDemandNotification')->orderBy('created_at', 'asc')->get();
        $notifications = auth()->user()->notifications()->orderBy('created_at', 'asc')->paginate(25);
        // $notifications = auth()->user()->notifications()->where('type', '==','App\Notifications\DemandBought')->orWhere('type', '==','App\Notifications\TicketNotification')->orWhere('type', '==','App\Notifications\TimelineAction')->get();
        // $user = \App\User::findOrFail(2);
        // $notifications = $user->notifications;

        return view('volgh.notifications.index', [
            'notifications' => $notifications,
        ]);
    }

    public function indexVue()
    {
        // $notifications = auth()->user()->notifications;

        // daca USER = ADMIN => nu prelua raspunsurile cererilor.

        // daca USER = ADMIN => preia raportarile firmelor la cereri

        // Daca USER = client => preia doar ce este necesar

        // Daca User = firma => preia doar ce este necesar ETC in cunctie de roluri

        // $notifications = auth()->user()->notifications()->where('type', '=','App\Notifications\DemandBought')->orWhere('type', '=','App\Notifications\TicketNotification')->orWhere('type', '=','App\Notifications\TimelineAction')->orWhere('type', '=','App\Notifications\ReportDemandNotification')->orWhere('type', '=','App\Notifications\ResponseForReportedDemandNotification')->orderBy('created_at', 'asc')->get();
        // $notifications = auth()->user()->notifications()->orderBy('created_at', 'asc')->paginate(25);
        // $notifications = auth()->user()->notifications()->where('type', '==','App\Notifications\DemandBought')->orWhere('type', '==','App\Notifications\TicketNotification')->orWhere('type', '==','App\Notifications\TimelineAction')->get();
        // $user = \App\User::findOrFail(2);
        // $notifications = $user->notifications;

        return view('volgh.notifications.index-vue', [
            // 'notifications' => $notifications,
        ]);
    }

    public function indexMessages()
    {
        $notifications = auth()->user()->notifications()->where('type', 'App\Notifications\TimelineMessageNotification')->orWhere('type', 'App\Notifications\TicketMessageNotification')->get();
        // $notifications = auth()->user()->notifications()->where('type', 'App\Notifications\TimelineMessageNotification')->groupBy('timeline_id')->get();
        // $user = \App\User::findOrFail(2);
        // $notifications = $user->notifications;

        // dd($notifications);

        return view('volgh.notifications.messages', [
            'notifications' => $notifications,
        ]);
    }

    public function indexMessagesSecond()
    { // num functioneaza
        $notifications = auth()->user()->notifications()->where('type', 'App\Notifications\TimelineMessageNotification')->orWhere('type', 'App\Notifications\TicketMessageNotification')->get();
        $unreadNotifications = auth()->user()->unreadNotifications()->where('type', 'App\Notifications\TimelineMessageNotification')->orWhere('type', 'App\Notifications\TicketMessageNotification')->get();
        // $notifications = auth()->user()->notifications()->where('type', 'App\Notifications\TimelineMessageNotification')->groupBy('timeline_id')->get();
        // $user = \App\User::findOrFail(2);
        // $notifications = $user->notifications;

        // dd($unreadNotifications);

        $types = $unreadNotifications->groupBy('type');
        // dump($tickets);

        $news = [];
        foreach ($types as $type => $col) {
            if ($type == 'App\Notifications\TicketMessageNotification') {
                $news[$type] = $col->groupBy('ticket_id');
            } elseif ($type == 'App\Notifications\TimelineMessageNotification') {
                $news[$type] = $col->groupBy('timeline_id');
            }

        }

        // dd($news->first());
        foreach ($news as $type => $col) {
            dump($type);
            dump($col->values()->all());
            // dump($col->first()->groupBy('ticket_id'));

            // foreach($col as $item){
            //     dump($item->groupBy('ticket_id'));
            // }
            dump('------');
        }

        dd('stop');

        return view('volgh.notifications.messages-second', [
            'notifications' => $notifications,
            'unreadNotifications' => $unreadNotifications,
        ]);
    }

    public function showMessage($id)
    {
        // dd('aici');

        $notification = auth()->user()->notifications()->findOrFail($id);

        $notification->markAsRead();

        if ($notification->type == 'App\Notifications\TimelineMessageNotification') {
            if (auth()->user()->isPro()) {
                return redirect()->route('timeline.show.pro', $notification->data['timeline_id']);
            } else {
                return redirect()->route('timeline.show.client', $notification->data['timeline_id']);
            }
        } elseif ($notification->type == 'App\Notifications\TicketMessageNotification') {
            return redirect()->route('tickets.show', $notification->data['ticket_id']);
        }

    }

    public function show($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $user_notification = \App\User::findOrFail($notification->notifiable_id);

        $notification->markAsRead();

        if ($notification->type == 'App\Notifications\DemandBought') {
            $demand = \App\Demand::findOrFail($notification->data['demand_id']);
            $user = \App\User::findOrFail($notification->data['user_id']);

            return view('volgh.notifications.show', [
                'notification' => $notification,
                'user' => $user,
                'user_notification' => $user_notification,
                'demand' => $demand,
            ]);

        } elseif ($notification->type == 'App\Notifications\TimelineAction') {
            $user = \App\User::findOrFail($notification->data['user_id']);
            $timeline = \App\Timeline::findOrFail($notification->data['timeline_id']);
            $demand = $timeline->demand;

            return view('volgh.notifications.show', [
                'notification' => $notification,
                'user' => $user,
                'user_notification' => $user_notification,
                'demand' => $demand,
            ]);

        } elseif ($notification->type == 'App\Notifications\TimelineMessageNotification') {
            $user = \App\User::findOrFail($notification->data['user_id']);
            $timeline = \App\Timeline::findOrFail($notification->data['timeline_id']);
            $demand = $timeline->demand;

            return view('volgh.notifications.show', [
                'notification' => $notification,
                'user' => $user,
                'user_notification' => $user_notification,
                'demand' => $demand,
            ]);

        } elseif ($notification->type == 'App\Notifications\TicketNotification') {
            $user = \App\User::findOrFail($notification->data['user_id']);
            $ticket = Ticket::find($notification->data['ticket_id']) ?? null;

            // dd($ticket);

            return view('volgh.notifications.show', [
                'notification' => $notification,
                'user' => $user,
                'user_notification' => $user_notification,
                'ticket' => $ticket,
            ]);

            // if($notification->data['type'] == 'ticket_deleted'){
            //     $ticket = NULL;
            //     return view('volgh.notifications.show', [
            //         'notification'  => $notification,
            //         'user'  => $user,
            //         'user_notification'  => $user_notification,
            //         'ticket'  => $ticket
            //     ]);
            // } else {
            //     $ticket = Ticket::findOrFail($notification->data['ticket_id']);
            //     return view('volgh.notifications.show', [
            //         'notification'  => $notification,
            //         'user'  => $user,
            //         'user_notification'  => $user_notification,
            //         'ticket'  => $ticket
            //     ]);
            // }

        } elseif ($notification->type == 'App\Notifications\ReportDemandNotification') {

            $demand = \App\Demand::findOrFail($notification->data['demand_id']);
            $user = \App\User::findOrFail($notification->data['user_id']);

            $report = $user->reports()->where('demand_id', $demand->id)->first();

            return view('volgh.notifications.show', [
                'notification' => $notification,
                'user' => $user, // reporting user
                'user_notification' => $user_notification,
                'demand' => $demand,
                'report' => $report,
            ]);
        } elseif ($notification->type == 'App\Notifications\ResponseForReportedDemandNotification') {

            $demand = \App\Demand::findOrFail($notification->data['demand_id']);
            $user = \App\User::findOrFail($notification->data['user_id']);

            $report = $user_notification->reports()->where('demand_id', $demand->id)->first();

            // dd($report);

            return view('volgh.notifications.show', [
                'notification' => $notification,
                'user' => $user, // responsind user -> usually admin
                'user_notification' => $user_notification,
                'demand' => $demand,
                'report' => $report,
            ]);
        }

    }

    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);

        if (!$notification->delete()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam, incercati mai tarziu.');
        }

        return redirect()->route('notifications.all')->with('success', 'Actiune executata cu succes.');
    }

    public function settings()
    {
        return view('volgh.settings.notifications');
    }
}

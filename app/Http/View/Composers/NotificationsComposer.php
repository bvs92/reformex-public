<?php

namespace App\Http\View\Composers;

use App\User;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Support\Facades\Notification;



class NotificationsComposer {

    public $notifications;

    public function __construct()
    {
        // $this->notifications = $notifications;
        // $this->unreadMessageTimelineNotifications = auth()->user()->unreadNotifications()->where('type', 'App\Notifications\TimelineMessageNotification');

    }

    public function compose(View $view)
    {
        if(auth()->user()){
            // $view->with('count', $this->notifications->count());
            $unreadMessageTicketNotifications = auth()->user()->unreadNotifications()->where('type', 'App\Notifications\TicketMessageNotification')->get();
            // $unreadMessageTicketNotifications = auth()->user()->unreadNotifications()->where('type', 'App\Notifications\TicketMessageNotification')->get()->groupBy('ticket_id');
            // dd($unreadMessageTicketNotifications);
            $unreadMessageTimelineNotifications = auth()->user()->unreadNotifications()->where('type', 'App\Notifications\TimelineMessageNotification')->get();
            $mergedMessageNotifications = $unreadMessageTicketNotifications->merge($unreadMessageTimelineNotifications);
    
    
            $sortedMessages = $mergedMessageNotifications->sortByDesc('created_at');
            // $sorted = $mergedNotifications->sortByDesc(function ($transaction, $key) {
            //     return Carbon::parse($transaction['created_at'])->timestamp;
            // });
    
            $unreadRestNotifications = auth()->user()->unreadNotifications()->where('type', '!=', 'App\Notifications\TicketMessageNotification')->where('type', '!=', 'App\Notifications\TimelineMessageNotification')->get();
    
            $view->with([
                'unreadMessageNotifications' => $sortedMessages,
                'unreadRestNotifications' => $unreadRestNotifications->sortByDesc('created_at')
            ]);  
        }
        
    }
}
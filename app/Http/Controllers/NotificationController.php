<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\Notification\NotificationService;
use App\Http\Requests\Notification\StoreNotificationsRequest;

class NotificationController extends Controller
{
    public function __construct(protected NotificationService $notificationService){}

    public function store(User $user , StoreNotificationsRequest $request)
    {
        $this->notificationService->pushNotification(
            'title' , 
            'description' ,
            'type' ,
            1 ,
            $user ,
            User::class ,
            $user->id ,
            true 
       ) ;

       return $user->notifications()->get() ;
    }
}

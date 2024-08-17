<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\Notification\NotificationService;
use App\Http\Requests\Notification\StoreNotificationsRequest;

class NotificationController extends Controller
{
    public function __construct(protected NotificationService $notificationService){}

    public function index(User $user)
    {
       return $user->notifications()->get() ;
    }
}

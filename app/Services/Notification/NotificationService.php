<?php

namespace App\Services\Notification;

use App\Models\User;
use App\Constants\Constants;
use App\Constants\Notifications;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\NotificationRecourse;

class NotificationService
{
    protected ?User $user;

    public function __construct()
    {
        $this->user = auth('sanctum')->user();
    }



    public function getAllNotifications($hasRead = null, $countOnly = null)
    {
        $notifications = $this->user->notifications();
        if ($hasRead !== null) {
            $notifications->where('has_read', $hasRead);
        }
        if ($countOnly) {
            return $notifications->count();
        }
        $notifications = $notifications->orderBy('id', 'DESC')->paginate(config('app.pagination_limit'));
        return NotificationRecourse::collection($notifications);
    }
    public function getNotificationTypeStatistics($hasRead = null)
    {
        $stats = $this->user->notifications();
        if ($hasRead !== null) {
            $stats->where('has_read', $hasRead);
        }
        return $stats->select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->pluck('count', 'type');
    }


    public function readAllNotifications()
    {
        return  $this->user->notifications()->update(['has_read' => 1]);
    }

    public function pushNotification($title, $description, $type, $state, $user, $modelType, $modelId, $checkDuplicated = false)
    {
        $data = [
            'title' => $title,
            'description' => $description,
            'type' => $type,
            'state' => $state,
            'model_id' => $modelId,
            'model_type' => $modelType,
        ];

        if ($checkDuplicated) {
            $filteredData = array_diff_key($data, array_flip(['title', 'description']));
            $user->notifications()->firstOrCreate($filteredData, $data);
        } else
            $user->notifications()->create($data);
    }
}

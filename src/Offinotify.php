<?php

namespace Tantupix\Offinotify;

use Tantupix\Offinotify\Models\OfficialNotification;
use Illuminate\Support\Collection;
use Tantupix\Offinotify\Jobs\OfficialTried;
use App\Contracts\OfficialNotificationInterface;
use Carbon\Carbon;

class Offinotify implements OfficialNotificationInterface
{
    public function send($type, $notifiable, $trigger, array $attributes = [])
    {
        if (!$notifiable instanceof Collection && !is_array($notifiable)) {
            $notifiable = [$notifiable];
        }

        try {
            // 写数据
            dispatch(new OfficialTried($type, $trigger, $notifiable, $attributes));
            return ['code' => 0, 'msg' => 'OK'];
        } catch (\Exception $e) {
            return ['code' => $e->getCode(), 'msg' => $e->getMessage()];
        }
    }

    public function notifications()
    {
        return OfficialNotification::notifications();
    }

    public function readNotifications()
    {
        return OfficialNotification::readNotifications();
    }

    public function unreadNotifications()
    {
        return OfficialNotification::unreadNotifications();
    }

    public function show($id)
    {
        $notification = OfficialNotification::query()->where('id', $id)->firstOrFail();

        if ($notification->notifiable_id != auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        // 更新为已读
        $notification->markAsRead();

        return $notification;
    }

    public function markAsRead()
    {
        return OfficialNotification::unreadNotifications()->update(['read_at' => Carbon::now()->toDateTimeString()]);
    }

    public function unreadCount()
    {
        return OfficialNotification::unreadNotifications()->count();
    }

    public function destroy()
    {
        return OfficialNotification::readNotifications()->delete();
    }

    public function updateNotifications($class, $id, array $data = [])
    {
        return OfficialNotification::where([
            'trigger_type' => $class,
            'trigger_id' => $id
        ])->update([
            'data' => json_encode($data),
        ]);
    }
}
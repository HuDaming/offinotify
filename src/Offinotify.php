<?php

namespace Tantupix\Offinotify;

use App\Contracts\OfficialNotificationInterface;

class Offinotify implements OfficialNotificationInterface
{
    public function send($notifiable, $trigger, $attributes)
    {
        if (!$notifiable instanceof Collection && !is_array($notifiable)) {
            $notifiable = [$notifiable];
        }

        try {
            OfficialTried::dispatch($notifiable, $trigger, $attributes);
        } catch (\Exception $e) {
            // TODO;
        }
    }

    public function notifications()
    {
        return Notification::notifications();
    }

    public function readNotifications()
    {
        return Notification::readNotifications();
    }

    public function unreadNotifications()
    {
        return Notification::unreadNotifications();
    }

    public function show($id)
    {
        $notification = Notification::query()->where('id', $id)->findOrFail();

        if ($notification->notifiable_id != auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        // 更新为已读
        $notification->markAsRead();

        return $notification;
    }

    public function markAsRead()
    {
        return Notification::unreadNotifications()->update(['read_at' => Carbon::now()->toDateTimeString()]);
    }

    public function unreadCount()
    {
        return Notification::unreadNotifications()->count();
    }

    public function destroy()
    {
        return Notification::readNotifications()->delete();
    }

    public function updateNotifications()
    {
        echo "更新系统通知";
    }
}
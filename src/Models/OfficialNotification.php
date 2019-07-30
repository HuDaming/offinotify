<?php

namespace Tantupix\Offinotify\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfficialNotification extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    public $table = 'official_notifications';

    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function notifiable()
    {
        return $this->morphTo();
    }

    public static function notifications()
    {
        return self::where('notifiable_id', auth()->id())->orderby('created_at', 'desc');
    }

    public static function readNotifications()
    {
        return self::notifications()->whereNotNull('read_at');
    }

    public static function unreadNotifications()
    {
        return self::notifications()->whereNull('read_at');
    }

    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->forceFill(['read_at' => $this->freshTimestamp()])->save();
        }
    }

    public static function saveNotifications($type, $notifiable, $trigger, $attributes)
    {
        $notifications = [];
        $datetime = Carbon::now()->toDateTimeString();

        foreach ($notifiable as $item) {
            $notifications[] = ['id' => Str::uuid()->toString(),
                'type' => $type,
                'notifiable_id' => $item->id,
                'notifiable_type' => get_class($item),
                'trigger_id' => $trigger->id,
                'trigger_type' => get_class($trigger),
                'data' => json_encode($attributes),
                'created_at' => $datetime,
                'updated_at' => $datetime,
            ];
        }

        // 写入数据
        self::insert($notifications);
    }
}

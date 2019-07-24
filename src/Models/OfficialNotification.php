<?php

namespace Tantupix\Offinotify\Models;

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
}

<?php

namespace Tantupix\Offinotify\Jobs;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Tantupix\Offinotify\Models\OfficialNotification;

class OfficialTried
{
    protected $notifiable;
    protected $trigger;
    protected $attributes;

    public function __construct($notifiable, $trigger, array $attributes = [])
    {
        $this->notifiable = $notifiable;
        $this->trigger = $trigger;
        $this->attributes = $attributes;
    }

    public function send()
    {
        $notifications = [];
        $data = $this->toOfficialNotification();
        $datetime = Carbon::now()->toDateTimeString();

        foreach ($this->notifiable as $item) {
            $notifications[] = [
                'id' => Str::uuid()->toString(),
                'type' => get_class($this->trigger),
                'notifiable_id' => $item->id,
                'notifiable_type' => get_class($item),
                'trigger_id' => $this->trigger->id,
                'trigger_type' => get_class($this->trigger),
                'data' => json_encode($data),
                'created_at' => $datetime,
                'updated_at' => $datetime,
            ];
        }

        // 写入数据
        return OfficialNotification::insert($notifications);
    }

    protected function toOfficialNotification()
    {
        return [
            'title' => !empty($this->attributes) ? ($this->attributes)['title'] : '',
            'body' => !empty($this->attributes) ? ($this->attributes)['body'] : '',
            'subject' => null,
            'verb' => null,
            'object' => $this->trigger->toNotification(),
        ];
    }
}
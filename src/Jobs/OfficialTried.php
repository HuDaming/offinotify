<?php

namespace Tantupix\Offinotify\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Carbon\Carbon;
use Illuminate\Support\Str;

class OfficialTried implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notifiable;
    protected $trigger;
    protected $attributes;
    protected $jpush;

    public function __construct($trigger, $notifiable, array $attributes = [], $jpush = true)
    {
        $this->trigger = $trigger;
        $this->notifiable = $notifiable;
        $this->attributes = $attributes;
        $this->jpush = $jpush;
    }

    public function handle()
    {
        $notifications = $userIds = [];
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
            $userIds[] = $item->id;
        }

        // 写入数据
        \DB::table('official_notifications')->insert($notifications);
    }

    protected function toOfficialNotification()
    {
        $object = $this->trigger->toNotification();

        return [
            'title' => !empty($this->attributes) ? ($this->attributes)['title'] : $object['title'],
            'body' => !empty($this->attributes) ? ($this->attributes)['body'] : $object['title'],
            'subject' => null,
            'verb' => null,
            'object' => $object,
        ];
    }
}
<?php

namespace Tantupix\Offinotify\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use App\Models\OfficialNotification;

class OfficialTried implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notifiable;
    protected $trigger;
    protected $attributes;

    public function __construct($notifiable, $trigger, array $attributes = [])
    {
        $this->notifiable = $notifiable;
        $this->trigger = $trigger;
        $this->attributes = $attributes;
    }

    public function handle()
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
                'data' => $data,
                'created_at' => $datetime,
                'updated_at' => $datetime,
            ];
        }

        // 写入数据
        OfficialNotification::insert($notifications);
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
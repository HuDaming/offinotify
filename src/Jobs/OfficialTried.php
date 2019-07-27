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

    protected $type;
    protected $notifiable;
    protected $trigger;
    protected $attributes;

    public function __construct($type, $trigger, $notifiable, array $attributes = [])
    {
        $this->type = $type;
        $this->trigger = $trigger;
        $this->notifiable = $notifiable;
        $this->attributes = $attributes;
    }

    public function handle()
    {
        $notifications = [];
        $datetime = Carbon::now()->toDateTimeString();

        foreach ($this->notifiable as $item) {
            $notifications[] = [
                'id' => Str::uuid()->toString(),
                'type' => $this->type,
                'notifiable_id' => $item->id,
                'notifiable_type' => get_class($item),
                'trigger_id' => $this->trigger->id,
                'trigger_type' => get_class($this->trigger),
                'data' => json_encode($this->attributes),
                'created_at' => $datetime,
                'updated_at' => $datetime,
            ];
        }

        // 写入数据
        \DB::table('official_notifications')->insert($notifications);
    }
}
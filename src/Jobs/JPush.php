<?php

namespace Tantupix\Offinotify\Jobs;

use JPush\Client;

class JPush
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(env('JPUSH_APP_KEY'), env('JPUSH_APP_SECRET'), storage_path('logs/jpush.log'));
    }

    public function getClient()
    {
        return $this->client;
    }

    public function sendMsg($userIds, $title, $body, $type, $unreadCount)
    {
        $push = $this->client->push();
        $push->setPlatform('all');
        if (empty($userIds)) {
            $push->addAllAudience();
        } else {
            $push->addAlias($userIds);
        }

        try {
            if (env('APP_ENV') === 'product') {
                $push->options(['apns_production' => true]);
            } else {
                $push->addTagAnd('测试环境');
                $push->options(['apns_production' => false]);
            }
            $style = 0;
            $bigPicPath = '';
            $alertType = -1;
            $sound = 'push_sound.caf';
            $date = now();
            $hour = $date->format('H');
            if ($hour > 23 || $hour < 8) {
                $sound = '';
                $alertType = 4;
            }
            $re = $push->setNotificationAlert($title)
                ->iosNotification(
                    ['title' => $title, 'body' => $body],
                    [
                        'sound' => $sound,
                        'badge' => $unreadCount,
                        'extras' => [
                            'push_type' => 'message_push',//或者 edit_push
                            'data' =>
                                [
                                    'msg_type' => (int)$type,
                                ]

                        ]
                    ])
                ->androidNotification(
                    $body,
                    [
                        "title" => $title,
                        //"builder_id" => 3,
                        "style" => $style, // 1,2,3
                        "alert_type" => $alertType, // -1 ~ 7
                        //"big_text"=>"big text content",
                        //"inbox"=>'',
                        "big_pic_path" => $bigPicPath,
                        //"priority"=>'0', // -2~2
                        //"category"=>"category str",
                        //"large_icon"=> "http://www.jiguang.cn/largeIcon.jpg",
                        'extras' => [
                            'push_type' => 'message_push',//或者 edit_push
                            'data' =>
                                [
                                    'msg_type' => (int)$type,
                                ]
                        ]
                    ])
                ->send();
        } catch (\Exception $e) {
            throw $e;
        }

        return true;
    }
}
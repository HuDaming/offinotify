<h1 align="center"> offinotify </h1>

<p align="center"> official notify sdk.</p>


## Installing

```shell
$ composer require tantupix/offinotify -vvv
```

## Usage

config/app.php 中添加
```
'providers' => [
    Tantupix\Offinotify\OffinotifyServiceProvider::class,
],

'aliases' => [
    'Offinotify' => Tantupix\Offinotify\OffinotifyFacade::class,
]
```

可用方法：
```
use Offinotify;
// 发送站内信
Offinotify::send($type, $notifiable, trigger, array $attributes = []);
// 站内信列表
Offinotify::notifications();
// 已读站内信列表
Offinotify::readNotifications();
// 未读站内信列表
Offinotify::unreadNotifications();
// 一键标记已读
Offinotify::markAsRead();
// 阅读一条站内信
Offinotify::show($id);
// 未读站内信数量
Offinotify::unreadCount();
// 删除已读站内信
Offinotify::destroy();
// 更新站内信内容
Offinotify::updateNotifications($class, $id, array $data = []);
```


## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/tantupix/offinotify/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/tantupix/offinotify/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT
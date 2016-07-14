yii2 multi thread
=================
yii2 multi thread with fsockopen

## 安装

推荐使用 [composer](http://getcomposer.org/download/).

```
php composer.phar require --prefer-dist kriss/yii2-thread "*"
```

或添加下面代码到`composer.json`文件

```
"kriss/yii2-thread": "*"
```

然后使用

```
php composer.phar update
```


## 使用方式

1.进行配置

basic 模版为 config/web.php, advanced 模版为对应入口的 config/main.php

示例配置如下：

```php
'components' => [
    .....
    'thread' => [
        'class' => 'kriss\thread\components\Thread',
        'enable' => true,
    ],
    ...
]
```

2.继承和改写

编写控制器来接收异步处理的链接

继承 `\kriss\thread\controllers\WebThreadController` 然后编写对应的 Action

3.使用

比如控制器下有个 Action 如下:

```php
public function actionSendMessage($message)
{
    Yii::info($message);
}
```

则在需要调用异步处理的脚本中使用：

```php
Yii::$app->thread->addThread(['/web-thread/send-message','message'=>'hello world']);
```

链接`['/web-thread/send-message','message'=>'hello world']`将会在本次请求脚本执行结束之前使用`fsockopen`方式进行访问
<?php
/**
 * User: kriss
 * Date: 2016-07-13
 * Time: 16:35
 * 基本用法：Yii::$app->thread->addThread(['/thread/send-message','message'=>json_encode(['text'=>'访问路径:'.Yii::$app->request->getAbsoluteUrl()])]);
 */

namespace kriss\thread\components;

use yii\base\Component;
use yii\helpers\Url;
use yii;

class Thread extends Component
{
    /**
     * 是否启用
     * 如果不启用用异步加载，程序将进行同步操作
     * @var bool
     */
    public $enable = true;

    /**
     * 是否开启 url 地址 token 验证
     * 开启能够防止部分 url 端口被恶意调用
     * @var bool
     */
    public $tokenValidate = true;

    /**
     * Token param
     * @var string
     */
    public $tokenParam = 'token';

    /**
     * 随意填写自己的 token
     * @var string
     */
    public $token = 'suiyitianxiezijidetoken';

    /**
     * 异步脚本的钩子，此处当前为Url链接
     * @var array
     */
    private $hooks = [];

    public function init()
    {
        parent::init();

        register_shutdown_function(function () {
            Yii::trace('start run Thread');
            $this->runThread();
            Yii::trace('end run Thread');
        });
    }

    /**
     * 添加一个线程
     * @param $url
     * @throws yii\base\ErrorException
     */
    public function addThread($url)
    {
        Yii::trace('add a thread');
        if ($this->tokenValidate) {
            if (isset($url[$this->tokenParam])) {
                Yii::error($this->tokenParam . 'can not be a key in $url parameter');
                throw new yii\base\ErrorException($this->tokenParam . 'can not be a key in $url parameter');
            }
            $url[$this->tokenParam] = $this->token;
        }
        $this->hooks[] = $url;
    }

    /**
     * 校验token
     * @param $token
     * @return bool
     */
    public function validateToken($token)
    {
        return $token == $this->token;
    }

    /**
     * 执行全部线程
     */
    public function runThread()
    {
        if ($this->enable == false) {
            foreach ($this->hooks as $hook) {
                Yii::$app->runAction(array_shift($hook), $hook);
            }
        } else {
            foreach ($this->hooks as $hook) {
                $fp = fsockopen($_SERVER['HTTP_HOST'], $_SERVER['SERVER_PORT'], $error, $errors, 3000000);
                if ($fp) {
                    $out = "GET " . Url::to($hook) . " HTTP/1.1\r\n";
                    $out .= "Host: {$_SERVER['HTTP_HOST']}\r\n";
                    $out .= "Connection: Close\r\n\r\n";
                    fputs($fp, $out);
                    fclose($fp);
                }
            }
        }
    }
}

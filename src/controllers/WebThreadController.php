<?php
/**
 * User: kriss
 * Date: 2016-07-13
 * Time: 16:35
 */

namespace kriss\thread\controllers;

use kriss\thread\components\Thread;
use yii\web\Controller;
use yii;

class WebThreadController extends Controller
{
    public function beforeAction($action)
    {
        Yii::trace('start thread');
        /** @var Thread $thread */
        $thread = Yii::$app->thread;
        if ($thread->tokenValidate) {
            $urlToken = Yii::$app->request->get($thread->tokenParam);
            if (!$thread->validateToken($urlToken)) {
                throw new yii\web\ForbiddenHttpException('拒绝访问');
            }
        }
        return parent::beforeAction($action);
    }
}

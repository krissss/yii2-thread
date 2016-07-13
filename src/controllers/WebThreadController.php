<?php
/**
 * User: kriss
 * Date: 2016-07-13
 * Time: 16:35
 */

namespace kriss\thread\controllers;

use yii\web\Controller;
use yii;

class WebThreadController extends Controller
{
    public function beforeAction($action)
    {
        Yii::info('start thread');
        parent::beforeAction($action);
    }
}
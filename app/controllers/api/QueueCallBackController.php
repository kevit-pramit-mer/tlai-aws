<?php

namespace app\controllers\api;

use Yii;
use yii\rest\ActiveController;


class QueueCallBackController extends ActiveController
{

    public function actionQueueCall()
    {
        echo 'hello';
        exit();

        $data = Yii::$app->request->post();
        print_r($data);
        exit();

    }
}

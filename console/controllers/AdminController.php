<?php

namespace console\controllers;
use common\models\Admin;
use yii\console\Controller;
class AdminController extends Controller
{
    public function actionGenerator($username, $password)
    {
        $user = new Admin();
        $user->username = $username;
        $user->email = '';
        $user->updated_at = $user->created_at = time();
        $user->setPassword($password);
        $user->generateAuthKey();
        return $user->save(false);
    }

}

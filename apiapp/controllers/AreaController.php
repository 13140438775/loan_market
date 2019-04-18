<?php
namespace app\controllers;

use app\services\AreaService;

class AreaController extends BaseController
{

    /**
     * 获取地区
     * @return mixed
     */
    public function actionIndex(){
        $parentid = \Yii::$app->request->get('parentid','001');

        return AreaService::getInstance()->areaList($parentid);
    }
}

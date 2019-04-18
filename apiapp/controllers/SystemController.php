<?php
namespace app\controllers;

use common\components\job\SystemJob;
use common\exceptions\BaseException;

/**
 * 异步队列请求
 * Class SystemController
 * @package api\controllers
 */
class SystemController extends BaseController
{
    /**
     *
     * @var array
     */
    private $typeList = [
        'addressBook' => '通讯录',
        'appList' => 'APP列表',
        'callHistory' => '本地通讯记录',
        'deviceInfo' => '设备信息',
    ];

    public function actionIndex(){
        $data = \Yii::$app->request->post('data');
        $type = \Yii::$app->request->post('type');

        try{
            \Yii::$app->queue->push(new SystemJob(['method' => $type, 'data' => $data,]));
        }catch (\Exception $e){
            \Yii::error("同步{$this->typeList[$type]}失败, error: {$e->getMessage()}");
            throw new BaseException();
        }

    }
}

<?php

namespace common\components\job;

use yii\base\BaseObject;
use yii\queue\Queue;
use common\helpers\Helper;
use open\exceptions\BaseException;

class SystemJob extends BaseObject implements \yii\queue\JobInterface
{

    public $method;
    public $data;

    public function __construct($array){
        $this->method = $array['type'];
        $this->data = $array['data'];
    }
    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        $action = $this->method;
        $this->$action($this->data);
        // TODO: Implement execute() method.
    }


    //TODO 缺少 System方法内 所有消费者
    /**
     * callHistory 上传本地通话记录
     * @date     2019/3/13 17:56
     * @author   周晓坤<1426801685@qq.com>
     * @param $data
     */
    public function callHistory($data)
    {
        Helper::apiCurl(Helper::getApiUrl('callHistory', 'javaApiSecond'), 'POST', $data, [], 'json');
    }

    /**
     * addressBook 上传通讯录
     * @date     2019/3/13 17:55
     * @author   周晓坤<1426801685@qq.com>
     * @param $data
     */
    public function addressBook($data)
    {
        Helper::apiCurl(Helper::getApiUrl('addressBook', 'javaApiSecond'), 'POST', $data, [], 'json');
    }

    /**
     * addressBook 上传app列表
     * @date     2019/3/13 17:57
     * @author   周晓坤<1426801685@qq.com>
     * @param $data
     */
    public function appList($data)
    {
        Helper::apiCurl(Helper::getApiUrl('appList', 'javaApiSecond'), 'POST', $data, [], 'json');
    }

    /**
     * addressBook 上传设备信息
     * @date     2019/3/13 17:59
     * @author   周晓坤<1426801685@qq.com>
     * @param $data
     */
    public function deviceInfo($data)
    {
        Helper::apiCurl(Helper::getApiUrl('deviceInfo', 'javaApiSecond'), 'POST', $data, [], 'json');
    }

}
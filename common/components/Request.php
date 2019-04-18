<?php

namespace common\components;

use Yii;

/**
 * Class Request
 * @package common\components
 */
class Request extends \yii\web\Request
{
    /**
     * get request info
     *
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @CreateTime 2018/9/11 15:13:46
     * @Author     : pb@likingfit.com
     */
    public function getInfo()
    {
        return [
            'path_info' => $this->getPathInfo(),
            'method'    => $this->getMethod(),
            'header'    => $this->getHeaders()
                ->toArray(),
            'get'       => $this->get(),
            'post'      => $this->post()
        ];
    }

    /**
     * 文件描述 get request unique id
     * Created On 2019-01-22 09:45
     * Created By heyafei
     * @param string $prefix
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getUniqueId($prefix = '')
    {
        $id = md5(json_encode([
            $this->getPathInfo(),
            $this->getMethod(),
            $this->get(),
            $this->post()
        ]));

        return Yii::$app->id . ":{$prefix}:" . $id;
    }
}
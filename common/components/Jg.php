<?php
/**
 * ${FILE_NMAE}.
 * 文件描述 极光推送组件
 * Created On 2019-03-06 15:16
 * Created By heyafei
 */

namespace common\components;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use JPush\Client;

class Jg extends Component {
    private $_client;
    private $_pushPayload;

    /**
     * 极光日志文件
     * @var
     */
    public $logPath;
    /**
     * 极光密钥配置
     * @var
     */
    public $keyConfig;

    /**
     * 设置极光推送客户端
     * @param $app
     * @return $this
     */
    public function setClient($app) {
        $conf = ArrayHelper::getValue($this->keyConfig, "{$app}.".YII_ENV, []);

        if (!empty($conf)) {
            $this->_client = new Client($conf['appkey'], $conf['masterSecret'], Yii::getAlias($this->logPath));
            $this->_pushPayload = $this->_client->push();
        }

        return $this;
    }

    /**
     * 消息推送
     * @param $registrationIds 设备ids
     * @param $alert    标题
     * @param $extras   扩展数据
     *
     * @return $this
     * @CreateTime 2018/5/7 17:14:24
     * @Author     : pb@likingfit.com
     */
    public function push($registrationIds, $alert, $extras){
        if (is_null($this->_client) || empty($registrationIds)) {
            return $this;
        }

        $this->_pushPayload
            ->setPlatform(['ios', 'android'])
            ->addRegistrationId($registrationIds)
            ->iosNotification($alert, ['extras'=>$extras])
            ->androidNotification($alert, ['extras'=>$extras])
            ->send();
    }
}
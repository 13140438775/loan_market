<?php

namespace common\services;

use common\exceptions\StatsDataException;
use common\models\ChannelData;
use common\models\Channels;
use common\models\LoanLoginLog;
use common\models\LoanUsers;
use yii\base\Component;
use yii\db\Expression;

class StatsChannelDataService extends Component
{
    private $redis;
    const EXPIRE = 60 * 60 * 24 * 2; // key过期时间
    const REDIS_PREFIX = "loan_market"; // 项目开头

    public function __construct(array $config = [])
    {
        $this->redis = \Yii::$app->redis;
        parent::__construct($config);
    }

    // 1.统计每天每个渠道的UV
    private function setChannelUv($channel_id, $date)
    {
        return self::REDIS_PREFIX . "_" . $date . "_channel_uv_" . $channel_id;
    }

    // 2.统计每天每个渠道的PV
    private function setChannelPv($channel_id, $date)
    {
        return self::REDIS_PREFIX . "_" . $date . "_channel_pv_" . $channel_id;
    }

    // 3.统计每天每个渠道的IP
    private function setChannelIp($channel_id, $date)
    {
        return self::REDIS_PREFIX . "_" . $date . "_channel_ip_" . $channel_id;
    }

    // 4.统计每天的渠道ID
    private function setChannelId($date)
    {
        return self::REDIS_PREFIX. "_" .$date. "_channel_id";
    }

    // 5.设置过期时间
    private function setExpire($key)
    {
        return $this->redis->executeCommand("EXPIRE", [$key, self::EXPIRE]);
    }


    /**
     * 文件描述 存数据到redis
     * Created On 2019-02-22 21:17
     * Created By heyafei
     * @param $channel_id
     * @param $unique_user
     * @param $ip
     */
    public function setChannelRedis($channel_id, $unique_user, $ip)
    {
        $date = date("Ymd");
        // 1.统计UV
        $this->redis->executeCommand("SADD", [$this->setChannelUv($channel_id, $date), $unique_user]);
        $this->setExpire($this->setChannelUv($channel_id, $date));

        // 2.统计PV
        $this->redis->executeCommand("INCR", [$this->setChannelPv($channel_id, $date)]);
        $this->setExpire($this->setChannelPv($channel_id, $date));

        // 3.统计IP
        $this->redis->executeCommand("SADD", [$this->setChannelIp($channel_id, $date), $ip]);
        $this->setExpire($this->setChannelIp($channel_id, $date));

        // 4.统计channel_id数量
        $this->redis->executeCommand("SADD", [$this->setChannelId($date), $channel_id]);
        $this->setExpire($this->setChannelId($date));
    }

    /**
     * 文件描述 获取channel_ids
     * Created On 2019-02-25 10:32
     * Created By heyafei
     * @param $date
     */
    public function getChannelIds($date)
    {
        $channel_ids = $this->redis->executeCommand("SMEMBERS", [$this->setChannelId($date)]);
        foreach ($channel_ids AS $channel_id) {
            $this->saveChannelRedisData($channel_id, $date);
        }
    }

    /**
     * 文件描述 保存昨天的渠道数据
     * Created On 2019-02-25 11:14
     * Created By heyafei
     * @param $channel_id
     * @param $date
     * @return bool
     */
    public function saveChannelRedisData($channel_id, $date)
    {
        $uv_data = $this->redis->executeCommand("SCARD", [$this->setChannelUv($channel_id, $date)]);
        $pv_data = $this->redis->executeCommand("GET", [$this->setChannelPv($channel_id, $date)]);
        $ip_data = $this->redis->executeCommand("SCARD", [$this->setChannelIp($channel_id, $date)]);

        $uv_data = $uv_data ?? 0;
        $pv_data = $pv_data ?? 0;
        $ip_data = $ip_data ?? 0;
        try {
            $model = ChannelData::findOne(['channel_id' => $channel_id, 'date' => $date]);
            if ($model) {
                $model->uv_data = $uv_data;
                $model->pv_data = $pv_data;
                $model->ip_data = $ip_data;
            } else {
                $model = new ChannelData();
                $model->channel_id = $channel_id;
                $model->uv_data = $uv_data;
                $model->pv_data = $pv_data;
                $model->ip_data = $ip_data;
                $model->date = $date;
            }
            if(!$model->save()) {
                throw new StatsDataException(StatsDataException::SAVE_CHANNEL_DATA_FAIL);
            }
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }





    /**
     * 文件描述 数据收集
     * Created On 2019-02-21 14:26
     * Created By heyafei
     * @param $date
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function statsChannelData($date)
    {
        $date_begin = date("Y-m-d H:i:s", strtotime($date));
        $date_end = date("Y-m-d H:i:s", strtotime($date) + 24 * 60 * 60);

        // 1.统计注册数据
        $select = [
            'channel_id' => 'channel_id',
            'register_count' => new Expression("COUNT(id)"),
        ];

        $register_data = LoanUsers::find()->select($select)
            ->where([">=", 'create_time', $date_begin])
            ->andWhere(["<=", 'create_time', $date_end])
            ->andWhere(['>', "channel_id", 0])
            ->groupBy("channel_id")
            ->asArray()
            ->all();
        // 2.统计登陆数
        $select = [
            'channel_id' => 'loan_users.channel_id',
            'login_count' => new Expression("COUNT(loan_login_log.id)"),
        ];

        $login_data = LoanLoginLog::find()
            ->select($select)
            ->innerJoin("loan_users", "loan_users.id = loan_login_log.user_id")
            ->where([">=", 'loan_login_log.create_time', $date_begin])
            ->andWhere(["<=", 'loan_login_log.create_time', $date_end])
            ->andWhere(['>', "loan_users.channel_id", 0])
            ->groupBy("channel_id")
            ->indexBy("channel_id")
            ->asArray()
            ->all();

        foreach ($register_data AS &$val) {
            $val['login_count'] = isset($login_data[$val['channel_id']]) ? $login_data[$val['channel_id']]['login_count'] : 0;
        }
        return $register_data;

    }

    /**
     * 文件描述 数据落地
     * Created On 2019-02-21 14:26
     * Created By heyafei
     * @param $date
     * @return int
     * @throws \yii\db\Exception
     */
    public static function saveChannelData($date)
    {
        $date = date("Ymd", strtotime($date));
        $channel_data = self::statsChannelData($date);

        $data = [];
        foreach ($channel_data AS $val) {
            $data[] = [
                "channel_id" => $val['channel_id'],
                "login_data" => $val['login_count'],
                "register_data" => $val['register_count'],
                "date" => $date,
                "created_at" => time(),
                "updated_at" => time(),
            ];
        }

        if (empty($data)) {
            return 0;
        }
        $field = array_keys(reset($data));
        return ChannelData::find()->createCommand()
            ->batchInsert(ChannelData::tableName(), $field, $data)
            ->execute();
    }

    /**
     * 文件描述
     * Created On 2019-03-04 10:49
     * Created By heyafei
     * @param $channel_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public static function getH5Url($channel_id)
    {
        $html_manage = Channels::find()->select("html_manage.*")
            ->innerJoin("html_manage", "html_manage.id = channels.template_id")
            ->where(['channels.channel_id' => $channel_id])
            ->asArray()
            ->one();
        return $html_manage['url'];
    }
}

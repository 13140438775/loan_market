<?php

namespace common\services;

use common\exceptions\StatsDataException;
use common\exceptions\UserException;
use common\models\AppDailyData;
use common\models\AppUser;
use common\models\ProductDailyData;
use common\models\UserDailyData;
use common\models\UserProductPvLog;
use Yii;
use common\models\Apps;
use yii\base\Component;
use common\models\CreditProduct;
use yii\redis\Connection;


class DataCollectService extends Component
{
    /** @var Connection $redis */
    private $redis;
    const  REDIS_PREFIX = 'loan_market';
    const  EXPIRE = 86400 * 2;

    public function __construct(array $config = [])
    {
        $this->redis = \Yii::$app->redis;
        parent::__construct($config);
    }

    /*UV key 某天产品在app的UV 集合存user_id*/
    private function getAppProductUvSetKey($appId, $productId, $date)
    {
        return self::REDIS_PREFIX . $date . '_UV_' . $appId . '_' . $productId . '_set';
    }

    /*UV key 某天产品的UV集合存user_id*/
    private function getProductUvSetKey($productId, $date)
    {
        return self::REDIS_PREFIX . $date . '_UV_' . $productId . '_set';
    }


    /*PV key 每次+1*/
    private function getAppProductPvKey($appId, $productId, $date)
    {
        return self::REDIS_PREFIX . $date . '_PV_' . $appId . '_' . $productId;
    }

    //一个用户每天访问项目数量
    private function getUserDailyAccessProductSetKey($appId, $userId, $date)
    {
        return self::REDIS_PREFIX . $date . '_user_access_product_' . $appId . '_' . $userId . '_set';
    }

    //每个app 一天访问渠道的用户数
    private function getAppUvKey($appId, $date)
    {
        return self::REDIS_PREFIX . $date . '_app_UV_' . $appId . '_user_set';
    }

    //每个app 一天访问渠道的次数
    private function getAppPvKey($appId, $date)
    {
        return self::REDIS_PREFIX . $date . '_app_PV_' . $appId;
    }

    /**
     * 收集pv pv到redis
     * collect
     * @date     2019/1/30 3:00 PM
     * @author   Wei Yang<suncode_666@163.com>
     * @param CreditProduct $product
     * @param $userId
     * @param Apps $app
     * @return bool
     * @throws UserException
     */
    public function collect(CreditProduct $product, $userId, Apps $app)
    {
        $today = date('Ymd');
        //先存储用户
        $this->saveUser($app->id, $userId);
        $isSuccess = 0;
        $isSuccess += $this->storeAppProductUvData($this->getAppProductUvSetKey($app->id, $product->id, $today), $userId);
        $isSuccess += $this->storeProductUvData($this->getProductUvSetKey($product->id, $today), $userId);
        $isSuccess += $this->addAppProductPv($this->getAppProductPvKey($app->id, $product->id, $today));
        $isSuccess += $this->storeUserDailyAccessProductSet($this->getUserDailyAccessProductSetKey($app->id, $userId, $today), $product->id);
        $isSuccess += $this->storeAppUvUserSet($this->getAppUvKey($app->id, $today), $userId);
        $isSuccess += $this->addAppPv($this->getAppPvKey($app->id, $today));


        return $isSuccess === 6;
    }

    public function doLog($user_id, $product_id)
    {
        $log = new UserProductPvLog();
        $log->user_id = $user_id;
        $log->product_id = $product_id;
        $log->created_time = time();
        $log->created_date = date('Ymd');
        $log->save();
    }

    //获取产品今天天的Uv
    public function getTodayProductUv($productId)
    {

        $date = date('Ymd');
        $dbUv = ProductDailyData::find()->where(['product_id' => $productId])->sum('uv');
        if (date('Ymd') === $date) {//如果是今天读数据库+缓存
            $key = $this->getProductUvSetKey($productId, $date);
            try {
                $uv = $this->redis->executeCommand('SCARD', [$key]);
            } catch (\Exception $e) {
                Yii::error($this->formatRedisError('SCARD', $key, '', $e->getMessage()));
                return false;
            }
            return $uv + $dbUv;
        } else {
            return $dbUv;
        }

    }

    /**
     * 将redis里的某天运营数据落地到数据库
     * saveSomeDayDataToDb
     * @date     2019/1/30 3:06 PM
     * @author   Wei Yang<suncode_666@163.com>
     * @param $date
     */
    public function saveSomeDayDataToDb($date)
    {
        $apps = Apps::find()->select('id')->all();
        $products = CreditProduct::find()->select('id')->all();
        foreach ($apps as $app) {
            $this->saveAppUvPvData($app->id, $date);
            //产品维度罗库
            foreach ($products as $product) {
                $this->saveAppProductUvPvDataToDb($app->id, $product->id, $date);
            }
            //分页
            $count = AppUser::find()->count();
            if ($count > 0) {
                $limit = 1000;
                $pageCount = ceil($count / $limit);
                foreach (range(1, $pageCount) as $i) {
                    $users = AppUser::find()->select('user_id')->where(['app_id' => $app->id])->limit($limit)->offset(($i - 1) * $limit)->all();
                    foreach ($users as $user) {
                        $this->saveUserAccessProductCountToDb($app->id, $user->user_id, $date);
                    }
                }
            }
        }
    }

    public function deleteSomeDayCache($date)
    {
        $data = $this->redis->executeCommand('KEYS', [self::REDIS_PREFIX . $date . '_user_access_product_*']);
        foreach ($data as $datum) {
            $this->redis->executeCommand('DEL', $datum);
        }
    }

    private function saveUser($app_id, $user_id)
    {
        if (!AppUser::findOne(['app_id' => $app_id, 'user_id' => $user_id])) {
            $appUser = new AppUser();
            $appUser->user_id = $user_id;
            $appUser->app_id = $app_id;
            $appUser->created_at = time();
            if ($appUser->save() === false) {
                throw new UserException(UserException::USER_STORE_FAIL);
            }
        }
    }

    //保存某天 某app内product的user_id
    private function storeAppProductUvData($key, $user_id)
    {
        try {
            $this->redis->executeCommand('SADD', [$key, $user_id]);
            $this->setExpire($key);
        } catch (\Exception $e) {
            Yii::error($this->formatRedisError('SADD', $key, $user_id, $e->getMessage()));
            return false;
        }
        return true;
    }

    //保存某天 product的user_id
    private function storeProductUvData($key, $user_id)
    {
        try {
            $this->redis->executeCommand('SADD', [$key, $user_id]);
            $this->setExpire($key);
        } catch (\Exception $e) {
            Yii::error($this->formatRedisError('SADD', $key, $user_id, $e->getMessage()));
            return false;
        }
        return true;
    }

    private function setExpire($key)
    {
        $this->redis->executeCommand('EXPIRE', [$key, self::EXPIRE]);
    }

    private function addAppProductPv($key)
    {
        try {
            $this->redis->executeCommand('INCR', [$key]);
            $this->setExpire($key);
        } catch (\Exception $e) {
            Yii::error($this->formatRedisError('INCR', $key, '1', $e->getMessage()));
            return false;
        }
        return true;
    }

    private function storeUserDailyAccessProductSet($key, $product_id)
    {
        try {
            $this->redis->executeCommand('SADD', [$key, $product_id]);
            $this->setExpire($key);
        } catch (\Exception $e) {
            Yii::error($this->formatRedisError('SADD', $key, $product_id, $e->getMessage()));
            return false;
        }
        return true;
    }

    private function storeAppUvUserSet($key, $user_id)
    {
        try {
            $this->redis->executeCommand('SADD', [$key, $user_id]);
            $this->setExpire($key);
        } catch (\Exception $e) {
            Yii::error($this->formatRedisError('SADD', $key, $user_id, $e->getMessage()));
            return false;
        }
        return true;
    }

    private function addAppPv($key)
    {
        try {
            $this->redis->executeCommand('INCR', [$key]);
            $this->setExpire($key);
        } catch (\Exception $e) {
            Yii::error($this->formatRedisError('INCR', $key, '1', $e->getMessage()));
            return false;
        }
        return true;
    }


    private function formatRedisError($action, $key, $value, $errorMessage)
    {
        return 'redis 异常 操作:' . $action . ' ' . $key . ' ' . $value . ' error-info:' . $errorMessage();
    }


    //UV PV 落地数据库
    private function saveAppProductUvPvDataToDb($appId, $productId, $date)
    {
        $uvKey = $this->getAppProductUvSetKey($appId, $productId, $date);
        $pvKey = $this->getAppProductPvKey($appId, $productId, $date);
        try {
            $uv = $this->redis->executeCommand('SCARD', [$uvKey]);
            $pv = $this->redis->executeCommand('GET', [$pvKey]);
            $uv = $uv ?? 0;
            $pv = $pv ?? 0;
            $model = ProductDailyData::find()->where(['product_id' => $productId, 'app_id' => $appId, 'date' => $date])->one();
            if (!$model) {
                $model = new ProductDailyData();
                $model->product_id = $productId;
                $model->app_id = $appId;
                $model->uv = $uv;
                $model->pv = $pv;
                $model->date = $date;
                $model->created_at = time();
            } else {
                $model->uv = $uv;
                $model->pv = $pv;
            }
            if (false == $model->save()) {
                throw new StatsDataException(StatsDataException::STORE_APP_PRODUCT_PV_TO_DB_FAIL);
            }
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    //app 访问通知落地数据库
    private function saveAppUvPvData($appId, $date)
    {
        $uvKey = $this->getAppUvKey($appId, $date);
        $pvKey = $this->getAppPvKey($appId, $date);

        try {
            $uv = $this->redis->executeCommand('SCARD', [$uvKey]);
            $pv = $this->redis->executeCommand('GET', [$pvKey]);
            $uv = $uv ?? 0;
            $pv = $pv ?? 0;
            $model = AppDailyData::find()->where(['app_id' => $appId, 'date' => $date])->one();
            if (!$model) {
                $model = new AppDailyData();
                $model->app_id = $appId;
                $model->uv = $uv;
                $model->pv = $pv;
                $model->date = $date;
                $model->created_at = time();
            } else {
                $model->uv = $uv;
                $model->pv = $pv;
            }
            if (false == $model->save()) {
                throw new StatsDataException(StatsDataException::STORE_APP_PV_TO_DB_FAIL);
            }
        } catch (\Exception $e) {
            Yii::error(StatsDataException::$reasons[StatsDataException::STORE_APP_PV_TO_DB_FAIL] . $e->getMessage());
            return false;
        }
        return true;
    }

    private function saveUserAccessProductCountToDb($appId, $userId, $date)
    {
        $key = $this->getUserDailyAccessProductSetKey($appId, $userId, $date);
        try {
            $uv = $this->redis->executeCommand('SCARD', [$key]);
            $uv = $uv ?? 0;
            if ($uv == 0) {
                return true;
            }
            $model = UserDailyData::find()->where(['app_id' => $appId, 'date' => $date, 'user_id' => $userId])->one();
            if (!$model) {
                $model = new UserDailyData();
                $model->app_id = $appId;
                $model->user_id = $userId;
                $model->product_num = $uv;
                $model->date = $date;
                $model->created_at = time();
            } else {
                $model->product_num = $uv;
            }
            if (false == $model->save()) {
                throw new StatsDataException(StatsDataException::STORE_USER_DAILY_DB_FAIL);
            }
        } catch (\Exception $e) {
            Yii::error(StatsDataException::$reasons[StatsDataException::STORE_USER_DAILY_DB_FAIL] . $e->getMessage());
            return false;
        }
        return true;

    }


}
<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');
Yii::setAlias('@apiapp', dirname(dirname(__DIR__)) . '/apiapp');
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@open', dirname(dirname(__DIR__)) . '/open');

//服务
\Yii::$container->set('DataCollectService', 'common\services\DataCollectService');
\Yii::$container->set('StatsChannelDataService', 'common\services\StatsChannelDataService');
\Yii::$container->set('UvPvService', 'common\services\UvPvService');
\Yii::$container->set('OrderStatusNoticeService', 'common\services\OrderStatusNoticeService');


\Yii::$container->set('RedisPrefixService', 'common\services\RedisPrefixService');

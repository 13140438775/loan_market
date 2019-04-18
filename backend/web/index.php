<?php
$env = get_cfg_var('env');
$env = $env ? $env : 'dev';
defined('YII_ENV') or define('YII_ENV', $env);
defined('YII_DEBUG') or define('YII_DEBUG', $env == 'prod' ? true : true);


require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/main.php',//全局配置
    require __DIR__ . '/../../environment/'.YII_ENV.'/common/config/main-local.php',//环境参数配置
    require __DIR__ . '/../config/main.php', //backend的main配置 组件行为美化配置 加载backend本地参数
    require __DIR__ . '/../config/main-local.php'//gii配置
);

(new yii\web\Application($config))->run();

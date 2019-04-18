<?php

$env = get_cfg_var('env');
$env = $env ? $env : 'dev';
defined('YII_ENV') or define('YII_ENV', $env);
defined('YII_DEBUG') or define('YII_DEBUG', $env == 'prod' ? false : true);


require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/main.php',
    require __DIR__ . '/../config/main.php',
    require(__DIR__ . '/../../environment/' . YII_ENV . '/common/config/main-local.php')
);
if(YII_ENV === 'dev'){
    $__http_origin = $_SERVER['HTTP_ORIGIN'] ?? null;
    $__http_origin && header("Access-Control-Allow-Origin: $__http_origin");
    $__http_origin && header('Access-Control-Allow-Credentials: true');
}

//var_dump($config);die;
(new yii\web\Application($config))->run();


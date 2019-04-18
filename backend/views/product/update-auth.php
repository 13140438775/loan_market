<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $authConfigstring */



$this->title = '认证项配置';
$this->params['breadcrumbs'][] = ['label' => '产品列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mk-product-create">

    <?= $this->render('_form-auth', [
        'model' => $model,
        'authConfig' => $authConfig
    ]) ?>

</div>

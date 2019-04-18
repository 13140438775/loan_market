<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ProductApiConfig */

$this->title = '接口配置' . $model->product_id;
$this->params['breadcrumbs'][] = ['label' => 'Product Api Configs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-api-config-update">

    <?= $this->render('_form-api-config', [
        'model' => $model,
    ]) ?>

</div>


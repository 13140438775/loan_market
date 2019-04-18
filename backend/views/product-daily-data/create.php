<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ProductDailyData */

$this->title = 'Create Product Daily Data';
$this->params['breadcrumbs'][] = ['label' => 'Product Daily Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-daily-data-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AppTabs */

$this->title = 'Update App Tabs: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'App Tabs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="app-tabs-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AppTabs */

$this->title = 'Create App Tabs';
$this->params['breadcrumbs'][] = ['label' => 'App Tabs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-tabs-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

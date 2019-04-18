<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ChannelConfig */

$this->title = 'Update Channel Config: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Channel Configs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="channel-config-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

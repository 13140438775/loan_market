<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ChannelConfig */

$this->title = 'Create Channel Config';
$this->params['breadcrumbs'][] = ['label' => 'Channel Configs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="channel-config-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>

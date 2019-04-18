<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ChannelData */

$this->title = 'Create Channel Data';
$this->params['breadcrumbs'][] = ['label' => 'Channel Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="channel-data-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

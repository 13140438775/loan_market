<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\HandFillTerm */

$this->title = 'Update Hand Fill Term: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Hand Fill Terms', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="hand-fill-term-update">

    <?= $this->render('_form', [
        'model' => $model,
        'jsonString' => $jsonString

    ]) ?>

</div>

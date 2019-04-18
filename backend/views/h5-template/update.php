<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\H5Template */

$this->title = 'Update H5 Template: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'H5 Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="h5-template-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

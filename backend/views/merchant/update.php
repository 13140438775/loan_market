<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Merchant */

$this->title = 'Update Merchant: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Merchants', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="merchant-update">

    <?= $this->render('_form', [
        'model' => $model,'contactModels' => $contactModels
    ]) ?>

</div>

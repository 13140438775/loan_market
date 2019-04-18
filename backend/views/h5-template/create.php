<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\H5Template */

$this->title = 'Create H5 Template';
$this->params['breadcrumbs'][] = ['label' => 'H5 Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="h5-template-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>

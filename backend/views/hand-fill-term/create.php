<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\HandFillTerm */

$this->title = '添加手填项';
$this->params['breadcrumbs'][] = ['label' => 'Hand Fill Terms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hand-fill-term-create">

    <?= $this->render('_form', [
    'model' => $model,
    'jsonString' => $jsonString
    ]) ?>

</div>

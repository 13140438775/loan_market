<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Merchant */

$this->title = 'Create Merchant';
$this->params['breadcrumbs'][] = ['label' => 'Merchants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="merchant-create">

    <?= $this->render('_form', [
    'model' => $model,'contactModels' => $contactModels
    ]) ?>

</div>

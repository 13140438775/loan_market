<?php

use yii\helpers\Html;


/* @var $model common\models\Product */
/* @var $allTerms string */
/* @var $selectedTerms string */
/* @var $allCareerTerms string */
/* @var $selectedCareer string */


$this->title = '添加Api对接产品';
$this->params['breadcrumbs'][] = ['label' => '产品列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mk-product-create">

    <?= $this->render('_form-hand-fill-config', [
        'model' => $model,
        'allTerms' => $allTerms,
        'selectedTerms' => $selectedTerms,
        'allCareerTerms' => $allCareerTerms,
        'selectedCareer' => $selectedCareer
    ]) ?>

</div>

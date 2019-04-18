<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BannerInfo */

$this->title = 'Update Banner Info: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Banner Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="banner-info-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\BannerInfo */

$this->title = 'Create Banner Info';
$this->params['breadcrumbs'][] = ['label' => 'Banner Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-info-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>

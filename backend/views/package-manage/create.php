<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PackageVersions */

$this->title = '上传文件';
$this->params['breadcrumbs'][] = ['label' => 'Package Versions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="package-versions-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

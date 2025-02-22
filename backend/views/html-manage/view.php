<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\HtmlManage */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Html Manages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="html-manage-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'attribute' => 'url',
                'format' => 'raw',
                'value' => function($model){
                    return Html::img(\Yii::$app->params['oss']['url_prefix'].$model->url, ['height' =>50, 'width' => 50]);
                }
            ],
            'param',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>

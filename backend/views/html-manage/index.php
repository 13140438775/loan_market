<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\HtmlManageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'H5模板管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="html-manage-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
//            'url:url',
            [
                'attribute' => 'url',
                'format' => 'raw',
                'value' => function($model){
                    return Html::img(\Yii::$app->params['oss']['url_prefix'].$model->url, ['height' =>50, 'width' => 50]);
                }
            ],
//            'param',
//            'pic_id',
            //'created_at',
            //'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update}  {delete}',
                'buttons' => [
                    'update' => function ($url) {
                        return Html::a('<span class="glyphicon">编辑</span>', $url);
                    },
                ],
            ],
        ],
    ]); ?>
</div>

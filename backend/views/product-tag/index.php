<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductTagSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '标签列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-tag-index">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加标签', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            'sort',
            'id',
            'tag_name',
            [
                'attribute' => 'is_enable',
                'value' => function($model){
                    return \common\models\ProductTag::$is_enable_set[$model->is_enable];
                }
            ],
            [
                'attribute' => 'tag_icon',
                'format' => 'raw',
                'value' => function($model){
                    return Html::img(\Yii::$app->params['oss']['url_prefix'].$model->tag_icon, ['height' =>50, 'width' => 50]);
                }
            ],
            [
                'attribute' => 'tag_img',
                'format' => 'raw',
                'value' => function($model){
                    return Html::img(\Yii::$app->params['oss']['url_prefix'].$model->tag_img, ['height' =>50, 'width' => 50]);
                }
            ],
//            'tag_img',
            //'sort',
            //'is_valid',
            //'created_at',
//            'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update}',
                'buttons' => [
                   'update' => function ($url) {
                        return Html::a('<span class="glyphicon">编辑</span>', $url);
                   },
                ],
            ],
        ],
    ]); ?>
</div>

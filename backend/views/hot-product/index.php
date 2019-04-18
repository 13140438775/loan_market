<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\HotProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '热门贷款';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hot-product-index">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加热门贷款', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            'sort',
            'product_id',
            [
                'attribute' => 'logo',
                'format' => 'raw',
                'value' => function($model){
                    return Html::img(\Yii::$app->params['oss']['url_prefix'].$model->product->logo_url, ['height' =>50, 'width' => 50]);
                }
            ],
            [
                'attribute' => 'product_name',
                'value' => function($model){
                    return $model->product->product_name;
                }
            ],
            //'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update}  {delete}',
                'buttons' => [
                    'update' => function ($url) {
                        return Html::a('<span class="glyphicon">编辑</span>', $url);
                    },
                ],
            ]
        ],
    ]); ?>
</div>

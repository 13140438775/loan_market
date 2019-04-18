<?php

use yii\helpers\Html;
use yii\grid\GridView;
use supplyhog\ClipboardJs\ClipboardJsWidget;
use common\services\StatsChannelDataService;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ChannelsManageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '渠道管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="channels-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => '序号'
            ],
            [
                'attribute' => 'channel_id',
                'label' => '渠道ID'
            ],
            'channel_name',
//            'merchant_id',
            [
                'attribute' => 'merchant_id',
                'value' => function($model){
                    return "用钱金卡";
                }
            ],
            [
                'attribute' => 'type',
                'value' => function($model){
                    return $model->market_type_set[$model->type];
                }
            ],
            [
                'attribute' => 'cooperation',
                'value' => function($model){
                    return $model->cooperation_set[$model->cooperation];
                }
            ],
            [
                'attribute' => 'created_id',
                'value' => function($model) {
                    return $model->createdInteralUser->username;
                }
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y年/m月/d日 H:i'],
            ],
            [
                'attribute' => 'updated_id',
                'value' => function($model) {
                    return $model->updatedInteralUser->username;
                }
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:Y年/m月/d日 H:i'],
            ],
//            [
//                'attribute' => 'short_url',
//                'label' => '注册页',
//                'format' => 'raw',
//                'value' => function ($model) {
//                    return "
//                <textarea>".$model->short_url."</textarea>
//                <a href='javascript:;' onclick='copyShortUrl()'>复制链接</a>";
//                }
//            ],
            //'is_filling',
            //'is_company_name',
            //'template_id',
            //'created_at',
            //'updated_at',
            //'created_id',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{copy}',
                'buttons' => [
                    'update' => function ($url) {
                        return Html::a('<span class="btn-clipboard">编辑</span>', $url);
                    },
                    'copy' => function($url, $model, $key){
                        return ClipboardJsWidget::widget([
                            'text' => Yii::$app->params['promotion_url_prefix'].$model->channel_id."&h5_url=".urlencode(Yii::$app->params['oss']['url_prefix'].StatsChannelDataService::getH5Url($model->channel_id)),
                            'label' => '复制链接',
                            'htmlOptions' => ['class' => 'btn-clipboard'],
                        ]);
                    },
                ]
            ],
        ],
    ]); ?>
</div>
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\bootstrap\Modal;
use \yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '运营配置';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mk-product-index box box-primary">
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'id',
                    'value' => 'id',
                ],
                [
                    'attribute' => '产品名称',
                    'value' => 'name',
                ],
//                'merchant_id',
//                'show_name',
                [
                    'attribute' => '图标',
                    'format' => 'raw',
                    'value' => function($model){
                        return Html::img(\Yii::$app->params['oss']['url_prefix'].$model->logo_url, ['height' =>50, 'width' => 50]);
                    }
                ],
                [
                    'attribute' => '产品类型',
                    'value' => function($model){
                        return \common\models\Product::$call_type_set[$model->call_type];
                    }
                ],
                [
                    'attribute' => '属性',
                    'value' => function($model){
                        return \common\models\Product::$product_type_set[$model->product_type];
                    }
                ],
//                [
//                    'attribute' => '在线APP',
//                    'value' => function($model){
//                        return '用钱金卡';
//                    }
//                ],
                [
                    'attribute' => '在线场景',
                    'value' => function($model){
                        $checkList = [];
                        foreach (\common\models\Product::$scenario_set as $k => $v){
                            if((strval($model->online_scenario) & strval($k)) === strval($k)){
                                if($k != 1000000000){
                                    $checkList[] = \common\models\Product::$scenario_set[strval($k)];
                                }
                            }
                        }
                        if(count($checkList) > 1){
                            return implode(",", $checkList);
                        }else{
                            if($model->online_scenario == 1000000000){
                                return '未设置';
                            }else{
                                return \common\models\Product::$scenario_set[$model->online_scenario];
                            }
                        }
                    }
                ],
                [
                    'attribute' => '权重',
                    'value' => 'weight'
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template'=> '{update-weight} {update-online} {updatetag} {customerscreen} {updateconfig} {sceneconfig} {onoffline}',
                    'buttons' => [
                        'update-weight' => function ($url,$model) {
                            return Html::button('权重配置', ['class'=>'weight-config-btn','data' =>$model->id ]);
                        },
                        'update-online' => function ($url,$model) {
                            return Html::button('线上配置', ['class'=>'set-online-config-btn','data' =>$model->id ]);
                        },
                        'updatetag' => function ($url,$model) {
                            return Html::button('标签配置', ['class'=>'set-tag-config-btn','data' =>$model->id ]);
                        },
                        'customerscreen' => function ($url,$model) {
                            return Html::button('客群筛选', ['class'=>'customer-screen-btn','data' =>$model->id ]);
                        },
                        'updateconfig' => function ($url,$model) {
                            return Html::button('限量配置', ['class'=>'set-size-config-btn','data' =>$model->id ]);
                        },
                        'sceneconfig' => function ($url,$model) {
                            return Html::button('场景配置', ['class'=>'set-scene-config-btn','data' =>$model->id ]);
                        },
                        'onoffline' => function ($url,$model) {
                            return Html::a('一键下线', ['offline', 'id' =>$model->id ], [ 'class' => 'btn btn-xs btn-danger', 'data-confirm' => '确认下线?' ]);
                        },
                    ],
                ],
            ],
        ]); ?>
    </div>
    <?php
    $weight_form_url = Url::toRoute('/product/update-weight');
    $online_config_form_url = Url::toRoute('/product/set-online-config');
    $tag_config_form_url = Url::toRoute('/product/set-tag-config');
    $customer_screen_form_url = Url::toRoute('/product/customer-screen');
    $set_size_config_url = Url::toRoute('/product/set-size-config');
    $set_scene_config_url = Url::toRoute('/product/set-scene-config');
    $set_offline_url = Url::toRoute('/product/offline');


    Modal::begin([
        'id'=>'weight-config',
        'size' => Modal::SIZE_LARGE,
        'options' => [
            'tabindex' => false,
            'data-backdrop' => 'static',
            'data-keyboard' => false
        ],
    ]);
    Modal::end();
    Modal::begin([
        'id'=>'set-online-config',
        'size' => Modal::SIZE_LARGE,
        'options' => [
            'tabindex' => false,
            'data-backdrop' => 'static',
            'data-keyboard' => false
        ]
    ]);
    Modal::end();
    Modal::begin([
        'id'=>'set-tag-config',
        'size' => Modal::SIZE_LARGE,
        'options' => [
            'tabindex' => false,
            'data-backdrop' => 'static',
            'data-keyboard' => false
        ],
    ]);
    Modal::end();
    Modal::begin([
        'id'=>'customer-screen',
        'size' => Modal::SIZE_LARGE,
        'options' => [
            'tabindex' => false,
            'data-backdrop' => 'static',
            'data-keyboard' => false
        ]
    ]);
    Modal::end();
    Modal::begin([
        'id'=>'set-size-config',
        'size' => Modal::SIZE_LARGE,
        'options' => [
            'tabindex' => false,
            'data-backdrop' => 'static',
            'data-keyboard' => false
        ]
    ]);
    Modal::end();
    Modal::begin([
        'id'=>'set-scene-config',
        'size' => Modal::SIZE_LARGE,
        'options' => [
            'tabindex' => false,
            'data-backdrop' => 'static',
            'data-keyboard' => false
        ]
    ]);
    Modal::end();
    ?>
</div>



<?php
$js = <<<JS
$('.weight-config-btn').on('click', function () {
        $('#weight-config').find('.modal-body').html(global_loading);
        $('#weight-config').modal('show');
        $('#weight-config').find('.modal-body').css('height','200px');
        $('#weight-config').find('.modal-body').css('overflow-y','auto');
        $.get('{$weight_form_url}',{ id: $(this).closest('tr').data('key') },
            function (data) {
                $('#weight-config').find('.modal-body').html(data);
            }
        );
    });

$('.set-online-config-btn').on('click', function () {
        $('#set-online-config').find('.modal-body').html(global_loading);
        $('#set-online-config').modal('show');
        $('#set-online-config').find('.modal-body').css('height','550px');
        $('#set-online-config').find('.modal-body').css('overflow-y','auto');
        $.get('{$online_config_form_url}',{ id: $(this).closest('tr').data('key') },
            function (data) {
                $('#set-online-config').find('.modal-body').html(data);
            }
        );
    });
$('.set-tag-config-btn').on('click', function () {
        $('#set-tag-config').find('.modal-body').html('');
        $('#set-tag-config').find('.modal-body').css('height','300px');
        $('#set-tag-config').find('.modal-body').css('overflow-y','auto');
        $.get('{$tag_config_form_url}',{ id: $(this).closest('tr').data('key') },
            function (data) {
                $('#set-tag-config').find('.modal-body').html(data);
                $('#set-tag-config').modal('show');
            }
        );
    });
$('.customer-screen-btn').on('click', function () {
        $('#customer-screen').find('.modal-body').html('');
        $('#customer-screen').find('.modal-body').css('height','500px');
        $('#customer-screen').find('.modal-body').css('overflow-y','auto');
        $.get('{$customer_screen_form_url}',{ id: $(this).closest('tr').data('key') },
            function (data) {
                // console.log(data)
                $('#customer-screen').find('.modal-body').html(data);
                $('#customer-screen').modal('show');
            }
        );
    });
$('.set-size-config-btn').on('click', function () {
        $('#set-size-config').find('.modal-body').html('');
        $('#set-size-config').find('.modal-body').css('height','500px');
        $('#set-size-config').find('.modal-body').css('overflow-y','auto');
        $.get('{$set_size_config_url}',{ id: $(this).closest('tr').data('key') },
            function (data) {
                // console.log(data)
                $('#set-size-config').find('.modal-body').html(data);
                $('#set-size-config').modal('show');
            }
        );
    });
$('.set-scene-config-btn').on('click', function () {
        $('#set-scene-config').find('.modal-body').html('');
        $('#set-scene-config').find('.modal-body').css('height','500px');
        $('#set-scene-config').find('.modal-body').css('overflow-y','auto');
        $.get('{$set_scene_config_url}',{ id: $(this).closest('tr').data('key') },
            function (data) {
                // console.log(data)
                $('#set-scene-config').find('.modal-body').html(data);
                $('#set-scene-config').modal('show');
            }
        );
    });
JS;
$this->registerJs($js, \yii\web\View::POS_END);
?>


<?php

use kartik\grid\GridView;
use backend\services\GetChannelDataServices;
use \kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ChannelDataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '渠道数据（市场端)';
$this->params['breadcrumbs'][] = $this->title;
$fullExportMenu = ExportMenu::widget([
    'container' => ['class' => 'btn-group', 'style' => 'margin-right:50px;','role' => 'group'],
    'filename' => 'filename',
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'label' => '日期',
            'value' => function($model) {
                if(isset($model->create_time) && date("Ymd") == date("Ymd", strtotime($model->create_time))) {
                    return date("Y/m/d", strtotime($model->create_time));
                } else {
                    return date("Y/m/d", strtotime($model->date));
                }
            }
        ],
        [
            'label' => '渠道ID',
            'value' => function ($model) {
                return $model->channel_id;
            }
        ],
//        [
//            'label' => '渠道名称',
//            'value' => function($model){
//                return $model->channels->channel_name;
//            }
//        ],
//        [
//            'label' => '商户',
//            'value' => function($model){
//                return $model->channels->loanMerchantInfo->merchant_name;
//            }
//        ],
//        [
//            'label' => '渠道类型',
//            'value' => function($model){
//                return $model->channels->market_type_set[$model->channels->type];
//            }
//        ],
//        [
//            'label' => '合作方式',
//            'value' => function($model){
//                return $model->channels->cooperation_set[$model->channels->cooperation];
//            }
//        ],
        [
            'label' => "UV",
            'value' => function () {
                return 0;
            }
        ],
        [
            'label' => "注册数",
            'value' => function ($model) {
                if(isset($model->create_time) && date("Ymd") == date("Ymd", strtotime($model->create_time))) {
                    return GetChannelDataServices::statsRegisterData(date("Ymd", strtotime($model->create_time)), $model->channel_id);
                } else {
                    return $model->register_data;
                }
            }
        ],
        [
            'label' => "登陆数",
            'value' => function ($model) {
                if(isset($model->create_time) && date("Ymd") == date("Ymd", strtotime($model->create_time))) {
                    return GetChannelDataServices::statsLoginData(date("Ymd", strtotime($model->create_time)), $model->channel_id);
                } else {
                    return $model->login_data;
                }
            }
        ],
    ],
    'exportConfig' => [
        ExportMenu::FORMAT_PDF => false,
    ],
    'target' => ExportMenu::TARGET_BLANK,
    'pjaxContainerId' => 'kv-pjax-container',
    'exportContainer' => [
        'class' => 'btn-group mr-2'
    ],
    'dropdownOptions' => [
        'label' => 'Full',
        'class' => 'btn btn-secondary',
        'itemsBefore' => [
            '<div class="dropdown-header">导出数据</div>',
        ],
    ],
]);
?>
<div class="channel-data-index">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //Html::a('Create Channel Data', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?php echo GridView::widget([
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => '<h3 class="panel-title"><i class="fas"></i>数据</h3>',
            ],
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'label' => '日期',
                    'value' => function($model) {
                        if(isset($model->create_time) && date("Ymd") == date("Ymd", strtotime($model->create_time))) {
                            return date("Y/m/d", strtotime($model->create_time));
                        } else {
                            return date("Y/m/d", strtotime($model->date));
                        }
                    }
                ],
                [
                    'label' => '渠道ID',
                    'value' => function ($model) {
                        return $model->channel_id;
                    }
                ],
//                [
//                    'label' => '渠道名称',
//                    'value' => function($model){
//                        return $model->channels->channel_name;
//                    }
//                ],
//                [
//                    'label' => '商户',
//                    'value' => function($model){
//                        return $model->channels->loanMerchantInfo->merchant_name;
//                    }
//                ],
//                [
//                    'label' => '渠道类型',
//                    'value' => function($model){
//                        return $model->channels->market_type_set[$model->channels->type];
//                    }
//                ],
//                [
//                    'label' => '合作方式',
//                    'value' => function($model){
//                        return $model->channels->cooperation_set[$model->channels->cooperation];
//                    }
//                ],
                [
                    'label' => "UV",
                    'value' => function () {
                        return 0;
                    }
                ],
                [
                    'label' => "注册数",
                    'value' => function ($model) {
                        if(isset($model->create_time) && date("Ymd") == date("Ymd", strtotime($model->create_time))) {
                            return GetChannelDataServices::statsRegisterData(date("Ymd", strtotime($model->create_time)), $model->channel_id);
                        } else {
                            return $model->register_data;
                        }
                    }
                ],
                [
                    'label' => "登陆数",
                    'value' => function ($model) {
                        if(isset($model->create_time) && date("Ymd") == date("Ymd", strtotime($model->create_time))) {
                            return GetChannelDataServices::statsLoginData(date("Ymd", strtotime($model->create_time)), $model->channel_id);
                        } else {
                            return $model->login_data;
                        }
                    }
                ],
            ],
            'toolbar' => [
                $fullExportMenu,
            ]
        ]); ?>

</div>

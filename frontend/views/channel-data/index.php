<?php

use kartik\grid\GridView;
use backend\services\GetChannelDataServices;
use \kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ChannelDataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '渠道数据';
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
        [
            'label' => '渠道名称',
            'value' => function($model){
                return $model->channels->channel_name;
            }
        ],
        [
            'label' => '商户',
            'value' => function($model){
                return $model->channels->loanMerchantInfo->merchant_name;
            }
        ],
        [
            'label' => '渠道类型',
            'value' => function($model){
                return $model->channels->market_type_set[$model->channels->type];
            }
        ],
        [
            'label' => '合作方式',
            'value' => function($model){
                return $model->channels->cooperation_set[$model->channels->cooperation];
            }
        ],
        [
            'label' => "IP",
            'value' => function ($model) {
                return $model->ip_data;
            }
        ],
        [
            'label' => "PV",
            'value' => function ($model) {
                return $model->pv_data;
            }
        ],
        [
            'label' => "UV",
            'value' => function ($model) {
                if(!empty($model->channelAssocAccount->uv_show)) {
                    return ceil($model->uv_data * $model->channelAssocAccount->uv_coefficient);
                }
            }
        ],
        [
            'label' => "注册数",
            'value' => function ($model) {
                if(isset($model->create_time) && date("Ymd") == date("Ymd", strtotime($model->create_time))) {
                    $register_data = GetChannelDataServices::statsRegisterData(date("Ymd", strtotime($model->create_time)), $model->channel_id);
                } else {
                    $register_data = $model->register_data;
                }
                if(!empty($model->channelAssocAccount->register_show)) {
                    return ceil($register_data * $model->channelAssocAccount->register_coefficient);
                }
            }
        ],
        [
            'label' => "登陆数",
            'value' => function ($model) {
                if(isset($model->create_time) && date("Ymd") == date("Ymd", strtotime($model->create_time))) {
                    $login_data = GetChannelDataServices::statsLoginData(date("Ymd", strtotime($model->create_time)), $model->channel_id);
                } else {
                    $login_data = $model->login_data;
                }
                if(!empty($model->channelAssocAccount->login_show)) {
                    return ceil($login_data * $model->channelAssocAccount->login_coefficient);
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
                [
                    'label' => '渠道名称',
                    'value' => function($model){
                        return $model->channels->channel_name;
                    }
                ],
                [
                    'label' => '商户',
                    'value' => function($model){
                        return $model->channels->loanMerchantInfo->merchant_name;
                    }
                ],
                [
                    'label' => '渠道类型',
                    'value' => function($model){
                        return $model->channels->market_type_set[$model->channels->type];
                    }
                ],
                [
                    'label' => '合作方式',
                    'value' => function($model){
                        return $model->channels->cooperation_set[$model->channels->cooperation];
                    }
                ],
                [
                    'label' => "IP",
                    'value' => function ($model) {
                        return $model->ip_data;
                    }
                ],
                [
                    'label' => "PV",
                    'value' => function ($model) {
                        return $model->pv_data;
                    }
                ],
                [
                    'label' => "UV",
                    'value' => function ($model) {
                        if(!empty($model->channelAssocAccount->uv_show)) {
                            return ceil($model->uv_data * $model->channelAssocAccount->uv_coefficient);
                        }
                    }
                ],
                [
                    'label' => "注册数",
                    'value' => function ($model) {
                        if(isset($model->create_time) && date("Ymd") == date("Ymd", strtotime($model->create_time))) {
                            $register_data = GetChannelDataServices::statsRegisterData(date("Ymd", strtotime($model->create_time)), $model->channel_id);
                        } else {
                            $register_data = $model->register_data;
                        }
                        if(!empty($model->channelAssocAccount->register_show)) {
                            return ceil($register_data * $model->channelAssocAccount->register_coefficient);
                        }
                    }
                ],
                [
                    'label' => "登陆数",
                    'value' => function ($model) {
                        if(isset($model->create_time) && date("Ymd") == date("Ymd", strtotime($model->create_time))) {
                            $login_data = GetChannelDataServices::statsLoginData(date("Ymd", strtotime($model->create_time)), $model->channel_id);
                        } else {
                            $login_data = $model->login_data;
                        }
                        if(!empty($model->channelAssocAccount->login_show)) {
                            return ceil($login_data * $model->channelAssocAccount->login_coefficient);
                        }
                    }
                ],
            ],
            'toolbar' => [
                $fullExportMenu,
            ]
        ]); ?>

</div>

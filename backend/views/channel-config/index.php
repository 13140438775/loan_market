<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ChannelConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Channel Configs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="channel-config-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('创建渠道配置', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php  ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => '渠道id',
                    'value' => 'channel_id',
                ],
                [
                    'attribute' => '渠道名字',
                    'value' => 'channel_name',
                ],
                [
                    'attribute' => '平台名称',
                    'value' => function ($model) {
                        return '用钱金卡';
                    }
                ],
                [
                    'attribute' => '包名',
                    'value' => 'package_id',
                ],
                [
                    'attribute' => '通用包',
                    'value' => function ($model) {
                        return \common\models\ChannelConfig::is_general_package_map[$model->is_general_package];
                    }
                ],
                [
                    'attribute' => '投放端',
                    'value' => function ($model) {
                        $checkList = [];
                        foreach (\common\models\ChannelConfig::put_in_map as $k => $v) {
                            if ((strval($model->delivery_terminal) & strval($k)) === strval($k)) {
                                if ($k != 1000000000) {
                                    $checkList[] = \common\models\ChannelConfig::put_in_map[strval($k)];
                                }
                            }
                        }
                        if (count($checkList) > 1) {
                            return implode(",", $checkList);
                        } else {
                            if ($model->online_scenario == 1000000000) {
                                return '未设置';
                            } else {
                                return \common\models\ChannelConfig::put_in_map[$model->delivery_terminal];
                            }
                        }
                    }
                ],
                [
                    'attribute' => '状态',
                    'value' => function ($model) {
                        return \common\models\ChannelConfig::status_map[$model->status];
                    }
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{copy} {config} {app} {useorunuse}',
                    'buttons' => [
                        'copy' => function ($url, $model) {
                            return Html::a('认证项配置', \yii\helpers\Url::toRoute(['/product/update-auth', 'id' => $model->id]));
                        },
                        'config' => function ($url, $model) {
                            return Html::a('配置', \yii\helpers\Url::toRoute(['/channel-config/update', 'id' => $model->id]));
                        },
                        'app' => function ($url, $model) {
                            return Html::a('App管理', \yii\helpers\Url::toRoute(['/package-manage/index', 'id' => $model->package_id]));
                        },
                        'useorunuse' => function ($url, $model) {
                            return Html::a('启用或禁用', ['offline', 'id' => $model->id], ['class' => 'btn btn-xs btn-danger', 'data-confirm' => '启用或禁用?']);
                        },
                    ],
                ],
            ],
        ]); ?>
    </div>
</div> 
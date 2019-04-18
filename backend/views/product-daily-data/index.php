<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use \kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductDailyDataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $apps array */

$this->title = '渠道数据统计';
$this->params['breadcrumbs'][] = $this->title;
$fullExportMenu = ExportMenu::widget([
    'container' => ['class' => 'btn-group', 'style' => 'margin-right:50px;','role' => 'group'],
    'filename' => 'filename',
    'dataProvider' => $dataProvider,
    'columns' => [
            'product_id',
        [
            'label' => '产品名称',
            'value' => function ($model) {
                return $model->creditProduct->product_name;
            }
        ],
        'uv',
        'pv',
        [
            'label' => 'app name',
            'value' => function ($model) use ($apps) {
                return $apps[$model->app_id];
            }
        ]

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
<div class="product-daily-data-index">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<h3 class="panel-title"><i class="fas"></i>数据</h3>',
        ],
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            'product_id',
            [
                'label' => '产品名称',
                'value' => function ($model) {
                    return $model->creditProduct->product_name;
                }
            ],
            'uv',
            'pv',
            [
                'label' => 'app name',
                'value' => function ($model) use ($apps) {
                    return $apps[$model->app_id];
                }
            ]
            //'date',
            //'created_at',
        ],
        'toolbar' => [
            $fullExportMenu,
        ]
    ]); ?>
</div>

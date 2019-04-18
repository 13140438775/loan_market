<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Admin;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PackageVersionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '包管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="package-versions-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('上传新包', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => '序号',
                'value' => function ($model) {
                    return $model->id;
                }
            ],
            [
                'attribute' => '包名',
                'value' => function ($model) {
                    $package = \common\models\Package::findOne($model->package_id);
                    if ($package !== null) {
                        return $package->package_name;
                    }
                    return '';
                }
            ],
            [
                'attribute' => '版本',
                'value' => function ($model) {
                    return $model->version_id;
                }
            ],
            // 'url:url',
            [
                'attribute' => '包类型',
                'value' => function ($model) {
                    return \common\models\PackageVersions::$platform_type_map[$model->type];
                }
            ],
            [
                'attribute' => '上传时间',
                'value' => function ($model) {
                    return date('Y-m-d H:i:s', $model->created_at);
                }
            ],
            [
                'attribute' => '操作人',
                'value' => function ($model) {
                    $admin = Admin::findOne($model->operator_id);
                    if ($admin !== null) {
                        return $admin->username;
                    }
                    return '';
                }
            ],
            //'updated_at',
            //['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        // var_dump($url);die;
                        return Html::a('<span class="glyphicon">删除</span>', $url . '&package_id=' . $model->package_id, [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => '你确定要删除吗？',
                                'method' => 'post'
                            ],
                        ]);
                    },
                ],
            ],
        ]
    ]); ?>
</div> 
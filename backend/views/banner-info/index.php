<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BannerInfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Banner Infos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-info-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('新增banner', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'title',
                'img',
                'url:url',
                'sort',
                // 'begin_time',
                // 'end_time',
                // 'created_at',
                // 'updated_at',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>

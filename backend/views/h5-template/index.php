<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\H5TemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'H5模板';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="h5-template-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('创建H5模板', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'h5_template_name',
                [
                    'attribute' => '缩略图',
                    'format' => 'raw',
                    'value' => function($model){
                        return Html::img(\Yii::$app->params['oss']['url_prefix'].$model->abbreviation_img, ['height' =>50, 'width' => 50]);
                    }
                ],
//                'banner_img',
//                'background_color',
                // 'submit_img',
                // 'is_show_company_main_body',
                // 'is_show_record_number',
                // 'created_at',
                // 'updated_at',
                // 'last_operator_id',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>

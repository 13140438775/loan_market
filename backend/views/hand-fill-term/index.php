<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\HandFillTermSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Hand Fill Terms';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hand-fill-term-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Create Hand Fill Term', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
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
                'term_key',
                'term_name',
                'type',
                'options:ntext',
                // 'career_type',
                // 'is_must',
                // 'term_group_id',
                // 'sort',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>

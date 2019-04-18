<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\HandFillTerm */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Hand Fill Terms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hand-fill-term-view box box-primary">
    <div class="box-header">
        <?= Html::a('继续添加', ['create', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'term_key',
                'term_name' ,
                [
                        'label' => '类型',
                        'value' => function($model){
                            return \common\models\HandFillTerm::$type_set[$model->type];
                        }
                ],
                'options:ntext',
                [
                    'label' => '职业类型',
                    'value' => function($model){
                        return \common\models\HandFillTerm::$career_type_set[$model->career_type];
                    }
                ],
                [
                    'label' => '是否必填',
                    'value' => function($model){
                        return \common\models\HandFillTerm::$is_must_set[$model->is_must];
                    }
                ],
                [
                    'label' => '所属分组',
                    'value' => function($model){
                        return \common\models\HandFillTerm::getGroups()[$model->term_group_id];
                    }
                ],
                'sort',
            ],
        ]) ?>
    </div>
</div>

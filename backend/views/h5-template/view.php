<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\H5Template */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'H5 Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="h5-template-view box box-primary">
    <div class="box-header">
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
                'h5_template_name',
                'abbreviation_img',
                'banner_img',
                'background_color',
                'submit_img',
                'is_show_company_main_body',
                'is_show_record_number',
                'created_at:datetime',
                'updated_at:datetime',
                'last_operator_id',
            ],
        ]) ?>
    </div>
</div>

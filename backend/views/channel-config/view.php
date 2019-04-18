<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ChannelConfig */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Channel Configs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="channel-config-view box box-primary">
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
                'channel_id',
                'channel_name',
                'platform_type',
                'package_id',
                'cooperate_mode',
                'is_general_package',
                'unsign_in_begin_version',
                'unsign_in_end_version',
                'sign_in_begin_version',
                'sign_in_end_version',
                'is_show_loan_user',
                'show_day',
                'delivery_terminal',
                'h5_template_id',
                'created_at:datetime',
                'updated_at:datetime',
                'last_operator_id',
            ],
        ]) ?>
    </div>
</div>

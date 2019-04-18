<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ChannelConfigSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="channel-config-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'channel_id') ?>

    <?= $form->field($model, 'channel_name') ?>

    <?= $form->field($model, 'platform_type') ?>

    <?= $form->field($model, 'package_id') ?>

    <?php // echo $form->field($model, 'cooperate_mode') ?>

    <?php // echo $form->field($model, 'is_general_package') ?>

    <?php // echo $form->field($model, 'unsign_in_begin_version') ?>

    <?php // echo $form->field($model, 'unsign_in_end_version') ?>

    <?php // echo $form->field($model, 'sign_in_begin_version') ?>

    <?php // echo $form->field($model, 'sign_in_end_version') ?>

    <?php // echo $form->field($model, 'is_show_loan_user') ?>

    <?php // echo $form->field($model, 'show_day') ?>

    <?php // echo $form->field($model, 'delivery_terminal') ?>

    <?php // echo $form->field($model, 'h5_template_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'last_operator_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

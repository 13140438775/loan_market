<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\H5TemplateSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="h5-template-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'h5_template_name') ?>

    <?= $form->field($model, 'abbreviation_img') ?>

    <?= $form->field($model, 'banner_img') ?>

    <?= $form->field($model, 'background_color') ?>

    <?php // echo $form->field($model, 'submit_img') ?>

    <?php // echo $form->field($model, 'is_show_company_main_body') ?>

    <?php // echo $form->field($model, 'is_show_record_number') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'last_operator_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

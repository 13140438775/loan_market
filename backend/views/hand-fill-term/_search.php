<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\HandFillTermSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hand-fill-term-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'term_key') ?>

    <?= $form->field($model, 'term_name') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'options') ?>

    <?php // echo $form->field($model, 'career_type') ?>

    <?php // echo $form->field($model, 'is_must') ?>

    <?php // echo $form->field($model, 'term_group_id') ?>

    <?php // echo $form->field($model, 'sort') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

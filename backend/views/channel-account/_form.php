<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ChannelAccount */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="">

    <?php $form = ActiveForm::begin([
        'options' => [
//            'class' => 'common-form',
        ],
        'fieldConfig' => function ($model, $attr) {
            $default = [
                'class' => 'yii\widgets\ActiveField',
                'template' => "<span class='col-sm-2 text-right'>{label}</span>\n{input}\n{hint}\n{error}",
                'options' => [
                    'class' => 'form-inline form-group-sm row top5',
                ],
                'inputOptions' => [
                    'class' => 'form-control',
                    'style' => 'width:400px;'
                ],
                'labelOptions' => [
                    'class' => 'control-label form-inline  initialism '
                ],
                'errorOptions' => [
                    'class' => 'self-help-block',
                    'tag' => 'span'
                ]
            ];
            return $default;
        }
    ]); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

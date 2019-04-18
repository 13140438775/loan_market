<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\web\JsExpression;
use \kartik\select2\Select2;
use \yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\HandFillTerm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hand-fill-term-form box box-primary">
    <?php $form = ActiveForm::begin(
        [
            'options' => [],
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
                switch ($attr) {
                    default:
                }
                return $default;
            }
        ]); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'term_key')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'term_name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'place_holder')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'type')->radioList(\common\models\HandFillTerm::$type_set) ?>
        <?= $form->field($model, 'data_type')->label('数据类型')->radioList(\common\models\HandFillTerm::$data_type_set) ?>

        <?= $form->field($model, 'options')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'career_type')->radioList(\common\models\HandFillTerm::$career_type_set) ?>

        <?= $form->field($model, 'is_must')->radioList(\common\models\HandFillTerm::$is_must_set) ?>

        <?= $form->field($model, 'term_group_id')->widget(Select2::class, [
            'data' => \common\models\HandFillTerm::getGroups(), // set the initial display text
//            'data' => [
//                '基础' => [
//                    '1' => '职业信息',
//                    '2' => '学历信息'
//                ],
//                '父级2' => [
//                    '4' => 'nbbbb'
//                ],
//                '1' => '车辆'
//            ],
            'options' => ['placeholder' => '选择产品'],
            'size' => Select2::MEDIUM,
            'pluginOptions' => [
                'allowClear' => true,
                'width' => '400px',
            ],
        ]) ?>

        <?= $form->field($model, 'sort')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
    <?= $this->render('trans', [
        'jsonString' => $jsonString
    ]) ?>


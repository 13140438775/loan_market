<?php

use yii\helpers\Html;
use \yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = '线上配置';
$this->params['breadcrumbs'][] = ['label' => 'Mk Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="channels-update">

        <h1><?= Html::encode($this->title) ?></h1>
        <div class="mk-product-form box box-primary">
            <?php $form = ActiveForm::begin(
                [
                    'id' => 'weight-update-form',
                    'enableAjaxValidation' => true,
                    'validationUrl' => Url::toRoute(['product/validate-set-online-config-form','id'=>$model->id]),
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
                        switch ($attr){
                            default:
                        }
                        return $default;
                    }
                ]); ?>
            <div class="box-body table-responsive" style="overflow-x:hidden;">

                <?= $form->field($model, 'show_name')->label('产品名称（展示用）')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'description')->label('一句话简介')->textInput() ?>



                <?= $form->field($model, 'sort_min_loan_time')->label('最快放款时间（排序）')->textInput() ?>
                <?= $form->field($model, 'sort_min_loan_time_type')->dropDownList(\common\models\Product::$term_type_set) ?>


                <?= $form->field($model, 'interest_day')->label('实际日息（排序用）')->textInput() ?>

                <?= $form->field($model, 'show_min_loan_time')->label('最快放款时间*（展示）')->textInput() ?>

                <?= $form->field($model, 'show_amount_range')->label('额度范围*（展示）')->textInput() ?>
                <?= $form->field($model, 'interest_pay_type_desc')->label('息费说明*（展示）')->textInput() ?>
                <?= $form->field($model, 'show_avg_term')->label('期限范围*（展示）')->textInput() ?>



            </div>
            <div class="box-footer">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

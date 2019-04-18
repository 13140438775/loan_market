<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
$merchant_list_url = Url::to(['merchant/list']);
?>

<div class="mk-product-form box box-primary">
    <?php $form = ActiveForm::begin(
        [
            'options' => ['id' => 'api-product-form'],
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
                    case 'logo_url':
                        {
                            $src = $model->logo_url ? Yii::$app->params['oss']['url_prefix'] . $model->logo_url : 'http://temp.im/80x80/0ff/d00';
                            $default['template'] = <<<TMP
<div class='form-inline form-group-sm row'>
    <span class='col-sm-2 text-right'></span>
    <img src={$src} class="pre_logo_url" height="80" width="80" alt="">
</div>
<div class='form-inline form-group-sm row'>
    <span class='col-sm-2 text-right'>{label}</span>\n{input}\n{hint}\n{error}
</div>
TMP;
                            $default['options']['class'] = '';
                            $default['inputOptions']['hiddenOptions'] = [
                                'value' => $model->logo_url
                            ];
                            break;
                        }
                    case 'interest_day':
                        {
                            $default['template'] = "<span class='col-sm-2 text-right'>{label}</span>\n{input}%\n{hint}\n{error}";
                            break;
                        }
                    default:
                }
                return $default;
            }
        ]); ?>
    <div class="box-body table-responsive">

        <div><h4>产品所属公司信息</h4></div>
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'merchant_id')->widget(Select2::class, [
            'initValueText' => empty($model->merchant_id) ? '' : $model->getMerchant()->one() ? $model->getMerchant()->one()->company_name:'', // set the initial display text
            'options' => ['placeholder' => '选择公司'],
            'size' => Select2::MEDIUM,
            'pluginOptions' => [
                'allowClear' => true,
                'width' => '400px',
                'minimumInputLength' => 1,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return '稍等'; }"),
                ],
                'ajax' => [
                    'url' => $merchant_list_url,
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(item) { return item.text; }'),
                'templateSelection' => new JsExpression('function (item) { return item.text; }'),
            ],
        ])->error(['error' => "请选择公司名称"]) ?>

        <hr>
        <div><h4>产品基本信息</h4></div>
        <?= $form->field($model, 'show_name')->label('产品展示名称')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'logo_url')->label('产品logo')->fileInput(['value' => '', 'maxlength' => true]) ?>

        <?= $form->field($model, 'description')->label('产品简介')->textarea(['maxlength' => true]) ?>

        <?= $form->field($model, 'sort_min_loan_time', [
            'inputOptions' => [
                'class' => 'form-control',
                'style' => 'width:150px;',
            ]])->label('最快放款时间(排序)')->textInput() ?>

        <?= $form->field($model, 'sort_min_loan_time_type')
            ->dropDownList(\common\models\Product::$sort_min_loan_time_type_set) ?>

        <?= $form->field($model, 'interest_day', [
            'inputOptions' => [
                'class' => 'form-control',
                'style' => 'width:150px;',
            ]
        ])->label('实际日息(排序用)')->textInput() ?>

        <?= $form->field($model, 'show_min_loan_time')->label('最快放款时间 (展示)')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'show_amount_range')->label('额度范围 (展示)')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'show_interest_desc')->label('息费说明 (展示)')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'show_avg_term')->label('期限范围 (展示)')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'interest_pay_type')->label('息费收取方式')->radioList(\common\models\Product::$interest_pay_type_set) ?>

        <?= $form->field($model, 'interest_pay_type_desc',['options'=>['style'=>'display:none']])->label('其他息费说明')->textarea(['maxlength' => true]) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

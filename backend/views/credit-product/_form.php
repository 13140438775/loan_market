<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \common\models\CreditProduct;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\CreditProduct */
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
            switch ($attr) {
                case 'up_time':
                    $default['enableClientValidation'] = false;
                    break;
                case $attr == 'product_desc' || $attr == 'product_features':
                    $default['inputOptions']['rows'] = 4;
                    break;
                case 'min_credit':
                    $default = [
                        'class' => 'yii\widgets\ActiveField',
                        'template' => "<span class='col-sm-2 text-right'>{label}</span>\n{input}\n{hint}\n{error}",
                        'options' => [
                            'class' => 'form-inline form-group-sm  top5',
                            'tag' => 'span'
                        ],
                        'inputOptions' => [
                            'class' => 'form-control',
                            'style' => 'width:150px;',
                            'placeholder' => "请输入下限",
                            'autocomplete' => 'off'

                        ],
                        'labelOptions' => [
                            'class' => 'control-label form-inline  initialism '
                        ],
                        'errorOptions' => [
                            'class' => 'self-help-block',
                            'tag' => 'span'
                        ]
                    ];
                    break;
                case 'max_credit':
                    $default = [
                        'class' => 'yii\widgets\ActiveField',
                        'template' => "{input}\n{hint}\n{error}",
                        'options' => [
                            'class' => 'form-inline form-group-sm  top5',
                            'tag' => 'span'
                        ],
                        'inputOptions' => [
                            'class' => 'form-control',
                            'style' => 'width:150px;',
                            'placeholder' => "请输入上限",
                            'autocomplete' => 'off'
                        ],
                        'labelOptions' => [
                            'class' => 'control-label form-inline  initialism ',
                        ],
                        'errorOptions' => [
                            'class' => 'self-help-block',
                            'tag' => 'span'
                        ]
                    ];
                    break;
                case 'min_credit_days':
                    $default = [
                        'class' => 'yii\widgets\ActiveField',
                        'template' => "<span class='col-sm-2 text-right'>{label}</span>\n{input}\n{hint}\n{error}",
                        'options' => [
                            'class' => 'form-inline form-group-sm  top5',
                            'tag' => 'span'
                        ],
                        'inputOptions' => [
                            'class' => 'form-control',
                            'style' => 'width:150px;',
                            'placeholder' => "请输入下限",
                            'autocomplete' => 'off'

                        ],
                        'labelOptions' => [
                            'class' => 'control-label form-inline  initialism '
                        ],
                        'errorOptions' => [
                            'class' => 'self-help-block',
                            'tag' => 'span'
                        ]
                    ];
                    break;
                case 'max_credit_days':
                    $default = [
                        'class' => 'yii\widgets\ActiveField',
                        'template' => "{input}\n{hint}\n{error}",
                        'options' => [
                            'class' => 'form-inline form-group-sm  top5',
                            'tag' => 'span'
                        ],
                        'inputOptions' => [
                            'class' => 'form-control',
                            'style' => 'width:150px;',
                            'placeholder' => "请输入上限",
                            'autocomplete' => 'off'
                        ],
                        'labelOptions' => [
                            'class' => 'control-label form-inline  initialism ',
                        ],
                        'errorOptions' => [
                            'class' => 'self-help-block',
                            'tag' => 'span'
                        ]
                    ];
                    break;
                case 'credit_limit_type':
                    $default = [
                        'class' => 'yii\widgets\ActiveField',
                        'template' => "{input}\n{hint}\n{error}",
                        'options' => [
                            'class' => 'form-inline form-group-sm  top5',
                            'tag' => 'span'
                        ],
                        'inputOptions' => [
                            'class' => 'form-control',
                            'style' => 'width:150px;',
                        ],
                        'labelOptions' => [
                            'class' => 'control-label form-inline  initialism ',
                        ],
                        'errorOptions' => [
                            'class' => 'self-help-block',
                            'tag' => 'span'
                        ]
                    ];
                    break;
                case 'avg_credit_days':
                    $default = [
                        'class' => 'yii\widgets\ActiveField',
                        'template' => "<span class='col-sm-2 text-right'>{label}</span>\n{input}\n{hint}\n{error}",
                        'options' => [
                            'class' => 'form-inline form-group-sm  top5',
                            'tag' => 'span'
                        ],
                        'inputOptions' => [
                            'class' => 'form-control',
                            'style' => 'width:150px;',
                            'placeholder' => "请输入平均借款期限",
                        ],
                        'labelOptions' => [
                            'class' => 'control-label form-inline  initialism ',
                        ],
                        'errorOptions' => [
                            'class' => 'self-help-block',
                            'tag' => 'span'
                        ]
                    ];
                    break;
                case 'avg_credit_limit_type':
                    $default = [
                        'class' => 'yii\widgets\ActiveField',
                        'template' => "{input}\n{hint}\n{error}",
                        'options' => [
                            'class' => 'form-inline form-group-sm  top5',
                            'tag' => 'span'
                        ],
                        'inputOptions' => [
                            'class' => 'form-control',
                            'style' => 'width:150px;',
                        ],
                        'labelOptions' => [
                            'class' => 'control-label form-inline  initialism ',
                        ],
                        'errorOptions' => [
                            'class' => 'self-help-block',
                            'tag' => 'span'
                        ]
                    ];
                    break;
                case 'fast_loan':
                    $default = [
                        'class' => 'yii\widgets\ActiveField',
                        'template' => "<span class='col-sm-2 text-right'>{label}</span>\n{input}\n{hint}\n{error}",
                        'options' => [
                            'class' => 'form-inline form-group-sm  top5',
                            'tag' => 'span'
                        ],
                        'inputOptions' => [
                            'class' => 'form-control',
                            'style' => 'width:150px;',
                            'placeholder' => "请输入最快放款时间",
                        ],
                        'labelOptions' => [
                            'class' => 'control-label form-inline  initialism ',
                        ],
                        'errorOptions' => [
                            'class' => 'self-help-block',
                            'tag' => 'span'
                        ]
                    ];
                    break;
                case 'fast_loan_type':
                    $default = [
                        'class' => 'yii\widgets\ActiveField',
                        'template' => "{input}\n{hint}\n{error}",
                        'options' => [
                            'class' => 'form-inline form-group-sm  top5',
                            'tag' => 'span'
                        ],
                        'inputOptions' => [
                            'class' => 'form-control',
                            'style' => 'width:150px;',
                        ],
                        'labelOptions' => [
                            'class' => 'control-label form-inline  initialism ',
                        ],
                        'errorOptions' => [
                            'class' => 'self-help-block',
                            'tag' => 'span'
                        ]
                    ];
                    break;
                case 'rate_type':
                    $default = [
                        'class' => 'yii\widgets\ActiveField',
                        'template' => "<span class='col-sm-2 text-right'>{label}</span>\n{input}\n{hint}\n{error}",
                        'options' => [
                            'class' => 'form-inline form-group-sm  top5',
                            'tag' => 'span'
                        ],
                        'inputOptions' => [
                            'class' => 'form-control',
                            'style' => 'width:150px;',
                        ],
                        'labelOptions' => [
                            'class' => 'control-label form-inline  initialism ',
                        ],
                        'errorOptions' => [
                            'class' => 'self-help-block',
                            'tag' => 'span'
                        ]
                    ];
                    break;
                case 'rate_num':
                    $default = [
                        'class' => 'yii\widgets\ActiveField',
                        'template' => "{input}\n{hint}\n{error}",
                        'options' => [
                            'class' => 'form-inline form-group-sm  top5',
                            'tag' => 'span'
                        ],
                        'inputOptions' => [
                            'class' => 'form-control',
                            'style' => 'width:150px;',
                            'placeholder' => "请输入利率",
                            'autocomplete' => 'off'
                        ],
                        'labelOptions' => [
                            'class' => 'control-label form-inline  initialism ',
                        ],
                        'errorOptions' => [
                            'class' => 'self-help-block',
                            'tag' => 'span'
                        ]
                    ];
                    break;
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

//                default:
//                    return $default;
            }
            return $default;
        }
    ]); ?>
    <div class="row">
        <h4 style="margin-left: 100px;">产品信息</h4>
    </div>
    <?= $form->field($model, 'product_name')->textInput(['maxlength' => true]) ?>
    <div class="form-inline form-group-sm row top5">
        <?= $form->field($model, 'min_credit')->label('贷款范围(元)')->textInput() ?>
        --
        <?= $form->field($model, 'max_credit')->textInput() ?>
    </div>

    <div class="form-inline form-group-sm row top5">
        <?= $form->field($model, 'rate_type')->label('利率')->dropDownList(CreditProduct::$rate_type_set) ?>
        <?= $form->field($model, 'rate_num')->textInput() ?>
    </div>

    <div class="form-inline form-group-sm row top5">
        <?= $form->field($model, 'min_credit_days')->label('借款期限范围')->textInput() ?>
        <?= $form->field($model, 'max_credit_days')->textInput() ?>
        <?= $form->field($model, 'credit_limit_type')->dropDownList(CreditProduct::$credit_limit_type_set) ?>
    </div>

    <div class="form-inline form-group-sm row top5">
        <?= $form->field($model, 'avg_credit_days')->label('平均借款期限')->textInput() ?>
        <?= $form->field($model, 'avg_credit_limit_type')->dropDownList(CreditProduct::$avg_credit_limit_type_set) ?>
    </div>

    <div class="form-inline form-group-sm row top5">
        <?= $form->field($model, 'fast_loan')->label('最快放款时间')->textInput() ?>
        <?= $form->field($model, 'fast_loan_type')->dropDownList(CreditProduct::$fast_loan_type_set) ?>
    </div>
    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'logo_url')->fileInput(['value' => '', 'maxlength' => true]) ?>
    <?= $form->field($model, 'product_desc')->textarea(['maxlength' => true]) ?>
    <?= $form->field($model, 'apply_conditions')->textarea(['maxlength' => true]) ?>
    <?= $form->field($model, 'product_features')->textarea(['maxlength' => true]) ?>


    <hr>

    <div class="row">
        <h4 style="margin-left: 100px;">运营配置</h4>
    </div>
    <?= $form->field($model, 'credit_base')->textInput() ?>
    <?= $form->field($model, 'product_status')->radioList(CreditProduct::$product_status_set) ?>
    <?= $form->field($model, 'tagIds')->label('筛选标签 *')->checkboxList(\common\models\ProductTag::getIdMapName()) ?>
    <?= $form->field($model, 'tag_id')->radioList(array_merge(['0' => "无"], \common\models\ProductTag::getIdMapName())) ?>
    <?= $form->field($model, 'uv_limit')->textInput() ?>

    <?= $form->field($model, 'up_time')->widget(DateTimePicker::class, [
        'type' => DateTimePicker::TYPE_INPUT,
        'language' => 'zh-CN',
        'options' => [
            'value' => $model->id ? date('Y-m-d H:i:s', $model->up_time) : '',
            'autocomplete' => 'off'
        ],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd hh:ii:ss'
        ]
    ]) ?>

    <?= $form->field($model, 'sort')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$js = <<<JS

$("#creditproduct-logo_url").on("change", function (e) {
    var file = $(this)[0].files[0];
    if(file){
      var upload = new Upload(file,'#creditproduct-logo_url','.pre_logo_url','.field-creditproduct-logo_url input[type=hidden]');
      upload.doUpload();
    }
});
//上架时间初始化
    var temporary_shelves = $("#creditproduct-product_status input:eq(1)");
    var temporarys = $("#creditproduct-product_status input").not('input:eq(1)');
    // $(".field-creditproduct-uv_limit").hide();
    // $(".field-creditproduct-sort").hide();
    $(".field-creditproduct-up_time").hide();
    temporary_shelves.click(function(){
        // $(".field-creditproduct-uv_limit").show();
        // $(".field-creditproduct-sort").show();
        $(".field-creditproduct-up_time").show();
    });
    temporarys.each(function() {
        $(this).click(function(){
            // $(".field-creditproduct-uv_limit").hide();
            // $(".field-creditproduct-sort").hide();
            $(".field-creditproduct-up_time").hide();
        });
    })
    
    // $("#btn-success").click(function(event) {
    //   event.preventDefault();
    //   jQuery('#w0').yiiActiveForm().submit();    
    // });
JS;
$this->registerJs($js, \yii\web\View::POS_END);
?>

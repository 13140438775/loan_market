<?php

use yii\helpers\Html;
use \yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = '客群筛选';
$this->params['breadcrumbs'][] = ['label' => 'Mk Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$style_display = $model->filter_user_enable ?  'display:block': 'display:none';
?>
    <div class="channels-update">

        <h4><?= Html::encode($this->title) ?></h4>
        <div class="mk-product-form box box-primary">
            <?php $form = ActiveForm::begin(
                [
                    'id' => 'customer-screen-form',
                    'enableAjaxValidation' => true,
                    'validationUrl' => Url::toRoute(['product/validate-customer-screen-form','id'=>$model->id]),
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

                <?= $form->field($model, 'filter_user_enable')->label('是否启用客群筛选')->radioList(\common\models\Product::$is_customer_screen_set) ?>
                <?php echo '<div class="filter_user_enable" style="'.$style_display.'">' ?>
                    <?= $form->field($model, 'enable_mobile_black')->label('是否开启手机号码黑名单')->radioList(\common\models\Product::$is_mobile_black_set)?>
                    <?= $form->field($model, 'min_age')->textInput()?>
                    <?= $form->field($model, 'max_age')->textInput()?>
                    <?= $form->field($model, 'area_filter')->label('地域过滤身份证前三位或者前6位')->textInput()?>

                        <?php if(!$model->isNewRecord){
                            $checkList = [];
                            foreach (\common\models\Product::$visible_filter_set as $k => $v){
                                if((strval($model->filter_net_time) & strval($k)) === strval($k)){
                                    $checkList[] = strval($k);
                                }
                            }
                            $model->filter_net_time = $checkList;
                        } ?>
                    <?= $form->field($model, 'filter_net_time')->label('手机过滤时长')->checkboxList(\common\models\Product::$visible_filter_set)?>
                </div>
            </div>
            <div class="box-footer">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

<?php
$js = <<<JS
$("input[name = 'Product[filter_user_enable]']").on('click', function () {
        if($(this).val() == 1){
            $('.filter_user_enable').show();
            $('#customer-screen-form').yiiActiveForm('add', {
                id: "product-min_age",
                name: 'Product[min_age]',
                container: '.field-product-min_age',
                input: '#product-min_age',
                error: '.help-block',
                validate:  function(attribute, value, messages, deferred, form) {
                    yii.validation.required(value, messages, {message: "请输入年龄下限"});
                    yii.validation.number(value, messages, {"pattern":/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/,"message": "请输入年龄下限整数", "min" :1,"max": 2,"skipOnEmpty": 1 });
                }
                
            });
            $('#customer-screen-form').yiiActiveForm('add', {
                id: "product-max_age",
                name: 'Product[max_age]',
                container: '.field-product-max_age',
                input: '#product-max_age',
                error: '.help-block',
                validate:  function(attribute, value, messages, deferred, form) {
                    yii.validation.required(value, messages, {message: "请输入年龄上限"});
                    yii.validation.number(value, messages, {"pattern":/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/,"message":"请输入年龄上限整数", "min" :1,"max": 2,"skipOnEmpty": 1 });
                }
            });
            $('#customer-screen-form').yiiActiveForm('add',{
                id: "product-area_filter",
                name: 'Product[area_filter]',
                container: '.field-product-area_filter',
                input: '#product-area_filter',
                error: '.help-block',
                validate:  function(attribute, value, messages, deferred, form) {
                    yii.validation.required(value, messages, {message: "请输入身份证"});
                    yii.validation.number(value, messages, {"pattern":/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/,"message":"身份证必须是数字", "min" :1,"max": 6,"skipOnEmpty": 1 });
                }
            });
        }else{
            $('.filter_user_enable').hide();
            $('#customer-screen-form').yiiActiveForm('remove', 'product-min_age');
            $('#customer-screen-form').yiiActiveForm('remove', 'product-max_age');
            $('#customer-screen-form').yiiActiveForm('remove', 'product-area_filter');
        }
    });

JS;
$this->registerJs($js, \yii\web\View::POS_END);
?>

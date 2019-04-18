<?php

use yii\helpers\Html;
use \yii\widgets\ActiveForm;
use yii\helpers\Url;
use \kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = '限量配置';
$this->params['breadcrumbs'][] = ['label' => 'Mk Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$product_sales = \common\models\ProductSales::find()->where(['product_id' => $model->id ])->one();
$surplus_first_loan_one_push = $surplus_first_loan_approval = $surplus_second_loan_one_push = $surplus_second_loan_approval =0;
if(!is_null($product_sales)){
    $surplus_first_loan_one_push = $model->first_loan_one_push_limit - $product_sales->first_loan_one_push;

    $surplus_first_loan_approval = $model->first_loan_approval_limit - $product_sales->first_loan_approval;

    $surplus_second_loan_one_push = $model->second_loan_one_push_limit - $product_sales->second_loan_one_push;
    
    $surplus_second_loan_approval = $model->second_loan_approval_limit - $product_sales->second_loan_approval;
}

$enable_count_limit_style_display = $model->enable_count_limit ?  'display:block': 'display:none';
$is_time_sharing_style_display = $model->is_time_sharing ?  'display:block': 'display:none';
$is_diff_first_style_display = $model->is_diff_first ?  'display:block': 'display:none';
$is_diff_plat_style_display = $model->is_diff_plat ?  'display:block': 'display:none';
$uv_day_limit_style_display = $model->call_type ?  'display:block': 'display:none';

?>
    <div class="channels-update">

        <h1><?= Html::encode($this->title) ?></h1>
        <div class="mk-product-form box box-primary">
            <?php $form = ActiveForm::begin(
                [
                    'id' => 'set-size-config-form',
                    'enableClientValidation' => false,
                    'enableAjaxValidation'=>false,
                    'validationUrl' => Url::toRoute(['product/validate-set-size-config-form','id'=>$model->id]),
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
                            case 'limit_begin_time':
//                                $default['enableClientValidation'] = false;

                                break;
                            case 'limit_end_time':
//                                $default['enableClientValidation'] = false;

                                break;
                            default:
                        }
                        return $default;
                    }
                ]); ?>
            <div class="box-body table-responsive" style="overflow-x:hidden;">


                <?= $form->field($model, 'enable_count_limit')->label('是否启用')->radioList(\common\models\Product::$is_customer_screen_set) ?>
                <?php echo '<div class="enable_count_limit" style="'.$enable_count_limit_style_display.'">' ?>
                    <?= $form->field($model, 'is_time_sharing')->label('是否分时段')->radioList(\common\models\Product::$is_mobile_black_set) ?>
                    <?php echo '<div class="is_time_sharing" style="'.$is_time_sharing_style_display.'">' ?>
                        <?= $form->field($model, 'limit_begin_time')->label('开始时间')->textInput() ?>
                        <?= $form->field($model, 'limit_end_time')->label('结束时间')->textInput() ?>
                    </div>
                    <?php echo '<div class="uv_day_limit" style="'.$uv_day_limit_style_display.'">' ?>
                        <?= $form->field($model, 'uv_day_limit')->label('点击数控制')->textInput() ?>
                    </div>
                    <?= $form->field($model, 'is_diff_first')->label('是否区分首复贷控量')->radioList(\common\models\Product::$is_customer_screen_set) ?>
                    <?= $form->field($model, 'is_diff_plat')->label('是否区分平台控量')->radioList(\common\models\Product::$is_mobile_black_set) ?>
                    <?php echo '<div class="is_diff_plat" style="'.$is_diff_plat_style_display.'">' ?>
                        <?= $form->field($model, 'appIds')->label('勾选控量平台')->checkboxList(\common\models\Product::getAppsIdMapName()) ?>
                    </div>
                    <?php echo '<div class="is_diff_first" style="'.$is_diff_first_style_display.'">' ?>
                        <?= $form->field($model, 'first_loan_one_push_limit',['enableAjaxValidation'=>true])->label('首贷一推单量控制'.$surplus_first_loan_one_push)->textInput() ?>
                        <?= $form->field($model, 'first_loan_approval_limit',['enableAjaxValidation'=>true])->label('首贷审核单量控制'.$surplus_first_loan_approval)->textInput() ?>
                        <?= $form->field($model, 'second_loan_one_push_limit',['enableAjaxValidation'=>true])->label('复贷一推单量控制'.$surplus_second_loan_one_push)->textInput() ?>
                        <?= $form->field($model, 'second_loan_approval_limit',['enableAjaxValidation'=>true])->label('复贷审核单量控制'.$surplus_second_loan_approval)->textInput() ?>
                    </div>
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
$("input[name = 'Product[enable_count_limit]']").on('click', function () {
        if($(this).val() == 1){
            $('.enable_count_limit').show();
            add_is_diff_first();
            add_time();
            $('#set-size-config-form').yiiActiveForm('add', {
                id: "product-uv_day_limit",
                name: 'Product[uv_day_limit]',
                container: '.field-product-uv_day_limit',
                input: '#product-uv_day_limit',
                error: '.help-block',
                validate:  function(attribute, value, messages, deferred, form) {
                    yii.validation.required(value, messages, {message: "请输入数字"});
                    yii.validation.number(value, messages, {"pattern":/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/,"message":"请输入数字", "min" :1,"max": 10,"skipOnEmpty": 1 });
                }
            });
        }else{
            $('.enable_count_limit').hide();
            remove_time();
            $('#set-size-config-form').yiiActiveForm('remove', 'product-uv_day_limit');
            remove_is_diff_first();
        }
    });
$("input[name = 'Product[is_time_sharing]']").on('click', function () {
        if($(this).val() == 1){
            $('.is_time_sharing').show();
            add_time()
        }else{
            $('.is_time_sharing').hide();
            remove_time();
        }
    });
$("input[name = 'Product[is_diff_first]']").on('click', function () {
        if($(this).val() == 1){
            $('.is_diff_first').show();
            add_is_diff_first();
        }else{
            $('.is_diff_first').hide();
            remove_is_diff_first();
        }
    });
$("input[name = 'Product[is_diff_plat]']").on('click', function () {
        if($(this).val() == 1){
            $('.is_diff_plat').show();
        }else{
            $('.is_diff_plat').hide();
        }
    });


function remove_is_diff_first(){
    $('#set-size-config-form').yiiActiveForm('remove', 'product-first_loan_one_push_limit');
    $('#set-size-config-form').yiiActiveForm('remove', 'product-first_loan_approval_limit');
    $('#set-size-config-form').yiiActiveForm('remove', 'product-second_loan_one_push_limit');
    $('#set-size-config-form').yiiActiveForm('remove', 'product-second_loan_approval_limit');
}
 
function remove_time() {
    $('#set-size-config-form').yiiActiveForm('remove', 'product-limit_begin_time');
    $('#set-size-config-form').yiiActiveForm('remove', 'product-limit_end_time');
}

function add_is_diff_first(){
    $('#set-size-config-form').yiiActiveForm('add', {
        id: "product-first_loan_one_push_limit",
        name: 'Product[first_loan_one_push_limit]',
        container: '.field-product-first_loan_one_push_limit',
        input: '#product-first_loan_one_push_limit',
        error: '.help-block',
        validate:  function(attribute, value, messages, deferred, form) {
            yii.validation.required(value, messages, {message: "请输入数字"});
            yii.validation.number(value, messages, {"pattern":/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/,"message":"请输入数字", "min" :1,"max": 10,"skipOnEmpty": 1 });
        }
    });
    $('#set-size-config-form').yiiActiveForm('add', {
        id: "product-first_loan_approval_limit",
        name: 'Product[first_loan_approval_limit]',
        container: '.field-product-first_loan_approval_limit',
        input: '#product-first_loan_approval_limit',
        error: '.help-block',
        validate:  function(attribute, value, messages, deferred, form) {
            yii.validation.required(value, messages, {message: "请输入数字"});
            yii.validation.number(value, messages, {"pattern":/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/,"message":"请输入数字", "min" :1,"max": 10,"skipOnEmpty": 1 });
        }
    });
    $('#set-size-config-form').yiiActiveForm('add', {
        id: "product-second_loan_one_push_limit",
        name: 'Product[second_loan_one_push_limit]',
        container: '.field-product-second_loan_one_push_limit',
        input: '#product-second_loan_one_push_limit',
        error: '.help-block',
        validate:  function(attribute, value, messages, deferred, form) {
            yii.validation.required(value, messages, {message: "请输入数字"});
            yii.validation.number(value, messages, {"pattern":/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/,"message":"请输入数字", "min" :1,"max": 10,"skipOnEmpty": 1 });
        }
    });
    $('#set-size-config-form').yiiActiveForm('add', {
        id: "product-second_loan_approval_limit",
        name: 'Product[second_loan_approval_limit]',
        container: '.field-product-second_loan_approval_limit',
        input: '#product-second_loan_approval_limit',
        error: '.help-block',
        validate:  function(attribute, value, messages, deferred, form) {
            yii.validation.required(value, messages, {message: "请输入数字"});
            yii.validation.number(value, messages, {"pattern":/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/,"message":"请输入数字", "min" :1,"max": 10,"skipOnEmpty": 1 });
        }
    });
}

function add_time(){
    $('#set-size-config-form').yiiActiveForm('add', {
        id: "product-limit_begin_time",
        name: 'Product[limit_begin_time]',
        container: '.field-product-limit_begin_time',
        input: '#product-limit_begin_time',
        error: '.help-block',
        validate:  function(attribute, value, messages, deferred, form) {
            yii.validation.required(value, messages, {message: "请输入数字"});
            yii.validation.number(value, messages, {"pattern":/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/,"message":"请输入数字", "min" :1,"max": 10,"skipOnEmpty": 1 });
        }
    });
    $('#set-size-config-form').yiiActiveForm('add', {
        id: "product-limit_end_time",
        name: 'Product[limit_end_time]',
        container: '.field-product-limit_end_time',
        input: '#product-limit_end_time',
        error: '.help-block',
        validate:  function(attribute, value, messages, deferred, form) {
            yii.validation.required(value, messages, {message: "请输入数字"});
            yii.validation.number(value, messages, {"pattern":/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/,"message":"请输入数字", "min" :1,"max": 10,"skipOnEmpty": 1 });
        }
    });
}
JS;
$this->registerJs($js, \yii\web\View::POS_END);
?>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ProductApiConfig */
/* @var $form yii\widgets\ActiveForm */
if($model->bind_card_mode == 2){
    $bind_card_h5_url = 'display:block';
}else{
    $bind_card_h5_url = 'display:none';
}

$h5_sign_url = $model->is_h5_sign_page ?  'display:block' : 'display:none';
?>

<div class="product-api-config-form box box-primary">
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
            switch ($attr){
                default:
            }
            return $default;
        }
    ]); ?>
    <div class="box-body table-responsive">

        <div class="row">
            <h3 style="margin-left: 100px;">接口配置</h3>
        </div>
        <?= $form->field($model, 'api_invoke_type')->label('接口配置')->radioList(\common\models\ProductApiConfig::$api_invoke_type_set) ?>

        <?= $form->field($model, 'credit_type')->label('授信方式')->radioList(\common\models\ProductApiConfig::$credit_type_set) ?>

        <div class="row">
            <h3 style="margin-left: 100px;">流程接口配置</h3>
        </div>
        <?= $form->field($model, 'is_simple_reloan_flow')->label('是否支持复贷简化流程')->radioList(\common\models\ProductApiConfig::$is_simple_reloan_flow_set) ?>

        <?= $form->field($model, 'is_outer_auth_product')->label('是否为请求外部获取认证地址产品')->radioList(\common\models\ProductApiConfig::$is_outer_auth_product_set) ?>

        <?= $form->field($model, 'is_update_audit_limit')->label('是否修改审核额度')->radioList(\common\models\ProductApiConfig::$is_update_audit_limit_set) ?>

        <?= $form->field($model, 'is_market')->label('是否有商城模式')->radioList(\common\models\ProductApiConfig::$is_market_set) ?>

        <?= $form->field($model, 'is_h5_sign_page')->label('是否有H5签约页面')->radioList(\common\models\ProductApiConfig::$is_h5_sign_page_set) ?>

        <?php echo '<div class="h5_sign_url" style="'.$h5_sign_url.'">' ?>
            <?= $form->field($model, 'h5_sign_url')->label('h5签约url')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="row">
            <h3 style="margin-left: 100px;">银行卡配置</h3>
        </div>

        <?= $form->field($model, 'bind_card_mode')->label('api绑卡是否需要验证码')->radioList(\common\models\ProductApiConfig::$bind_card_mode_set) ?>
        <?php echo '<div class="bind_card_h5_url" style="'.$bind_card_h5_url.'">' ?>
            <?= $form->field($model, 'bind_card_h5_url')->label('h5跳转地址')->textInput(['maxlength' => true]) ?>
        </div>
        <?= $form->field($model, 'can_list_card')->label('是否支持已绑定卡列表')->radioList(\common\models\ProductApiConfig::$can_list_card_set) ?>

        <?= $form->field($model, 'can_card_second_confirm')->label('是否支持同一卡二次确认')->radioList(\common\models\ProductApiConfig::$can_card_second_confirm_set) ?>

        <?= $form->field($model, 'can_replace_card')->label('是否支持更换还款银行卡')->radioList(\common\models\ProductApiConfig::$can_replace_card_set) ?>

        <div class="row">
            <h3 style="margin-left: 100px;">关键研发配置</h3>
        </div>
        <?= $form->field($model, 'api_url')->label('通用api请求地址')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'api_ua')->label('通用api请求UA')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'api_secret')->label('通用api请求签名秘钥')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'callback_plat_ua')->label('通用api回调平台接口时UA')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'callback_plat_secret')->label('通用api回调平台接口时签名秘钥')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'whitelist')->label('用户白名单')->textarea(['rows' => 6]) ?>
    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$js = <<<JS
$("input[name = 'ProductApiConfig[bind_card_mode]'").on('click', function () {
        if($(this).val() == 2){
            $('.bind_card_h5_url').show();
            $('#product-api-config-form').yiiActiveForm('add', {
                id: "productapiconfig-bind_card_mode",
                name: 'productapiconfig[bind_card_mode]',
                container: '.field-productapiconfig-bind_card_mode',
                input: '#productapiconfig-bind_card_mode',
                error: '.help-block',
                validate:  function(attribute, value, messages, deferred, form) {
                    yii.validation.required(value, messages, {message: "请输入链接"});
                }
            });
        }else{
            $('.bind_card_h5_url').hide();
            $('#set-size-config-form').yiiActiveForm('remove', 'productapiconfig-bind_card_h5_url');
        }
    });

$("input[name = 'ProductApiConfig[is_h5_sign_page]'").on('click', function () {
        if($(this).val() == 1){
            $('.h5_sign_url').show();
            $('#product-api-config-form').yiiActiveForm('add', {
                id: "productapiconfig-h5_sign_url",
                name: 'productapiconfig[h5_sign_url]',
                container: '.field-productapiconfig-h5_sign_url',
                input: '#productapiconfig-h5_sign_url',
                error: '.help-block',
                validate:  function(attribute, value, messages, deferred, form) {
                    yii.validation.required(value, messages, {message: "请输入链接"});
                }
            });
        }else{
            $('.h5_sign_url').hide();
            $('#set-size-config-form').yiiActiveForm('remove', 'productapiconfig-h5_sign_url');
        }
    });
JS;
$this->registerJs($js, \yii\web\View::POS_END);
?>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\ChannelConfig;

/* @var $this yii\web\View */
/* @var $model common\models\ChannelConfig */
/* @var $form yii\widgets\ActiveForm */

$is_general_package = $model->is_general_package ? 'display:none' : 'display:block';
if($model->delivery_terminal){
    $android = 0;
    $ios = 0;
    foreach (ChannelConfig::put_in_map as $k => $v){
        if((strval($model->delivery_terminal) & strval($k)) === strval($k)){
            switch ($k){
                case 1100000000;
                    $android = 1100000000;
                    break;
                default: $ios = $k;
            }
        }
    }
}

?>

<div class="channel-config-form box box-primary">
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

        <?= $form->field($model, 'channel_id')->label('渠道id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'channel_name')->label('渠道名字')->textInput() ?>

        <?= $form->field($model, 'platform_type')->label('平台类型', ['class' => 'field-label'])->dropDownList(ChannelConfig::$plat_type_set, ['class' => 'text-ipt']) ?>

        <?= $form->field($model, 'platform_type')->label('包id', ['class' => 'field-label'])->dropDownList($model->getPackageName(), ['class' => 'text-ipt']) ?>

        <?= $form->field($model, 'platform_type')->label('合作方式', ['class' => 'field-label'])->dropDownList(ChannelConfig::cooperate_mode_map, ['class' => 'text-ipt']) ?>

        <?= $form->field($model, 'is_general_package')->label('是否通用包')->radioList(ChannelConfig::is_general_package_map) ?>

        <?php echo '<div class="is_general_package" style="'.$is_general_package.'">' ?>
            <?= $form->field($model, 'unsign_in_begin_version')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'unsign_in_end_version')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'sign_in_begin_version')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'sign_in_end_version')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'is_show_loan_user')->label('是否通用包')->radioList(ChannelConfig::is_show_loan_user_map) ?>

            <?= $form->field($model, 'show_day')->label('登录用户指定时间展示')->dropDownList(ChannelConfig::show_day_map, ['class' => 'text-ipt']) ?>
        </div>
        <div class="form-inline form-group-sm row top5 offline_repay">
            <span class="col-sm-2 text-right"><label class="control-label form-inline">投放端</label></span>
            <?=Html::checkboxList('android',$android,['1100000000'=>'安卓'],['class'=>'form-control']);?>
            <?=Html::radioList('ios',$ios,['1010000000'=>'ios企业','1001000000'=>'ios官方'],['class'=>'form-control']);?>
        </div>
        <?= $form->field($model, 'h5_template_id')->dropDownList($model->getH5Template(), ['class' => 'text-ipt']) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$js = <<<JS
$("input[name = 'ChannelConfig[is_general_package]']").on('click', function () {
        if($(this).val() == 0){
            $('.is_general_package').show();
        }else{
            $('.is_general_package').hide();
        }
    });

JS;
$this->registerJs($js, \yii\web\View::POS_END);
?>

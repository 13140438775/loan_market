<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Channels */
/* @var $form yii\widgets\ActiveForm */
?>

<?php //$model->gettemplate()?>
<div class="">

    <div class="row">
        <h3 style="margin-left: 100px;">渠道配置</h3>
    </div>

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

    <?= $form->field($model, 'channel_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'merchant_id')->dropDownList(
            [1 => '用钱金卡']
    );
    ?>

    <?= $form->field($model, 'type')->dropDownList(
            $model->market_type_set
    );
    ?>

    <?= $form->field($model, 'cooperation')->dropDownList(
        $model->cooperation_set
    );
    ?>
    <?= $form->field($model, 'channel_id')->textInput(['maxlength' => true]) ?>

    <hr>

    <div class="row">
        <h3 style="margin-left: 100px;">H5配置</h3>
    </div>

    <div class="row">
        <h4 style="margin-left: 100px;">H5配置模板选择:</h4>
    </div>

    <span class="col-sm-2 text-right"></span>
    <div style="float: left; padding: 10px;">
    <?php foreach(\common\models\HtmlManage::find()->all() as $item){ ?>

        <div style="float: left; padding: 10px">
            <div class="create-text-field-container" style="height: 80px;">
                <img src="<?= $item['url'] ? Yii::$app->params['oss']['url_prefix'] . $item['url'] : 'http://temp.im/80x80/0ff/d00' ?>"
                     class="url" height="80" width="80" alt="">
            </div>
        </div>
    <?php } ?>
    </div>

    <div class="clearfix"></div>

    <?php $html_manage = \common\models\HtmlManage::find()->one()?>
    <?php if(isset($html_manage)){ ?>
    <?php $model->template_id = $model-> template_id?> <!--默认选中-->
    <?= $form->field($model, 'template_id',[
        'options' => [
            'class' => 'form-radiox',
        ]])
        ->textInput()->radioList($model->gettemplate())->label(''); ?>
    <?php } ?>
    +
    <hr>

    <div class="row">
        <h3 style="margin-left: 100px;">底部栏显示内容</h3>
    </div>

    <?php $model->is_filling = $model->is_filling; ?>
    <?= $form->field($model, 'is_filling')->label('')->checkboxList($model->filling_type) ?>

    <?php $model->is_company_name = $model->is_company_name; ?>
    <?= $form->field($model, 'is_company_name')->label('')->checkboxList($model->company_type) ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<<JS
$('#channels-template_id').find("label").css('padding-left','30px');

$("#w0").submit(function(){
    // if(!$('#w0').hasClass('form-radiox')){
    //     alert('未找到模板,请先添加模板');
    //     return false;
    // }

    if($('input:radio:checked').length === 0){
          return false;
    }
});
        
JS;
$this->registerJs($js, \yii\web\View::POS_END);
?>
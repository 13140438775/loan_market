<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ChannelsManageSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="common-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'class' => 'common-search-form'
        ]
    ]); ?>

    <?= $form->field($model, 'channel_id', [
        'options' => [
            'class' => 'text-field-container'
        ]])->textInput(['class'=>'text-ipt'])->label('渠道id',['class' => 'field-label']) ?>

    <?= $form->field($model, 'channel_name', [
        'options' => [
            'class' => 'text-field-container'
        ]])->textInput(['class'=>'text-ipt'])->label('渠道名称',['class' => 'field-label']) ?>

    <?= $form->field($model, 'type', [
        'options' => [
            'class' => 'text-field-container'
        ]])->dropDownList(array_merge(['0' => "全部"], $model->market_type_set),['class'=>'form-controlx'])->label('渠道类型',['class' => 'field-label']) ?>

    <?php // echo $form->field($model, 'is_filling') ?>

    <?php // echo $form->field($model, 'is_company_name') ?>

    <?php // echo $form->field($model, 'template_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_id') ?>

    <div class="form-group">
        <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
        <?= Html::Button('重置', ['class' => 'btn btn-default', 'onclick' => 'btn_reset(this)']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>



<?php $hljs = <<<JS
    function btn_reset(obj){
        $(obj).parent().prev().prev().find('input').val('');
        $(obj).parent().prev().prev().prev().find('input').val('');
        $('#channelsmanagesearch-type').val(0);
    }
JS;
?>

<?php $this->registerJs($hljs, \yii\web\View::POS_END); ?>
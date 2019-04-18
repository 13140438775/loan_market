<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ProductTag */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="common-form-container">
    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'common-form'
        ]
    ]); ?>

    <?=
    $form->field($model, 'tag_name', [
        'options' => [
            'class' => 'create-text-field-container'
        ]])->label('标签名称', ['class' => 'field-label'])
        ->textInput(['maxlength' => true, 'class' => 'text-ipt'])
    ?>
    <div class="create-text-field-container" style="height: 80px;">
        <img src="<?= $model->tag_icon ? Yii::$app->params['oss']['url_prefix'] . $model->tag_icon : 'http://temp.im/80x80/0ff/d00' ?>"
             class="tag_icon" height="80" width="80" alt="">
    </div>
    <?= $form->field($model, 'tag_icon', [
        'enableClientValidation' => false,
        'options' => [
            'class' => 'create-text-field-container input-file-container',
        ]])->hint(' ')->label('首页ICON', ['class' => 'field-label'])->fileInput(['value' => '', 'maxlength' => true, 'class' => 'text-ipt',
        'hiddenOptions' => [
            'value' => $model->tag_icon,
        ]]) ?>
    <div class="create-text-field-container" style="height: 72px;">
        <img src="<?= $model->tag_img ? Yii::$app->params['oss']['url_prefix'] . $model->tag_img : "http://temp.im/72x72/0ff/d00" ?>"
             height="72" width="72" class="tag_img" alt="">
    </div>
    <?= $form->field($model, 'tag_img', [
        'enableClientValidation' => false,
        'options' => [
            'class' => 'create-text-field-container',
        ]])->hint(' ')->label('卡片样式图', ['class' => 'field-label'])->fileInput([
        'maxlength' => true,
        'class' => 'text-ipt', 'hiddenOptions' => [
            'value' => $model->tag_img
        ]]) ?>

    <?php //$form->field($model, 'tag', [
    //'options' => [
    //    'class' => 'create-text-field-container'
    //]])->label('唯一标识', ['class' => 'field-label'])->textInput(['maxlength' => true, 'class' => 'text-ipt']) ?>

    <?= $form->field($model, 'sort', [
        'options' => [
            'class' => 'create-text-field-container'
        ]])->label('排序', ['class' => 'field-label'])->textInput(['class' => 'text-ipt']) ?>

    <?= $form->field($model, 'is_enable', [
        'options' => [
            'class' => 'create-text-field-container'
        ]])->label('是否启用', ['class' => 'field-label'])->dropDownList(['0' => '否', '1' => '是'], ['class' => 'text-ipt']) ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$js = <<<JS
//Change id to your id
$("#producttag-tag_img").on("change", function (e) {
    var file = $(this)[0].files[0];
    if(file){
      var upload = new Upload(file,'#producttag-tag_img','.tag_img','.field-producttag-tag_img input[type=hidden]');
      upload.doUpload();
    }
});

$("#producttag-tag_icon").on("change", function (e) {
    var file = $(this)[0].files[0];
    if(file){
      var upload = new Upload(file,'#producttag-tag_icon','.tag_icon','.field-producttag-tag_icon input[type=hidden]');
      upload.doUpload();
    }
});

JS;
$this->registerJs($js, \yii\web\View::POS_END);
?>



<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\H5Template */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="h5-template-form box box-primary">
    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'common-form'
        ]
    ]); ?>
    <div class="box-body table-responsive">



        <?= $form->field($model, 'h5_template_name',[
            'options' => [
                'class' => 'create-text-field-container'
            ]])->label('模板名称', ['class' => 'field-label'])->textInput(['maxlength' => true]) ?>

        <div class="create-text-field-container" style="height: 80px;">
            <img src="<?=  \Yii::$app->params['oss']['url_prefix'] . $model->abbreviation_img  ?>"
                 class="tag_icon" height="80" width="80" alt="">
        </div>
        <?= $form->field($model, 'abbreviation_img', [
            'enableClientValidation' => false,
            'options' => [
                'class' => 'create-text-field-container input-file-container',
            ]])->hint(' ')->label('缩略图', ['class' => 'field-label'])->fileInput(['value' => '', 'maxlength' => true, 'class' => 'text-ipt',
            'hiddenOptions' => [
                'value' => $model->abbreviation_img,
            ]]) ?>

        <div class="create-text-field-container" style="height: 80px;">
            <img src="<?=  \Yii::$app->params['oss']['url_prefix'] . $model->banner_img  ?>"
                 class="tag_icon" height="80" width="80" alt="">
        </div>
        <?= $form->field($model, 'banner_img', [
            'enableClientValidation' => false,
            'options' => [
                'class' => 'create-text-field-container input-file-container',
            ]])->hint(' ')->label('banner图', ['class' => 'field-label'])->fileInput(['value' => '', 'maxlength' => true, 'class' => 'text-ipt',
            'hiddenOptions' => [
                'value' => $model->banner_img,
            ]]) ?>

        <?= $form->field($model, 'background_color',[
            'options' => [
                'class' => 'create-text-field-container'
            ]])->label('背景色：#', ['class' => 'field-label'])->textInput(['maxlength' => true]) ?>

        <div class="create-text-field-container" style="height: 80px;">
            <img src="<?=  \Yii::$app->params['oss']['url_prefix'] . $model->submit_img  ?>"
                 class="tag_icon" height="80" width="80" alt="">
        </div>
        <?= $form->field($model, 'submit_img', [
            'enableClientValidation' => false,
            'options' => [
                'class' => 'create-text-field-container input-file-container',
            ]])->hint(' ')->label('banner图', ['class' => 'field-label'])->fileInput(['value' => '', 'maxlength' => true, 'class' => 'text-ipt',
            'hiddenOptions' => [
                'value' => $model->submit_img,
            ]]) ?>

        <?= $form->field($model, 'is_show_company_main_body',[
            'options' => [
                'class' => 'create-text-field-container'
            ]])->label('是否展示公司主体', ['class' => 'field-label'])->radioList(\common\models\H5Template::$is_show_company_main_body_set) ?>

        <?= $form->field($model, 'is_show_record_number',[
            'options' => [
                'class' => 'create-text-field-container'
            ]])->label('是否展示备案号', ['class' => 'field-label'])->radioList(\common\models\H5Template::$is_show_record_number_set) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
$js = <<<JS
//Change id to your id
$("#h5template-abbreviation_img").on("change", function (e) {
    var file = $(this)[0].files[0];
    if(file){
      var upload = new Upload(file,'#h5template-abbreviation_img','.h5template-abbreviation_img','.field-h5template-abbreviation_img input[type=hidden]');
      upload.doUpload();
    }
});

$("#h5template-banner_img").on("change", function (e) {
    var file = $(this)[0].files[0];
    if(file){
      var upload = new Upload(file,'#h5template-banner_img','.h5template-banner_img','.field-h5template-banner_img input[type=hidden]');
      upload.doUpload();
    }
});

$("#h5template-submit_img").on("change", function (e) {
    var file = $(this)[0].files[0];
    if(file){
      var upload = new Upload(file,'#h5template-submit_img','.h5template-submit_img','.field-h5template-submit_img input[type=hidden]');
      upload.doUpload();
    }
});
JS;
$this->registerJs($js, \yii\web\View::POS_END);
?>

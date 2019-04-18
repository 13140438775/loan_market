<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\HtmlManage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="common-form-container">
    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'common-form'
        ]
    ]); ?>

    <?=
    $form->field($model, 'name', [
        'options' => [
            'class' => 'create-text-field-container'
        ]])->label('模板名称*', ['class' => 'field-label'])
        ->textInput(['maxlength' => true, 'class' => 'text-ipt'])
    ?>

    <div class="create-text-field-container" style="height: 80px;">
        <img src="<?= $model->url ? Yii::$app->params['oss']['url_prefix'] . $model->url : 'http://temp.im/80x80/0ff/d00' ?>"
             class="url" height="80" width="80" alt="">
    </div>

    <?= $form->field($model, 'url', [
        'enableClientValidation' => false,
        'options' => [
            'class' => 'create-text-field-container input-file-container',
        ]])->hint(' ')->label('缩略图*', ['class' => 'field-label'])->fileInput(['value' => '', 'maxlength' => true, 'class' => 'text-ipt',
        'hiddenOptions' => [
            'value' => $model->url,
        ]]) ?>

    <br>
    <br>
    <?=
    $form->field($model, 'param', [
        'options' => [
            'class' => 'create-text-field-container'
        ]])->label('参数', ['class' => 'field-label'])
        ->textInput(['maxlength' => true, 'class' => 'text-ipt'])
    ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<<JS
//Change id to your id
$("#htmlmanage-url").on("change", function (e) {
    var file = $(this)[0].files[0];
    if(file){
      var upload = new Upload(file,'#htmlmanage-url','.url','.field-htmlmanage-url input[type=hidden]');
      upload.doUpload();
    }
});

JS;
$this->registerJs($js, \yii\web\View::POS_END);
?>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\PackageVersions;

/* @var $this yii\web\View */
/* @var $model common\models\PackageVersions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="common-form-container">

    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'common-form'
        ]
    ]); ?>
    <div class="create-text-field-container" style="height: 80px;">
        <img src="<?= $model->url ? Yii::$app->params['oss']['url_prefix'] . $model->url : 'http://temp.im/80x80/0ff/d00' ?>" class="url" height="80" width="80" alt="">
    </div>
    <?= $form->field($model, 'url', [
        'enableClientValidation' => false,
        'options' => [
            'class' => 'create-text-field-container input-file-container',
        ]
    ])->hint(' ')->label('上传', ['class' => 'field-label'])->fileInput([
        'value' => '', 'maxlength' => true, 'class' => 'text-ipt',
        'hiddenOptions' => [
            'value' => $model->url,
        ]
    ]) ?>

    <?= $form->field($model, 'version_id', [
        'options' => [
            'class' => 'create-text-field-container'
        ]
    ])->label('输入版本号', ['class' => 'field-label'])->textInput(['maxlength' => true, 'class' => 'text-ipt']) ?>

    <!-- <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'package_id')->textInput() ?>

    <?= $form->field($model, 'type')->label('端类型')->radioList(PackageVersions::$platform_type_map) ?>

    <?= $form->field($model, 'operator_id')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?> -->

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<<JS
//Change id to your id
$("#packagemanage-url").on("change", function (e) {
    var file = $(this)[0].files[0];
    if(file){
      var upload = new Upload(file,'#packagemanage-url','.tag_img','.field-packagemanage-url input[type=hidden]');
      upload.doUpload();
    }
});

JS;
$this->registerJs($js, \yii\web\View::POS_END);
?> 
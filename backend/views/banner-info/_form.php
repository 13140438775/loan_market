<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \kartik\datetime\DateTimePicker;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\ProductTag */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="common-form-container">
    <?php $form = ActiveForm::begin([
//        'validationUrl' => Url::toRoute(['banner-info/validate-set-tag-config-form','id'=>$model->id]),
        'options' => [
            'class' => 'common-form',
        ]
    ]); ?>

    <?=
    $form->field($model, 'title')->label('标题')->textInput(['maxlength' => true])
    ?>
    <div class="create-text-field-container" style="height: 80px;">
        <img src="<?=  \Yii::$app->params['oss']['url_prefix'] . $model->img  ?>"
             class="tag_icon" height="80" width="80" alt="">
    </div>
    <?= $form->field($model, 'img', [
        'enableClientValidation' => false,
        'options' => [
            'class' => 'create-text-field-container input-file-container',
        ]])->hint(' ')->label('图片', ['class' => 'field-label'])->fileInput(['value' => '', 'maxlength' => true, 'class' => 'text-ipt',
        'hiddenOptions' => [
            'value' => $model->img,
        ]]) ?>
    <?= $form->field($model, 'sort')->label('排序', ['class' => 'field-label'])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'begin_time',[
        'enableClientValidation' => false])->label('开始时间')->widget(DateTimePicker::class, [
        'type' => DateTimePicker::TYPE_INPUT,
        'language' => 'zh-CN',
        'options' => [
            'value' => $model->id ? date('Y-m-d H:i:s', $model->begin_time) : '',
            'autocomplete' => 'off'
        ],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd hh:ii:ss'
        ],
    ])  ?>

    <?= $form->field($model, 'end_time',[
        'enableClientValidation' => false])->label('开始时间')->widget(DateTimePicker::class, [
        'type' => DateTimePicker::TYPE_INPUT,
        'language' => 'zh-CN',
        'options' => [
            'value' => $model->id ? date('Y-m-d H:i:s', $model->end_time) : '',
            'autocomplete' => 'off'
        ],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd hh:ii:ss'
        ],
    ])  ?>


    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$js = <<<JS
//Change id to your id
$("#bannerinfo-img").on("change", function (e) {
    var file = $(this)[0].files[0];
    if(file){
      var upload = new Upload(file,'#bannerinfo-img','.bannerinfo-img','.field-bannerinfo-img input[type=hidden]');
      upload.doUpload();
    }
});
JS;
$this->registerJs($js, \yii\web\View::POS_END);
?>



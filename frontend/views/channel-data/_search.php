<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model frontend\models\ChannelDataSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="channel-data-search common-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'class' => 'common-search-form'
        ]
    ]); ?>

    <?php echo $form->field($model,'date_begin')->widget(DatePicker::class, [
        'type' => DatePicker::TYPE_INPUT,
        'language' => 'zh-CN',
        'options' => [
            'value' => '',
            'autocomplete' => 'off'
        ],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ])->textInput(['class'=>'text-ipt', 'value' => '默认显示当天数据'])->label('日期',['class' => 'field-label']);
    ?>

    <?php echo $form->field($model,'date_end')->widget(DatePicker::class, [
        'type' => DatePicker::TYPE_INPUT,
        'language' => 'zh-CN',
        'options' => [
            'value' => '',
            'autocomplete' => 'off'
        ],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ])->textInput(['class'=>'text-ipt', 'value' => '默认显示当天数据'])->label(' -- ',['class' => 'field-label']);
    ?>

    <?= $form->field($model, 'channel_id', [
        'options' => [
            'class' => 'text-field-container'
        ]])->textInput(['class'=>'text-ipt', 'value' => ''])->label('渠道ID',['class' => 'field-label'])
    ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

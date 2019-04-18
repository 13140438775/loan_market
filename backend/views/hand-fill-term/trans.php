<?php
/**
 * Created by PhpStorm.
 * User: suns
 * Date: 2019-03-15
 * Time: 13:31
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\web\JsExpression;
use \kartik\select2\Select2;
use \yii\widgets\Pjax;

?>

<div class="hand-fill-term-form box box-primary">
    <div style="margin-top:10px;margin-left: 10px;margin-bottom: 10px;">
    <?php Pjax::begin([
        'enablePushState' => false, 'enableReplaceState' => false
    ]); ?>
    <?php $form = ActiveForm::begin(
        [
            'action' => \yii\helpers\Url::to('/hand-fill-term/trans'),
            'options' => ['data-pjax' => '0'],
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
                switch ($attr) {
                    default:
                }
                return $default;
            }
        ]); ?>
    <?= Html::textarea('text', Yii::$app->request->post('text'), ['style' => 'width:400px;']) ?>
    <?= Html::submitButton('转换', ['class' => 'btn btn-success btn-flat']) ?>
    <?php ActiveForm::end(); ?>
    <pre style="width: 1000px;height: 200px;word-wrap : break-word ; word-break:break-all;"><?= $jsonString ?? '' ?></pre>
    <?php Pjax::end(); ?>
    </div>
</div>

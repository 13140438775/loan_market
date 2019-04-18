<?php

use yii\helpers\Html;
use \yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = '权重配置';
$this->params['breadcrumbs'][] = ['label' => 'Mk Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="channels-update">

        <h4><?= Html::encode($this->title) ?></h4>
        <div class="mk-product-form box box-primary">
            <?php $form = ActiveForm::begin(
                [
                    'id' => 'weight-update-form',
                    'enableAjaxValidation' => true,
                    'validationUrl' => Url::toRoute(['product/validate-weight-form','id'=>$model->id]),
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
            <div class="box-body table-responsive" style="overflow-x:hidden;">
                <?= $form->field($model, 'weight',[ 'inputOptions'=>[
                        'placeholder'=>'原始权重值,范围0到100 两位有效数字',
                        'value' => $model->weight === 0 ? '' : $model->weight
                ]])->textInput(['maxlength' => true]) ?>

            </div>
            <div class="box-footer">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

<?php

use yii\helpers\Html;
use \yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = '线上配置';
$this->params['breadcrumbs'][] = ['label' => 'Mk Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
    <div class="channels-update">

        <h1><?= Html::encode($this->title) ?></h1>
        <div class="mk-product-form box box-primary">
            <?php $form = ActiveForm::begin(
                [
                    'id' => 'weight-update-form',
                    'enableAjaxValidation' => true,
                    'validationUrl' => Url::toRoute(['product/validate-set-tag-config-form','id'=>$model->id]),
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

                <?= $form->field($model, 'tagIds')->label('筛选标签 *')->checkboxList(\common\models\ProductTag::getIdMapName()) ?>
                <?= $form->field($model, 'show_tag_id')->radioList(array_merge(['0' => "无"], \common\models\ProductTag::getIdMapName())) ?>

            </div>
            <div class="box-footer">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

<?php

use yii\helpers\Html;
use \yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = '场景配置';
$this->params['breadcrumbs'][] = ['label' => 'Mk Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
    <div class="channels-update">

        <h1><?= Html::encode($this->title) ?></h1>
        <div class="mk-product-form box box-primary">
            <?php $form = ActiveForm::begin(
                [
                    'id' => 'weight-update-form',
                    'enableAjaxValidation' => false,
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

                <?php
                if(!$model->isNewRecord){
                    $checkList = [];
                    foreach (\common\models\Product::$scenario_set as $k => $v){
                        if((strval($model->online_scenario) & strval($k)) === strval($k)){
                            $checkList[] = strval($k);
                        }
                    }
                    $model->online_scenario = $checkList;
                }
                ?>
                <?= $form->field($model, 'online_scenario')->label('在线场景')->checkboxList(\common\models\Product::$scenario_set) ?>
                <?php
                if(!$model->isNewRecord){
                    $checkList = [];
                    foreach (\common\models\Product::$visible_set as $k => $v){
                        if((strval($model->visible) & strval($k)) === strval($k)){
                            $checkList[] = strval($k);
                        }
                    }
                    $model->visible = $checkList;
                }
                ?>
                <?= $form->field($model, 'visible')->label('可见逻辑')->checkboxList(\common\models\Product::$visible_set) ?>
                <?php
                if(!$model->isNewRecord){
                    $checkList = [];
                    foreach (\common\models\Product::$platform_visible_set as $k => $v){
                        if((strval($model->visible_mobile) & strval($k)) === strval($k)){
                            $checkList[] = strval($k);
                        }
                    }
                    $model->visible_mobile = $checkList;
                }
                ?>
                <?= $form->field($model, 'visible_mobile')->label('可见端')->checkboxList(\common\models\Product::$platform_visible_set) ?>

            </div>
            <div class="box-footer">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

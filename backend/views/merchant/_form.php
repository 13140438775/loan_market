<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Merchant */
/* @var $form yii\widgets\ActiveForm */
/* @var $contactModels array */
?>

<div class="merchant-form box box-primary">
    <?php $form = ActiveForm::begin(
        [
            'options' => [],
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


    <div class="box-body table-responsive">

        <?= $form->field($model, 'company_name')->textInput(['maxlength' => true])?>
        <?= $form->field($model, 'mark')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
        <h5>添加联系人</h5>
        <?= Html::button('增加', ['class' => 'btn add-contact btn-success btn-flat']) ?>
        <div id="contacts-container" class="row" >
            <?php
            if(!$model->id) {
                echo '<div class="col-sm-6 contact-item">';
                echo "<hr>";
                echo  Html::button('删除', ['class' => 'btn del-contact btn-success btn-flat']);
                echo  $form->field($contactModels[0], 'contacts_name')->textInput(['maxlength' => true, 'name' => 'MerchantContacts[0][contacts_name]']);
                echo  $form->field($contactModels[0], 'contacts_phone')->textInput(['maxlength' => true, 'name' => 'MerchantContacts[0][contacts_phone]']);
                echo  $form->field($contactModels[0], 'email')->textInput(['maxlength' => true, 'name' => 'MerchantContacts[0][email]']);
                echo  $form->field($contactModels[0], 'wx')->textInput(['maxlength' => true, 'name' => 'MerchantContacts[0][wx]']);
                echo '</div>';
            }else {
                foreach ($contactModels as $index => $contactModel) {
                    echo '<div class="col-sm-6 contact-item">';
                    echo '<hr>';
                    echo Html::button('删除', ['class' => 'btn del-contact btn-success btn-flat']);
                    echo $form->field($contactModels[$index], 'contacts_name')->textInput(['maxlength' => true, 'name' => "MerchantContacts[$index][contacts_name]"]);
                    echo $form->field($contactModels[$index], 'contacts_phone')->textInput(['maxlength' => true, 'name' => "MerchantContacts[$index][contacts_phone]"]);
                    echo $form->field($contactModels[$index], 'email')->textInput(['maxlength' => true, 'name' => "MerchantContacts[$index][email]"]);
                    echo $form->field($contactModels[$index], 'wx')->textInput(['maxlength' => true, 'name' => "MerchantContacts[$index][wx]"]);
                    echo "</div>";
                }
            }
            ?>
        </div>
        <div class="row">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
        </div>

        <?php ActiveForm::end(); ?>
        <script type="text/template" id="contacts">
            <div class='col-sm-6 contact-item' >
                <hr>
                <?= Html::button('删除', ['class' => 'btn del-contact btn-success btn-flat']) ?>
                <?= $form->field($contactModels[0], 'contacts_name')->textInput(['maxlength' => true, 'name' => 'MerchantContacts[{{index}}][contacts_name]']) ?>
                <?= $form->field($contactModels[0], 'contacts_phone')->textInput(['maxlength' => true, 'name' => 'MerchantContacts[{{index}}][contacts_phone]']) ?>
                <?= $form->field($contactModels[0], 'email')->textInput(['maxlength' => true, 'name' => 'MerchantContacts[{{index}}][email]']) ?>
                <?= $form->field($contactModels[0], 'wx')->textInput(['maxlength' => true, 'name' => 'MerchantContacts[{{index}}][wx]']) ?>
            </div>
        </script>

        <?php
        $length = $model->isNewRecord ? 0:count($contactModels)-1;
        $js = <<<JS
                    var tpl   =  $("#contacts").html();
                    var data = {index:{$length}};
                    var template = Handlebars.compile(tpl);
                    //绑定添加
  $('.add-contact').click(function() {
        data.index++;
        console.log(data);
        var html = template(data);
        $('#contacts-container').append(html);
      });
  //绑定删除
  $('#contacts-container').on('click','.del-contact',function() {
    $(this).closest('.contact-item').remove();
  })
  
JS;
        $this->registerJs($js, \yii\web\View::POS_END);

        ?>



<?php

use yii\helpers\Html;
use \yii\widgets\ActiveForm;
use yii\helpers\Url;
use \common\models\ProductProperty;



/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = '产品属性配置API';
$this->params['breadcrumbs'][] = $this->title;

$can_manual_repay_style_display = $model->can_manual_repay ?  'display:block': 'display:none';
$can_offline_repay_style_display = $model->can_offline_repay ?  'display:block': 'display:none';
$can_manual_repay_json = $model->manual_repay_detail;
$can_manual_repay = json_decode($can_manual_repay_json,true);
$single = $can_manual_repay['one']['repayment_mode'];
$multi = $can_manual_repay['more']['repayment_mode'];
$combine = $can_manual_repay['more']['overdue_need_combine'];

$offline_repay_detail_json = $model->offline_repay_detail;
$offline_repay_detail = json_decode($offline_repay_detail_json,true);
$offline_type = $offline_repay_detail['offline_repay_type'];
if(!empty($offline_type)){
    $wx_style_display = $offline_repay_detail['wx']['wx_account'] ?  'display:block': 'display:none';
    $ali_style_display = $offline_repay_detail['ali']['ali_account'] ?  'display:block': 'display:none';
    $other_style_display = $offline_repay_detail['other']['other_account'] ?  'display:block': 'display:none';
}else{
    $wx_style_display = $ali_style_display = $other_style_display = 'display:none';
    $offline_repay_detail['wx']['wx_account'] = $offline_repay_detail['ali']['ali_account'] = $offline_repay_detail['other']['other_account'] ='';
    $offline_repay_detail['wx']['wx_account_name'] = $offline_repay_detail['ali']['ali_account_name'] = $offline_repay_detail['other']['other_account_name'] ='';
    $offline_repay_detail['wx']['wx_remark'] = $offline_repay_detail['ali']['ali_remark'] = $offline_repay_detail['other']['other_remark'] ='';
}

?>
    <div class="channels-update">
        <div class="mk-product-form box box-primary">
            <?php $form = ActiveForm::begin(
                [
                    'id' => 'weight-update-form',
//                    'enableAjaxValidation' => true,
//                    'validationUrl' => Url::toRoute(['product/validate-set-tag-config-form','id'=>$model->id]),
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

                <div class="row">
                    <h3 style="margin-left: 100px;">产品页面元素配置</h3>
                </div>
                <?= $form->field($model, 'is_show_fee_txt')->label('费率文案是否展示')->radioList(ProductProperty::$is_show_fee_txt_set) ?>

                <?= $form->field($model, 'is_show_desc_entry')->label('产品说明入口是否展示')->radioList(ProductProperty::$is_show_desc_entry_set) ?>

                <?= $form->field($model, 'interest_desc')->label('利率说明')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'repay_type')->label('还款方式')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'ahead_repay')->label('提前还款')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'overdue_desc')->label('逾期政策')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'hotline')->label('客服电话')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'offline_service')->label('线下客服号')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'robot_url')->label('智能客服地址')->textInput(['maxlength' => true]) ?>
                <div class="row">
                    <h3 style="margin-left: 100px;">产品页面元素配置</h3>
                </div>
                <?= $form->field($model, 'can_manual_repay')->label('是否支持主动还款')->radioList(ProductProperty::$can_manual_repay_set) ?>

                <?php echo '<div class="terms" style="'.$can_manual_repay_style_display.'">' ?>
                    <div class="navs">
                        <div class="nav-item">单期</div>
                        <div class="nav-item">多期</div>
                    </div>
                    <div class="content">
                        <div class="content-item">
                            <p><label><input type="radio" name="single" value="1">可以提前还款</label></p>
                            <p><label><input type="radio" name="single" value="2">不可以提前还款</label></p>
                        </div>
                        <div class="content-item">
                            <p><label><input type="radio" name="multi" value="1">只可以提前还款</label></p>
                            <p><label><input type="radio" name="multi" value="2">可以提前还任意期数</label></p>
                            <p><label ><input type="radio" name="multi" value="3">只可以还当前期数</label></p>
                            <div class="line"></div>
                            <div class="overtime more">
                                <p>逾期是否可以和当前订单合并</p>
                                <p class="overtime-items">
                                    <label><input name="combine" value="1" type="radio">是</label>
                                    <label><input name="combine" value="2" type="radio">否</label>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <?= $form->field($model, 'can_offline_repay')->label('是否支持主动还款')->radioList(ProductProperty::$can_offline_repay_set) ?>
                <?php echo '<div class="offline_repay" style="'.$can_offline_repay_style_display.'">' ?>
                    <div class="form-inline form-group-sm row top5 ">
                        <span class="col-sm-2 text-right"><label class="control-label form-inline">线下还款类型</label></span>
                        <?= Html::checkboxList('offline_repay_type',$offline_type,ProductProperty::$manual_repayment_type_set) ?>
                    </div>

                    <?php echo '<div class="wx" style="'.$wx_style_display.'">' ?>
                        <h5 style="margin-left: 250px;">微信配置</h5>
                        <div class="form-inline form-group-sm row top5 offline_repay">
                            <span class="col-sm-2 text-right"><label class="control-label form-inline">还款账号</label></span>
                            <?=Html::input('text','wx_account',$offline_repay_detail['wx']['wx_account'],['class'=>'form-control','placeholder'=>'还款账号','style'=>'width:400px;']);?>
                        </div>
                        <div class="form-inline form-group-sm row top5 offline_repay">
                            <span class="col-sm-2 text-right"><label class="control-label form-inline">还款账户名称</label></span>
                            <?=Html::input('text','wx_account_name',$offline_repay_detail['wx']['wx_account_name'],['class'=>'form-control','placeholder'=>'还款账户名称','style'=>'width:400px;']);?>
                        </div>
                        <div class="form-inline form-group-sm row top5 offline_repay">
                            <span class="col-sm-2 text-right"><label class="control-label form-inline">打款备注</label></span>
                            <?=Html::input('text','wx_remark',$offline_repay_detail['wx']['wx_remark'],['class'=>'form-control','placeholder'=>'打款备注','style'=>'width:400px;']);?>
                        </div>
                    </div>

                    <?php echo '<div class="ali" style="'.$ali_style_display.'">' ?>
                        <h5 style="margin-left: 250px;">支付宝配置</h5>
                        <div class="form-inline form-group-sm row top5 offline_repay">
                            <span class="col-sm-2 text-right"><label class="control-label form-inline">还款账号</label></span>
                            <?=Html::input('text','ali_account',$offline_repay_detail['ali']['ali_account'],['class'=>'form-control','placeholder'=>'还款账号','style'=>'width:400px;']);?>
                        </div>
                        <div class="form-inline form-group-sm row top5 offline_repay">
                            <span class="col-sm-2 text-right"><label class="control-label form-inline">还款账户名称</label></span>
                            <?=Html::input('text','ali_account_name',$offline_repay_detail['ali']['ali_account_name'],['class'=>'form-control','placeholder'=>'还款账户名称','style'=>'width:400px;']);?>
                        </div>
                        <div class="form-inline form-group-sm row top5 offline_repay">
                            <span class="col-sm-2 text-right"><label class="control-label form-inline">打款备注</label></span>
                            <?=Html::input('text','ali_remark',$offline_repay_detail['ali']['ali_remark'],['class'=>'form-control','placeholder'=>'打款备注','style'=>'width:400px;']);?>
                        </div>
                    </div>

                    <?php echo '<div class="other" style="'.$other_style_display.'">' ?>
                        <h5 style="margin-left: 250px;">其他配置</h5>
                        <div class="form-inline form-group-sm row top5 offline_repay">
                            <span class="col-sm-2 text-right"><label class="control-label form-inline">还款账号</label></span>
                            <?=Html::input('text','other_account',$offline_repay_detail['other']['other_account'],['class'=>'form-control','placeholder'=>'还款账号','style'=>'width:400px;']);?>
                        </div>
                        <div class="form-inline form-group-sm row top5 offline_repay">
                            <span class="col-sm-2 text-right"><label class="control-label form-inline">还款账户名称</label></span>
                            <?=Html::input('text','other_account_name',$offline_repay_detail['other']['other_account_name'],['class'=>'form-control','placeholder'=>'还款账户名称','style'=>'width:400px;']);?>
                        </div>
                        <div class="form-inline form-group-sm row top5 offline_repay">
                            <span class="col-sm-2 text-right"><label class="control-label form-inline">打款备注</label></span>
                            <?=Html::input('text','other_remark',$offline_repay_detail['other']['other_remark'],['class'=>'form-control','placeholder'=>'打款备注','style'=>'width:400px;']);?>
                        </div>
                    </div>
                </div>

            </div>
            <div class="box-footer">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
<?php
$css = <<<CSS
.terms{width: 800px; margin: 60px auto;}
.navs{ display: flex;}
.nav-item{ width: 120px; text-align: center; height: 30px; line-height: 30px; border-radius: 6px; border: 1px solid #dddddd; color: #333333;}
.nav-item.on{ background: #c00; color: #ffffff}
.content{ background: #F9F9F9; width: 100%; padding: 20px ;}
.content-item p{ line-height: 30px;  }
.line{ height:0; border-bottom: 1px dashed #333333; margin: 10px 0;}
.overtime-items{ display: flex; align-items: center; justify-content: space-between; width: 120px;}
CSS;
$this->registerCss($css);
$js = <<<JS

$(function(){
    var chooseType=0;
    setNavStyle();
    setRadio();
    $(".nav-item").click(function(){
        chooseType=$(this).index();
        setNavStyle();
    })

    function setNavStyle(){
        $(".nav-item").removeClass('on').eq(chooseType).addClass('on');
        $(".content-item").hide().eq(chooseType).show();
    }
    
    function setRadio(){
        var single = {$single};
        var multi = {$multi};
        var combine = {$combine};
        if(single != '') $("input:radio[name=single][value="+single+"]").attr("checked",true);  
        if(multi != '') $("input:radio[name=multi][value="+multi+"]").attr("checked",true);  
        if(combine != '') $("input:radio[name=combine][value="+combine+"]").attr("checked",true);
    }
    
    $('input[name="offline_repay_type[]"]').change(function(){
        
        var arr = new Array();
        $('input[name="offline_repay_type[]"]:checked').each(function(){
            if ($(this).prop('checked') == true) {
                arr.push($(this).val());
            }
        });
        if(arr.indexOf("1") > -1){
            $('.wx').show();
        }else{
            $('.wx').hide();
        }
        if(arr.indexOf("2") > -1){
            $('.ali').show();
        }else{
            $('.ali').hide();
        }
        if(arr.indexOf("3") > -1){
            $('.other').show();
        }else{
            $('.other').hide();
        }

    });

    
    $("input[name = 'ProductProperty[can_manual_repay]']").on('click', function () {
        if($(this).val() == 1){
            $('.terms').show();
        }else{
            $('.terms').hide();
        }
    });
    
     $("input[name = 'ProductProperty[can_offline_repay]']").on('click', function () {
        if($(this).val() == 1){
            $('.offline_repay').show();
        }else{
            $('.offline_repay').hide();
        }
    });

})

JS;
$this->registerJs($js, \yii\web\View::POS_END);
?>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \common\models\Product;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
/* @var $productTermDetailModels array */

?>

<div class="mk-product-form box box-primary">

    <?php $form = ActiveForm::begin(
        [
            'enableAjaxValidation' => true,
            'validationUrl' => Url::toRoute(['product/update-fee-validate', 'id' => $model->id]),
            'enableClientValidation' => true,
            'id' => 'fee-config-form',
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
                    case 'single_fee':
                        {//是否固定费率 联动
                            if ($model->is_same_interest == 1) {
                                $default['options']['style'] = 'display:block';
                            } else {
                                $default['options']['style'] = 'display:none';
                            }
                            break;
                        }
                    case 'incr_step':
                        {
                            if ($model->is_fixed_step == 1) {
                                $default['options']['style'] = 'display:block';
                            } else {
                                $default['options']['style'] = 'display:none';
                            }
                            break;
                        }
                    default:
                }
                return $default;
            }
        ]); ?>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive">
        <?= $form->field($model, 'min_amount', ['inputOptions' => [
            'class' => 'form-control',
            'style' => 'width:150px;',
        ]])->textInput() ?>

        <?= $form->field($model, 'max_amount', ['inputOptions' => [
            'class' => 'form-control',
            'style' => 'width:150px;',
        ]])->textInput() ?>

        <?= $form->field($model, 'incr_amount_step', ['inputOptions' => [
            'class' => 'form-control',
            'style' => 'width:150px;',
        ]])->label('额度递增粒度')->textInput() ?>

        <?= $form->field($model, 'product_type')->label('产品属性')->radioList(Product::$product_type_set) ?>

        <?= $form->field($model, 'is_fixed_step')->label('是否是固定期限粒度')->radioList(Product::$is_fixed_step_set) ?>

        <?= $form->field($model, 'min_term', ['inputOptions' => [
            'class' => 'form-control',
            'style' => 'width:150px;'
        ]])->label('最低期限')->textInput() ?>

        <?= $form->field($model, 'max_term', ['inputOptions' => [
            'class' => 'form-control',
            'style' => 'width:150px;',
        ]])->label('最高期限')->textInput() ?>

        <?= $form->field($model, 'incr_step', [
            'enableClientValidation' => true,
            'inputOptions' => [
                'class' => 'form-control',
                'style' => 'width:150px;',
            ]])->label('期限递增粒度')->textInput() ?>

        <?= $form->field($model, 'is_same_interest')->label('是否统一费率')->radioList(Product::$is_same_interest_set) ?>
        <?= $form->field($model, 'single_fee', ['inputOptions' => [
            'class' => 'form-control',
            'style' => 'width:150px;',
        ]])->label('每期费率')->textInput() ?>

        <?= $form->field($model, 'term_type')->label('期限范围')->radioList(Product::$term_type_set) ?>

        <?= $form->field($model, 'single_interest', ['inputOptions' => [
            'class' => 'form-control',
            'style' => 'width:150px;',
        ]])->label('每期利率')->textInput() ?>

        <button id="get-lines" type="button">生成</button>
        <h4>期限详情 <span style="font-size: 12px;color: red;" class="term-warning"></span></h4>
        <div class="box term-detail-container" style="margin-top: 30px; padding: 20px;">
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <script type="text/template" id="term-details">
        {{#each lines ~}}
        <div class="row term-detail-item">
            <button type="button" class="delete-item">删除</button>
            <label style="margin-right: 5px;" for="">借款金额</label>
            <input style="width: 100px;" class="amount" type="text" value="{{~amount}}"
                   name="ProductTermDetail[{{~id}}][amount]">
            <label style="margin-right: 5px;" for="">期限 </label>
            <input style="width: 100px;" class="sum_time" type="text" {{ sum_time_writable sum_time}}
                   value="{{~sum_time}}" name="ProductTermDetail[{{~id}}][sum_time]">
            <label style="margin-right: 5px;"  for="">期限单位 </label><select class="sum_time_unit" name="ProductTermDetail[{{~id}}][sum_time_unit]">
                {{ get_unit term_time_unit }}
            </select>
            <label style="margin-right: 5px;" for="">每期时长 </label>
            <input style="width: 100px;" class="term_time" type="text" {{ term_time_writable product_type }}
                   value="{{~term_time}}" name="ProductTermDetail[{{~id}}][term_time]">
            <label style="margin-right: 5px;" for="">每期时长单位 </label>
            <select class="term_time_unit" name="ProductTermDetail[{{~id}}][term_time_unit]">
                {{ get_unit term_time_unit }}
            </select>
            <label style="margin-right: 5px;" for="">每期利率 </label>
            <input class="term_apr" style="width: 100px;" {{ term_apr_writable term_apr }} value="{{~term_apr}}"
                   type="text" name="ProductTermDetail[{{~id}}][term_apr]">
            <label style="margin-right: 5px;" for="">每期利率 </label>
            <input class="term_fee" style="width: 100px;" {{ term_fee_writable term_fee }} type="text"
                   value="{{~term_fee}}" name="ProductTermDetail[{{~id}}][term_fee]">
        </div>
        {{~/each}}
    </script>
</div>

<?php
$css = <<<CSS
.term-detail-container input[type="text"]:read-only {
  background: #dddddd;
}
.term-detail-container input[type="text"] {
  width: 100px;
}
.term-detail-container {
  font-size: 12px;
}


CSS;
$this->registerCss($css);

$js = <<<JS
  //如果是单期 非固定期限粒度 编辑期限 联动每期时长
  $('.term-detail-container').on('change','.term-detail-item .sum_time',function() {
    var config = get_config();
    if(config.product_type === '1' && config.is_fixed_step === '2') {// 单期 非固定期限粒度 联动
      $(this).closest('.term-detail-item').find('.term_time').val($(this).val());
    }
  });
  $('.term-detail-container').on('click','.delete-item',function() {
    $(this).closest('.term-detail-item').remove();
  });

  $.isInt = function(n) {
    return +n === n && !(n % 1);
  };
  //获取全局配置
  function get_config() {
    var config = {};
    config.min_amount = $('input[name=Product\\\\[min_amount\\\\]]').val();//最小值
    config.min_amount = parseInt(config.min_amount);
    
    config.max_amount = $('input[name=Product\\\\[max_amount\\\\]]').val();//最大值 
    config.max_amount = parseInt(config.max_amount);
    
    config.incr_amount_step = $('input[name=Product\\\\[incr_amount_step\\\\]]').val();//期步长
    config.incr_amount_step = parseInt(config.incr_amount_step)?parseInt(config.incr_amount_step):0;
    
    config.product_type = $("input[name=Product\\\\[product_type\\\\]]:checked").val();//产品属性单期 多期
    config.is_fixed_step = $("input[name=Product\\\\[is_fixed_step\\\\]]:checked").val();//是否固定期限粒度
    config.is_same_interest = $("input[name=Product\\\\[is_same_interest\\\\]]:checked").val();//是否相同费率
    config.term_type = $("input[name=Product\\\\[term_type\\\\]]:checked").val();//期限范围类型
    config.min_term = $('input[name=Product\\\\[min_term\\\\]]').val();//做小期限
    config.min_term = parseInt(config.min_term)?parseInt(config.min_term):0;//做小期限

    config.max_term = $('input[name=Product\\\\[max_term\\\\]]').val();//最大期限 
    config.max_term = parseInt(config.max_term)?parseInt(config.max_term):0;//做大期限

    config.incr_step = $('input[name=Product\\\\[incr_step\\\\]]').val();//期限递增粒度
    config.incr_step = parseInt(config.incr_step)? parseInt(config.incr_step):0;
    
    config.single_interest = $('input[name=Product\\\\[single_interest\\\\]]').val();//每期利率
    config.single_fee = $('input[name=Product\\\\[single_fee\\\\]]').val();//期步长
    return config;
  }
  function show_warning(text) {
    $('.term-warning').html(text);
    setTimeout(function() {
      $('.term-warning').html('');
    },9000)
  }
  //计算期限详情
  function calc(){
    //定义期数
    var result = [];
    //获取配置
    var config = get_config();
    var amount_line_count = 1;// 额度个数
    var time_line_count = 1;// 期限个数
    
    
    //计算额度个数
    if(config.min_amount <= config.max_amount && config.min_amount > 0 && config.incr_amount_step > 0) {
        amount_line_count = (config.max_amount - config.min_amount)/config.incr_amount_step + 1;
        if(!$.isInt(amount_line_count)){
          show_warning('额度递增粒度不合适,不能整除');
          return false;
        }
    }
    
    if(config.product_type === '1'){//如果是单期
      if(config.is_fixed_step === '1' && config.incr_step > 0){//如果是固定期期限粒度 固定步长 按最低期限 最高期限
        time_line_count = (config.max_term - config.min_term)/config.incr_step + 1; //按照期限递增粒度计算
        if(!$.isInt(time_line_count)){
          show_warning('期限递增粒度不合适,不能整除');
          return false;
        }
      }
    }
    if(amount_line_count > 12 || time_line_count > 12){
      show_warning('额度递增粒度 期限递增粒度太小');
      return false;
    }
    //计算每期
    var index = 0;
    for(var i = 0; i<amount_line_count; i++){
      for (var j = 0; j<time_line_count; j++){
        result.push({
          id: index,
          sum_time: config.is_fixed_step === '2' ? '': config.incr_step * j + config.min_term, //期限 非固定粒度 为空可以手填
          amount:config.incr_amount_step * i + config.min_amount,//额度
          sum_time_unit:config.term_type,//日期类型
          term_time: config.product_type ==='1' ? config.incr_step * j + config.min_term :'' ,//每期时长多期需要填 单期禁止
          term_time_unit:config.term_type,//日期类型
          term_apr:config.is_same_interest === '1' ? config.single_interest:'0',//如果是固定费率去利率 如果不是固定利率 要手动填写
          term_fee:config.is_same_interest === '1' ? config.single_fee:'', //每期费率
          can_copy: config.is_same_interest !== '1' || config.is_fixed_step !== '1' ? true : false //非固定期限粒度 非固定费率 自己填 需要可以copy
        });
        index++;
      }
    }
    // console.log(result);
    return result;
  }
  var tpl   =  $("#term-details").html();
  var template = Handlebars.compile(tpl);
  //利率不可写
  Handlebars.registerHelper('term_apr_writable',function(term_apr,options) {
    return 'readonly';
  });
  //固定费率 不可写 非固定费率可以写
  Handlebars.registerHelper('term_fee_writable',function(term_apr,options) {
    var config = get_config();
    if(config.is_same_interest === '1'){
      return 'readonly';
    }else{
      return '';
    }
  });
  
  //每期时长 如果是固定期限粒度不允许修改
  Handlebars.registerHelper('term_time_writable',function(product_type,options) {
    var config = get_config();
    if(config.product_type === '1'){ //如果是单期 不可以写每期时长
      return 'readonly';
    }else{ //如果是多期 可以写
      return '';
    }
  });
  
  Handlebars.registerHelper('sum_time_writable',function(term_time,options) {
    var config = get_config();
    if(config.is_fixed_step === '1'){
      return 'readonly';
    }else{
      return '';
    }
  });
  //期限单位
  Handlebars.registerHelper('get_unit',function(term_unit,options) {
    var config = get_config();
    var r;
    if(config.term_type === '1'){
      r = '<option value="1" selected >日</option> <option disabled value="2">月</option><option disabled value="3">年</option>';
    }else if(config.term_type === '2'){
      r = '<option value="1" disabled >日</option> <option  selected value="2">月</option><option value="3" disabled>年</option>';
    }else{
      r = '<option value="1" disabled >日</option> <option value="2" disabled>月</option><option value="3" selected>年</option>';
    }
    return new Handlebars.SafeString(r);
  });
  
  function render() {
    var lines = calc();
    if(lines && lines.length > 0){
      var result = {
        lines:calc()
      };
      var html = template(result);
      $('.term-detail-container').html(html);
    }  
  }
  function initRender(){
      var result = {
        lines:{$productTermDetailModels}
      };
      var html = template(result);
      $('.term-detail-container').html(html);
  }
  initRender();
  //绑定添加
  $('.add-contact').click(function() {
        data.index++;
        console.log(data);
        var html = template(data);
        $('#contacts-container').append(html);
  });
  //绑定删除
  $('input[name=Product\\\\[min_amount\\\\]], ' +
    'input[name=Product\\\\[max_amount\\\\]], ' +
    'input[name=Product\\\\[incr_amount_step\\\\]], ' +
    'input[name=Product\\\\[product_type\\\\]], ' +
    'input[name=Product\\\\[is_fixed_step\\\\]], ' +
    'input[name=Product\\\\[is_same_interest\\\\]], ' +
    'input[name=Product\\\\[term_type\\\\]], ' +
    'input[name=Product\\\\[min_term\\\\]], ' +
    'input[name=Product\\\\[incr_step\\\\]], ' +
    'input[name=Product\\\\[single_interest\\\\]], ' +
    'input[name=Product\\\\[single_fee\\\\]]').change(function() {
      render();
  });
  $('#get-lines').click(function() {
    render();
  });
  //是否是固定期限粒度
  $('input[name=Product\\\\[is_fixed_step\\\\]]').change(function() {
    if($(this).val() === '1'){ //固定期限粒度
      $('.field-product-incr_step').show();
      // $('#fee-config-form').yiiActiveForm('add', {
      //     id: 'product-incr_step',
      //     name: 'Product[incr_step]',
      //     container: '.field-product-incr_step',
      //     input: '#product-incr_step',
      //     error: '.help-block',
      //    validate:  function (attribute, value, messages, deferred, form) {
      //      yii.validation.required(value, messages, {message: "xxxxxxxx"});
      //    }
      // });
    }else{
      // var x = $('#fee-config-form').yiiActiveForm('remove', 'product-incr_step'); //删除期限粒度验证
      $('.field-product-incr_step').hide();
    }
  });
  
  //是否统一费率
  $('input[name=Product\\\\[is_same_interest\\\\]]').change(function() {
    if($(this).val() === '1'){ //统一
      $('.field-product-single_fee').show();
      $('#fee-config-form').yiiActiveForm('add', {
          id: 'product-single_fee',
          name: 'Product[single_fee]',
          container: '.field-product-single_fee',
          input: '#product-single_fee',
          error: '.help-block',
         // validate:  function (attribute, value, messages, deferred, form) {
         //   yii.validation.required(value, messages, {message: "xxxxxxxx"});
         // }
      });
    }else{
      $('.field-product-single_fee').hide();
      $('#fee-config-form').yiiActiveForm('remove', 'product-single_fee'); //删除费率验证
    }
  });
  //验证详细数据
  function validateTermDetail(){
    if($('.term-detail-item').length === 0){
      show_warning('请生成期限费率配置详情');
      return false;
    }
    var config = get_config();
    var fee_is_ok = true;
    var error_line = [];
    if(config.is_same_interest === '0'){//固定费率
      $.each($('.term_fee'),function(i) {
        console.log($(this).val() );
        if($(this).val() === ''){
          fee_is_ok = false;
          error_line.push(i+1);
        }
      });
      console.log(error_line.toString());
      if(fee_is_ok === false){
        show_warning('第'+error_line.toString()+'行费率错误');
        return false;
      }
    }
    return true;
  }
  
  //提交前验证 生成数据
  $('#fee-config-form').on('beforeSubmit', function (e) {
	return validateTermDetail();
});
  
 
JS;
$this->registerJs($js, \yii\web\View::POS_END);

?>



<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \common\models\Product;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $authConfig string */


?>

<div class="mk-product-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <table class="table table-bordered" id="auth-item-container">
    </table>
    <div class="box-footer">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>

    <script type="text/template" id="auth-item-tpl">
        <thead>
        <tr>
            <th>认证项(<span style="color: red;">红色</span>是未配置的认证项)</th>
            <th>是否需要</th>
            <th>是否是基础认证项</th>
            <th>数据格式</th>
            <th>时效要求</th>
            <th>特殊要求</th>
            <th>排序</th>
        </tr>
        </thead>
        {{#each this }}
        <tr class="auth-item">
            <td style="color:{{ renderIsNew this.have }}">
                {{ this.name }}
                <input type="hidden" name="ProductAuthConfig[{{@index}}][id]" value="{{ this.id }}">
                <input type="hidden" name="ProductAuthConfig[{{@index}}][auth_type]" value="{{ this.auth_type }}">
            </td>
            <td>
                <input type="checkbox" name="ProductAuthConfig[{{@index}}][is_need]" value="1" {{ renderIsNeed this.is_need }} />
            </td>
            <td>
                <select name="ProductAuthConfig[{{@index}}][is_base]">
                    {{ renderIsBase this.auth_type this.is_base }}
                </select>

            </td>
            <td>
                {{ renderDataFormat @index this }}
            </td>
            <td>
                {{ renderTimeLimit @index this.time_limit this.auth_type }}
            </td>
            <td>
                {{ renderIsNeedFaceScore @index this }}
            </td>
            <td>
                <input style="width:50px;" name="ProductAuthConfig[{{@index}}][sort]" type="text" value="{{ this.sort }}">
            </td>
        </tr>
        {{/each}}
    </script>
</div>

<?php
$css = <<<CSS
table tr.is-new{
    border: 1px red solid !important;
}
CSS;
$this->registerCss($css);
$js = <<<JS
Array.prototype.diff = function(a) {
    return this.filter(function(i) {return a.indexOf(i) < 0;});
};

var authConfig = $authConfig;
console.log(authConfig);

var tpl   =  $("#auth-item-tpl").html();
var template = Handlebars.compile(tpl);
//是否需要渲染
Handlebars.registerHelper('renderIsNeed',function(is_need) {
  if(is_need === '0'){
    return '';
  }else{
    return 'checked';
  }
});
//是否是基础认证项 渲染
Handlebars.registerHelper('renderIsBase',function(auth_type, is_base) {
  var r;
    if(auth_type === '1' || auth_type === '2' || auth_type === '3')//身份证 活体 手持都是基础认证项不可以改
    {
        r = '<option value="1" selected>是</option><option value="0" disabled>否</option>';
    }else{
      if(is_base === '1'){
        r = '<option value="1" selected>是</option><option value="0">否</option>';
      } else{
        r=  '<option value="1" >是</option><option value="0" selected>否</option>';
      }
    }
  return new Handlebars.SafeString(r);
});

//数据格式渲染 渲染
Handlebars.registerHelper('renderDataFormat',function(index,line) {
  var r = '<input name="ProductAuthConfig['+index+'][data_format]" type="hidden" value="0" />';
  if(line.auth_type === '2'){//活体认证
    if(line.data_format === '1')//face格式
    {
      r = '<select name="ProductAuthConfig['+index+'][data_format]"><option value="1" selected>face</option></select>';
    }
  }if(line.auth_type === '4'){//运营商认证
    if(line.data_format === '1')//聚信立
    {
      r = '<select name="ProductAuthConfig['+index+'][data_format]"><option value="1" selected>聚信立</option></select>';
    }
  }
  console.log(index,line.auth_type, line.data_format,r);
  return new Handlebars.SafeString(r);
});

//时效渲染 
Handlebars.registerHelper('renderTimeLimit',function(index,time_limit,auth_type) {
  var r = '<input name="ProductAuthConfig['+index+'][time_limit]" type="hidden" value="0" />';
  if(auth_type === '2' || auth_type === '3' || auth_type === '4'){//活体 手持 运营商 时效
    if(time_limit  === '-1'){//时效没有
      r = '<select name="ProductAuthConfig['+index+'][time_limit]">' +
          '<option value="-1" selected>不限</option>' +
          '<option value="0" >每次申请</option>' +
          '<option value="7" >7天</option>' +
           '<option value="30">30天</option>' +
       '</select>';
    }else if(time_limit  === '0'){//时效没有
      r = '<select name="ProductAuthConfig['+index+'][time_limit]">' +
          '<option value="-1" >不限</option>' +
          '<option value="0" selected>每次申请</option>' +
          '<option value="7" >7天</option>' +
           '<option value="30" >30天</option>' +
       '</select>';
    }else if(time_limit  === '7'){//时效没有
      r = '<select name="ProductAuthConfig['+index+'][time_limit]">' +
          '<option value="-1" >不限</option>' +
          '<option value="0" >每次申请</option>' +
          '<option value="7" selected>7天</option>' +
           '<option value="30" >30天</option>' +
       '</select>';
    }else if(time_limit  === '30'){//时效没有
      r = '<select name="ProductAuthConfig['+index+'][time_limit]">' +
          '<option value="-1">不限</option>' +
          '<option value="0" >每次申请</option>' +
          '<option value="7" >7天</option>' +
           '<option value="30" selected>30天</option>' +
       '</select>';
    }
  }
  return new Handlebars.SafeString(r);
});
//是否需要人脸分
Handlebars.registerHelper('renderIsNeedFaceScore',function(index,line) {
  var r = '<input name="ProductAuthConfig['+index+'][need_face_score]" type="hidden" value="0" />';
  if(line.auth_type === '2'){//活体认证
    if(line.need_face_score === '1')//face格式
    {
      r = '<select name="ProductAuthConfig['+index+'][need_face_score]">' +
              '<option value="0" >不需要人脸分</option>' +
              '<option value="1" selected >需要人脸分</option>' +
        '</select>';
    }else {
       r = '<select name="ProductAuthConfig['+index+'][need_face_score]">' +
              '<option value="0" selected>不需要人脸分</option>' +
              '<option value="1" >需要人脸分</option>' +
        '</select>';
    }
  }
  return new Handlebars.SafeString(r);
});
//未设置的配置项
Handlebars.registerHelper('renderIsNew',function(is_have) {
  if(is_have === '0'){
    return 'red';
  }
  return '';
});

function initRender(){
  var lines = authConfig;
  var haveTypes = lines.map(function(item) { //获取已经有的类型
    return item.auth_type;
  })
  for (var i = 0; i<haveTypes.length; i++){//没有的类型加上
    
  }
  var html = template(lines);
  $('#auth-item-container').html(html);
}
initRender();
  
JS;
$this->registerJs($js, \yii\web\View::POS_END);

?>



<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \common\models\Product;
use yii\widgets\Pjax;

/* @var $model common\models\Product */
/* @var $allTerms string */
/* @var $selectedTerms string */
/* @var $allCareerTerms string */
/* @var $selectedCareer string */



?>
<div class="mk-product-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">
        <?= $form->field($model, 'is_career_auto')->label('是否需要职业联动')->radioList(Product::$is_career_auto_set) ?>
    </div>

    <div id="hand-fill-config-container" style="margin-left: 30px">

    </div>
    <div class="box-footer">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <script type="text/template" id="all-hand-fill-config-tpl">
        {{#each this }}
        {{ renderTerm @index this}}
        {{/each}}
    </script>

    <script type="text/template" id="career-hand-fill-config-tpl">
        <div style="border:1px solid royalblue;padding: 5px;">
            {{ renderCareer career }}
        </div>
        {{ renderCareerTabs career }}

        <div class="career-item" style="display: {{renderVisible 1}};" >
            {{#each shangbanzu }}
            {{ renderTerm @index . }}
            {{/each }}
        </div>
        <div class="career-item" style="display: {{renderVisible 2}};">
            {{#each qiyezhu }}
            {{ renderTerm @index . }}
            {{/each }}
        </div>
        <div class="career-item" style="display: {{renderVisible 3}};">
            {{#each getihu }}
            {{ renderTerm @index . }}
            {{/each }}
        </div>
        <div class="career-item" style="display: {{renderVisible 4}};">
            {{#each xuesheng }}
            {{ renderTerm @index . }}
            {{/each }}
        </div>
        <div class="career-item" style="display: {{renderVisible 5}};">
            {{#each ziyouzhiye }}
            {{ renderTerm @index . }}
            {{/each }}
        </div>

    </script>
</div>

<?php
$css = <<<CSS
#hand-fill-config-container .checkbox-options{
    font-size: 12px;
    font-weight: normal;
}
#hand-fill-config-container label{
    margin-left: 4px;
    margin-right: 4px; 
}
#hand-fill-config-container .line-hand-term{
    margin-top: 4px;
}
#hand-fill-config-container  .career{
    display: inline-block;
    width: 200px;
    background-color: #0d6aad;
    text-align: center;
    border: 1px solid paleturquoise;
}
#hand-fill-config-container  .active{
    display: inline-block;
    width: 200px;
    background-color: #00a7d0;
    text-align: center;
    border: 1px solid paleturquoise;
}
#hand-fill-config-container .carrer_tabs{
    height: 40px;
}

CSS;
$this->registerCss($css);
$js = <<<JS
Array.prototype.diff = function(a) {
    return this.filter(function(i) {return a.indexOf(i) < 0;});
};

//所有题目
var allTerms = $allTerms;
//所有选中的题目
var selectedTerms = $selectedTerms;
//如果是职业联动 选中的职业
var selectedCareer = $selectedCareer;
console.log(selectedCareer);
var currentShowCareerTab = '';
//所有职业联动题目
var allCareerTerms = $allCareerTerms;
console.log(allCareerTerms);
var isCareerAuto = {$model->is_career_auto};//是否是职业联动
console.log(allTerms);
var allTpl  =  $("#all-hand-fill-config-tpl").html();
var careerTpl  =  $("#career-hand-fill-config-tpl").html();

var allTemplate = Handlebars.compile(allTpl);
var careerTemplate = Handlebars.compile(careerTpl);

//非职业联动渲染
Handlebars.registerHelper('renderTerm',function(index,line) {
  var r = '';
  switch (line.type) {
    case '1'||4:{//文本
      if(selectedTerms[line.id]){
        r = '<input type="checkbox" checked value="'+ line.id + '" name="ProductHandFillConfig[' +  index + '][id]">' +
        '<label>' + line.term_name + '</label><br/>';
      }else{
        r = '<input type="checkbox" value="'+ line.id + '" name="ProductHandFillConfig[' +  index + '][id]">' +
        '<label>' + line.term_name + '</label><br/>';
      }
      
      break;
    }
    case '2' || '3':{//选择题
        if(selectedTerms[line.id]){
           r = '<input class="single-select" checked type="checkbox" value="'+ line.id + '" name="ProductHandFillConfig[' + index + '][id]">'+ 
           '<label>' + line.term_name + '</label><br/>';
        }else{
            r = '<input class="single-select" type="checkbox" value="'+ line.id + '" name="ProductHandFillConfig[' + index + '][id]">'+ 
           '<label>' + line.term_name + '</label><br/>';
        }
       var o = '';
       // console.log(line.options);
       try {
         var options = JSON.parse(line.options);
       }catch (e) {
         console.log('异常数据:'+line);
       }
       for(var k in options){
         //如果是选中的 选中的选项设置为选中状态
         if(selectedTerms[line.id] && selectedTerms[line.id]['options'] && $.inArray(k,selectedTerms[line.id]['options']) !== false){
            o += '<input name="ProductHandFillConfig['+index+'][options][]" checked type="checkbox" value="'+ k +'" /> <label class="checkbox-options">'+options[k]+'</label>';
         }else{
            o += '<input name="ProductHandFillConfig['+index+'][options][]" type="checkbox" value="'+ k +'" /> <label class="checkbox-options">'+options[k]+'</label>';
         }
       }
       r += o + '<br/>';
       break;
    }
  }
  r = '<div class="line-hand-term">' + r + '</div>'
  return new Handlebars.SafeString(r);
});

//职业联动渲染职业
Handlebars.registerHelper('renderCareer',function(career) {
  var r = '<input class="single-select" checked type="checkbox" disabled  value="'+ career.id + '" name="ProductHandFillConfig[-1][id]">'+ 
           '<label>' + career.term_name + '</label><br/>';
  r += '<input type="hidden"  value="'+ career.id + '" name="ProductHandFillConfig[-1][id]">';
  var options = JSON.parse(career.options);
  var o = '';
  for(var k in options){
     //如果是选中的 选中的选项设置为选中状态
     if($.inArray(k,selectedCareer) !== -1){
        o += '<input name="ProductHandFillConfig[-1][options][]" checked type="checkbox" value="'+ k +'" /> <label class="checkbox-options">'+options[k]+'</label>';
     }else{
        o += '<input name="ProductHandFillConfig[-1][options][]" type="checkbox" value="'+ k +'" /> <label class="checkbox-options">'+options[k]+'</label>';
     }
  }
  
  r = '<div class="career_term">' + r + o +  '</div>';
  return new Handlebars.SafeString(r);
});


//渲染可见tab
Handlebars.registerHelper('renderVisible',function(type) {
  //如果是选中的
  if(currentShowCareerTab !== ''){
    if(type === currentShowCareerTab){
        return 'block';
    }else{
        return 'none';
    }
  }
  //默认第一个显示
  if(type+'' === selectedCareer[0]){
    return 'block';
  }else{
    return 'none';
  }
});

//渲染tabs
Handlebars.registerHelper('renderCareerTabs',function(career) {
  var options = JSON.parse(career.options);
  var tabsStr = '';
  for(var k in options){
      if($.inArray(k+'',selectedCareer) !== -1){ //如果是需要联动的职业类型
        console.log(currentShowCareerTab,k);
        if(currentShowCareerTab === ''){//如果当前没有选中 默认第一个高亮
          if(k+'' === selectedCareer[0]){
            tabsStr += '<span class="active" data-type="'+k+'">'+options[k]+'</span>';
          }else{
            tabsStr += '<span class="career" data-type="'+k+'">'+options[k]+'</span>';
          }
        }else{
          if( currentShowCareerTab+'' === k){//如果是选中的 渲染active
           tabsStr += '<span class="active" data-type="'+k+'">'+options[k]+'</span>';
          }else{
           tabsStr += '<span class="career" data-type="'+k+'">'+options[k]+'</span>';
          }
        }
      }
  }
  tabsStr = '<div class="career_tabs">' + tabsStr + '</div>';
  return new Handlebars.SafeString(tabsStr);
});




//渲染无职业联动
function renderAll(){
  var html = allTemplate(allTerms);
  $('#hand-fill-config-container').html(html);
}
//渲染职业联动
function renderCareer(){
  var html = careerTemplate(allCareerTerms);
  $('#hand-fill-config-container').html(html);
}
function initRender(){
  var v = $('#product-is_career_auto input[name=Product\\\\[is_career_auto\\\\]]:checked').val();
  console.log(v);
  if(v === '0'){
    renderAll();
  }else{
    renderCareer();
  }
}

initRender();
 
$('#product-is_career_auto input[name=Product\\\\[is_career_auto\\\\]]').click(function() {
  if($(this).val() === '1'){
        renderCareer();
  }else{
        renderAll();
  }
});

//职业联动时 点击任何职业选项 重新渲染
$('#hand-fill-config-container').on('click','.career_term input[type=checkbox]',function() {
  var sel_career = [];
  $.each($('.career_term input[name=ProductHandFillConfig\\\\[-1\\\\]\\\\[options\\\\]\\\\[\\\\]]:checked'),function() {
    //修改全局选中职业变量 渲染
    sel_career.push($(this).val());
  });
  selectedCareer = sel_career;
  renderCareer();
});
//点击切换tabs
$('#hand-fill-config-container').on('click','.career',function() {
  currentShowCareerTab = $(this).data('type');
  renderCareer();
});

JS;
$this->registerJs($js, \yii\web\View::POS_END);

?>



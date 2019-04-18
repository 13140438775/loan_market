
//上传图片
$("#product-logo_url").on("change", function (e) {
  var file = $(this)[0].files[0];
  if(file){
    var upload = new Upload(file,'#product-logo_url','.pre_logo_url','.field-product-logo_url input[type=hidden]');
    upload.doUpload();
  }
});

//息费收取方式联动
$('input[name=Product\\[interest_pay_type\\]]').change(function(){
  if($(this).val() === '5'){
    $(".field-product-interest_pay_type_desc").show();
    $('#api-product-form').yiiActiveForm('add', {
      id: 'product-interest_pay_type_desc',
      name: 'Product[interest_pay_type_desc]',
      container: '.field-product-interest_pay_type_desc',
      input: '#product-interest_pay_type_desc',
      error: '.help-block',
      validate:  function (attribute, value, messages, deferred, $form) {
        yii.validation.required(value, messages, {message: "xxxxxxxx"});
      }
    });
  }else{
    $(".field-product-interest_pay_type_desc").hide();
    $('#api-product-form').yiiActiveForm('remove', 'product-interest_pay_type_desc');
  }
});






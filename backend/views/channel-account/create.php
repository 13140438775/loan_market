<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ChannelAccount */

$this->title = '添加账户';
$this->params['breadcrumbs'][] = ['label' => '渠道账户列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .add_channel{
        display: inline-block;
        margin-bottom: 0;
        font-weight: normal;
        white-space: nowrap;
        vertical-align: middle;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        cursor: pointer;
        background-image: none;
        border: 1px solid transparent;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        border-radius: 4px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
</style>
<div class="channel-account-create">

    <div class="common-form-container">
        <form class="common-form" action="<?= \yii\helpers\Url::toRoute(['channel-account/channel-account-create']) ?>" method="post" enctype="multipart/form-data">
            <input name="_csrf-backend" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">

            <div class="form-inline form-group-sm row top5" style="padding-left: 38px;">
                <span class="text-right"><label class="control-label form-inline  initialism " for="channels-channel_name">用户名* &nbsp;</label></span>
                <input type="text" class="form-control" name="username" placeholder="用户名" aria-label="username" aria-describedby="basic-addon1" value="">
            </div>

            <div class="form-inline form-group-sm row top5" style="padding-left: 56px;">
                <span class="text-right"><label class="control-label form-inline  initialism " for="channels-channel_name">密码 &nbsp;</label></span>
                <input type="password" class="form-control" name="password" placeholder="密码" aria-label="username" aria-describedby="basic-addon1" value="">
            </div>

            <div class="form-inline form-group-sm row top5" style="padding-left: 38px;">
                <span class="text-right"><label class="control-label form-inline  initialism " for="channels-channel_name">手机号* &nbsp;</label></span>
                <input type="text" class="form-control" name="mobile" placeholder="手机号" aria-label="username" aria-describedby="basic-addon1" value="">
            </div>

            <div style="margin-top: 6px">
                <div class="create-text-field-container field-htmlmanage-name required">
                    <label class="field-label" for="htmlmanage-name">渠道ID*</label>
                    <input type="text" class="form-control" name="channel_id[]" placeholder="每个渠道ID之间以';'英文分号隔开">
                    <button type="button" class="btn" style="background-color: darkturquoise; color: white" onclick="channel_choose(this)">查询关联渠道商</button>
                    <a class="add_channel"><span class="glyphicon glyphicon-minus" onclick="channel_remove(this)"></span></a>
                </div>
                <div class="channel_name">

                </div>
            </div>

            <table class="table" style="margin-top: 10px">
                <tbody id="clone_ele">
                <tr>
                    <th scope="row">权限(倍率系数)</th>
                    <td>
                        <input type="checkbox" name="uv_show[]" value="1">UV
                        <input type="text" class="form-control" name="uv_coefficient[]" placeholder="">
                    </td>
                    <td>
                        <input type="checkbox" name="register_show[]" value="1">注册数
                        <input type="text" class="form-control" name="register_coefficient[]" placeholder="">
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <input type="checkbox" name="login_show[]" value="1">登录数
                        <input type="text" class="form-control" name="login_coefficient[]">
                    </td>
                </tr>
                </tbody>

            </table>

            <div id="position"></div>

            <a class="add_channel" id="add_channel"><span class="glyphicon glyphicon-plus">添加渠道</span></a>


            <div class="form-group">
                <button type="submit" class="btn btn-success">保存</button>
            </div>
        </form>
    </div>

</div>
<?php
    $ajax_api = \yii\helpers\Url::toRoute(['channel-account/channel-name']);
?>

<?php $hljs = <<<JS
jQuery(document).ready(function() {
    highlight_subnav('channel-account/index'); //子导航高亮
});

var csrfToken = $('meta[name="csrf-token"]').attr("content");
var add_channel = $('#add_channel');
var position = $('#position');
var clone_ele = $('#clone_ele');
add_channel.click(function(){
    position.append(
        '<div><hr></div><div><div class="create-text-field-container field-htmlmanage-name required"><label class="field-label" for="htmlmanage-name">渠道ID*</label><input type="text" class="form-control" name="channel_id[]" placeholder="每个渠道ID之间以\';\'英文分号隔开"><button type="button" class="btn" style="background-color: darkturquoise; color: white" onclick="channel_choose(this)">查询关联渠道商</button><a class="add_channel"><span class="glyphicon glyphicon-minus" onclick="channel_remove(this)"></span></a></div><div class="channel_name"></div></div><table class="table" style="margin-top: 10px"><tbody id="clone_ele"><tr><th scope="row">权限(倍率系数)</th><td><input type="checkbox" name="uv_show[]" value="1">UV<input type="text" class="form-control" name="uv_coefficient[]" placeholder=""></td><td><input type="checkbox" name="register_show[]" value="1">注册数<input type="text" class="form-control" name="register_coefficient[]" placeholder=""></td></tr><tr><td></td><td><input type="checkbox" name="login_show[]" value="1">登录数<input type="text" class="form-control" name="login_coefficient[]"></td></tr></tbody></table>'
    );
});

function channel_remove(obj){
    if(!confirm('确认取消关联吗?')){
        return false;
    }
    $(obj).parent().parent().parent().prev().remove();
    $(obj).parent().parent().parent().next().empty();
    $(obj).parent().parent().parent().empty();
}

function channel_choose(obj){
    //获取渠道ID的值
    var channel_id = $(obj).prev().val();
    var checkNum = /^[0-9;]+$/;
    if(!checkNum.test(channel_id)){
        alert('渠道id输入不合法');
        return false;
    }
    
    $.ajax({
      type: 'POST',
      url: '{$ajax_api}',
      data: {
          _csrf : csrfToken,
          'channel_id' : channel_id,
          },
      success: function(result) {
          if(result){
            $(obj).parent().next().empty();
            $(obj).parent().next().append('<label class="field-label" style="padding: 10px">渠道名称:</label><div class="">'+result+'</div>');
          }
        },
    });
}
        

$('form').submit(function(){
    var username = $('input[name="username"]').val($('input[name="username"]').val().replace(/(^\s+)|(\s+$)/g,""));
    var mobile = $('input[name="mobile"]').val();
    var channel_id = $('input[name="channel_id[]"]');
    if(!username.val()){
        alert('用户名不能为空');
        return false;
    }
    
    if(!mobile){
        alert('手机号不能为空');
        return false;
    }
    
    var pattern = /^1[34578]\d{9}$/; 
    if(!pattern.test(mobile)){
        alert('手机号不合法');
        return false;
    }
    
    var is_break = true;
    channel_id.each(function(){
        if(!$(this).val()){
            alert('渠道ID不能为空');
            is_break = false;
        }
    });
    
    if(!is_break){
        return false;        
    }
});
JS;
?>


<?php $this->registerJs($hljs, \yii\web\View::POS_END); ?>

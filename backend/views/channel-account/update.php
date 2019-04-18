<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ChannelAccount */

$this->title = '编辑 ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => '渠道账户列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '编辑';
?>
<style>
    .td_center{
        text-align:center
    }
</style>
<div class="channel-account-index">

    <h1>编辑 <?= $model->username?></h1>


    <form action="/channel-account/update?id=<?= Yii::$app->request->get()['id'] ?>" method="post">
        <input name="_csrf-backend" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">

        <div class="form-inline form-group-sm row top5 field-channels-channel_name required">
            <span class="col-sm-1 text-right"><label class="control-label form-inline  initialism " for="channels-channel_name">用户名</label></span>
            <input type="text" class="form-control" name="username" placeholder="用户名" aria-label="username" aria-describedby="basic-addon1" value="<?= $model->username ?>">
        </div>

        <div class="form-inline form-group-sm row top5 field-channels-channel_name">
            <span class="col-sm-1 text-right"><label class="control-label form-inline  initialism " for="channels-channel_name">密码</label></span>
            <input type="password" class="form-control" name="password" placeholder="请输入新密码" aria-label="password" aria-describedby="basic-addon1" >
        </div>

        <div class="form-inline form-group-sm row top5 field-channels-channel_name required">
            <span class="col-sm-1 text-right"><label class="control-label form-inline  initialism " for="channels-channel_name">手机号</label></span>
            <input type="text" class="form-control" name="mobile" placeholder="手机号" aria-label="mobile" aria-describedby="basic-addon1" value="<?= $model->mobile ?>">
        </div>

        <table class="table table-striped table-bordered"><thead>
            <tr>
                <th>#</th>
                <th><a href="">ID</a></th>
                <th><a href="">关联渠道名称</a></th>
                <th><a href="">关联渠道ID</a></th>
                <th><a href="">UV显示</a></th>
                <th><a href="">UV系数</a></th>
                <th><a href="">注册显示</a></th>
                <th><a href="">注册系数</a></th>
                <th><a href="">登陆显示</a></th>
                <th><a href="">登录系数</a></th>
                <th><a href="">操作</a></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $num = 1;
            foreach($model->getInfo() as $item){?>
            <tr>
                <td><?= $num?></td>
                <td><?= $item['channel_assoc_account_id'] ?? ''?></td>
                <td><?= $item['channel_name'] ?? ''?></td>
                <td><?= $item['channels_id'] ?? ''?></td>
                <td class="td_center">
                    <input type="hidden" name="uv_show[<?= $item['channel_assoc_account_id'] ?>]" value="0">
                    <input type="checkbox" aria-label="Checkbox for following text input" name="uv_show[<?= $item['channel_assoc_account_id'] ?>]" <?php if($item['uv_show'] == 1){ ?> checked <?php } ?> value="1">
                </td>

                <td><input type="text" class="form-control" name="uv_coefficient[<?= $item['channel_assoc_account_id'] ?>]" placeholder="UV系数" aria-label="UV系数" aria-describedby="basic-addon1" value="<?= $item['uv_coefficient'] ?>"></td>

                <td class="td_center">
                    <input type="hidden" name="register_show[<?= $item['channel_assoc_account_id'] ?>]" value="0">
                    <input type="checkbox" aria-label="Checkbox for following text input" name="register_show[<?= $item['channel_assoc_account_id'] ?>]" <?php if($item['register_show'] == 1){ ?> checked <?php } ?> value="1">
                </td>

                <td><input type="text" class="form-control" name="register_coefficient[<?= $item['channel_assoc_account_id'] ?>]" placeholder="注册系数" aria-label="注册系数" aria-describedby="basic-addon1" value="<?= $item['register_coefficient'] ?>"></td>

                <td class="td_center">
                    <input type="hidden" name="login_show[<?= $item['channel_assoc_account_id'] ?>]" value="0">
                    <input type="checkbox" aria-label="Checkbox for following text input" name="login_show[<?= $item['channel_assoc_account_id'] ?>]" <?php if($item['login_show'] == 1){ ?> checked <?php } ?> value="1">
                </td>

                <td><input type="text" class="form-control" name="login_coefficient[<?= $item['channel_assoc_account_id'] ?>]" placeholder="登录系数" aria-label="登录系数" aria-describedby="basic-addon1" value="<?= $item['login_coefficient'] ?>"></td>
                <td><a class="add_channel"><span class="glyphicon glyphicon-minus" onclick="channel_remove(this, <?= $item['channel_assoc_account_id'] ?>)"></span></a></td>
            </tr>
            <?php
            $num++;
            }?>
            </tbody>
        </table>

        <input type="submit" type="button" class="btn btn-success" value="保存"></input>
        <a href="<?= \yii\helpers\Url::toRoute(['channel-account/add-channel-assoc-account', 'id' => Yii::$app->request->get()['id']]); ?>" type="submit" class="btn btn-success">新增渠道</a>
    </form>
</div>

<?php
$ajax_api = \yii\helpers\Url::toRoute(['channel-account/change-assoc-status']);
?>

<?php $hljs = <<<JS
jQuery(document).ready(function() {
    highlight_subnav('channel-account/index'); //子导航高亮
});

var csrfToken = $('meta[name="csrf-token"]').attr("content");
function channel_remove(obj, id){
    if(!confirm('确认取消关联吗?')){
        return false;
    }
    $.ajax({
      type: 'POST',
      url: '{$ajax_api}',
      data: {
          _csrf : csrfToken,
          'assoc_id' : id,
          },
      success: function(result) {
          if(result){
            location.reload()
          }
        },
    });
}
JS;
?>

<?php $this->registerJs($hljs, \yii\web\View::POS_END); ?>
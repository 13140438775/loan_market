<?php

use yii\helpers\Html;
use common\models\ChannelAccount;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ChannelsManageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$model = new ChannelAccount();

$this->title = '渠道管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="channels-index">

    <h1><?= Html::encode($this->title) ?></h1>
<!--    --><?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th><a href="">用户名</a></th>
            <th><a href="">手机号</a></th>
            <th><a href="">关联渠道名称</a></th>
            <th><a href="">关联渠道ID</a></th>
            <th><a href="">创建人</a></th>
            <th><a href="">创建时间</a></th>
            <th><a href="">上次修改人</a></th>
            <th><a href="">上次修改时间</a></th>
            <th><a href="">状态</a></th>
        </tr>
        </thead>

        <tbody>
        <?php
        $num = 1;
        foreach($model->getIndexInfo() as $item){?>
            <tr>
                <td><?= $num ?></td>
                <td><?= isset($item['username']) ? $item['username'] : '' ?></td>
                <td><?= isset($item['mobile']) ? $item['mobile'] : '' ?></td>
                <td><?= isset($item['channel_name']) ? $item['channel_name'] : '' ?></td>
                <td><?= isset($item['channel_id']) ? $item['channel_id'] : '' ?></td>
                <td><?= isset($item['created_name']) ? $item['created_name'] : '' ?></td>
                <td><?= isset($item['created_at']) ? date('Y年/m月/d日 H:i', $item['created_at']) : '' ?></td>
                <td><?= isset($item['updated_name']) ? $item['updated_name'] : '' ?></td>
                <td><?= isset($item['updated_at']) ? date('Y年/m月/d日 H:i', $item['updated_at']) : '' ?></td>
                <td><?php
                    if($item['status']){
                        echo '<button type="button" class="btn btn-success" onclick="is_enable(this, '.$item['account_id'].')" value="1">启用</button>';
                    } else {
                        echo '<button type="button" class="btn btn-secondary" onclick="is_enable(this, '.$item['account_id'].')" value="0">禁用</button>';
                    }?>
                </td>
                <td><a href="<?= \yii\helpers\Url::toRoute(['channel-account/update', 'id' => $item['account_id']]); ?>">编辑</a></td>
            </tr>
        <?php
        $num++;
        }?>
        </tbody>
    </table>
</div>

<?php
    $ajax_api = \yii\helpers\Url::toRoute(['channel-account/change-status']);
?>

<?php $hljs = <<<JS
jQuery(document).ready(function() {
    highlight_subnav('channel-account/index'); //子导航高亮
});

    function is_enable(obj, account_id) {
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
            if(!confirm('确认修改状态吗?')){
                return false;
            }
            
            var status = $(obj).val();
            
            $.ajax({
          type: 'POST',
          url: '{$ajax_api}',
          data: {
              _csrf : csrfToken,
              'status' : status,
              'account_id' : account_id,
              },
          success: function(result) {
                    location.reload();
              }
        });
    }
JS;
?>


<?php $this->registerJs($hljs, \yii\web\View::POS_END); ?>
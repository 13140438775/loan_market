<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Channels */

$this->title = '编辑 ' . $model->channel_name;
$this->params['breadcrumbs'][] = ['label' => '渠道管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->channel_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="channels-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php $hljs = <<<JS
jQuery(document).ready(function() {
    highlight_subnav('channels-manage/index'); //子导航高亮
});
JS;
?>

<?php $this->registerJs($hljs, \yii\web\View::POS_END); ?>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\HtmlManage */

$this->title = '编辑模板 ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'H5模板管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '编辑模板';
?>
<div class="html-manage-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php $hljs = <<<JS
jQuery(document).ready(function() {
    highlight_subnav('html-manage/index'); //子导航高亮
});
JS;
?>

<?php $this->registerJs($hljs, \yii\web\View::POS_END); ?>
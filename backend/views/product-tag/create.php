<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ProductTag */

$this->title = '新建标签';
$this->params['breadcrumbs'][] = ['label' => '标签列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="common-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php $hljs = <<<JS
jQuery(document).ready(function() {
    highlight_subnav('product-tag/index'); //子导航高亮
});
JS;
?>

<?php $this->registerJs($hljs, \yii\web\View::POS_END); ?>
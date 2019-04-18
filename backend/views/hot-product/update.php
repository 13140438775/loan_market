<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\HotProduct */

$this->title = '编辑热门贷款: ' . $model->product->product_name;
$this->params['breadcrumbs'][] = ['label' => '热门贷款', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="hot-product-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php $hljs = <<<JS
jQuery(document).ready(function() {
    highlight_subnav('hot-product/index'); //子导航高亮
});
JS;
?>

<?php $this->registerJs($hljs, \yii\web\View::POS_END); ?>
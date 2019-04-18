<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\HotProduct */

$this->title = '添加热门贷款';
$this->params['breadcrumbs'][] = ['label' => '热门贷款', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hot-product-create">

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
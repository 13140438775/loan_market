<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CreditProduct */

$this->title = '编辑产品: ' . $model->product_name;
$this->params['breadcrumbs'][] = ['label' => '产品列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->product_name, 'url' => ['index']];
$this->params['breadcrumbs'][] = '编辑';
$model->apply_materia = json_decode($model->apply_materia, true);
?>
<div class="credit-product-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php $hljs = <<<JS
jQuery(document).ready(function() {
    highlight_subnav('credit-product/index'); //子导航高亮
});
JS;
?>

<?php $this->registerJs($hljs, \yii\web\View::POS_END); ?>

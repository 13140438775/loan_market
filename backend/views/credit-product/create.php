<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CreditProduct */

$this->title = '创建渠道';
$this->params['breadcrumbs'][] = ['label' => '创建渠道', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credit-product-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
    <!-- 定义数据块 -->
<?php $hljs = <<<JS
jQuery(document).ready(function() {
    highlight_subnav('credit-product/index'); //子导航高亮
});
JS;
?>

<?php $this->registerJs($hljs, \yii\web\View::POS_END); ?>










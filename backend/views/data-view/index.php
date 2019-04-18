<?php

use \kartik\date\DatePicker;
use \yii\widgets\ActiveForm;
use \yii\bootstrap\Html;
use \backend\models\DataStatsSearch;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductDailyDataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '渠道数据统计';
$this->params['breadcrumbs'][] = $this->title;
$model = new DataStatsSearch();
?>
<div class="product-daily-data-search common-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'class' => 'common-search-form'
        ]
    ]); ?>

    <?php echo $form->field($model,'date_begin')->widget(DatePicker::class, [
        'type' => DatePicker::TYPE_INPUT,
        'language' => 'zh-CN',
        'options' => [
            'value' => '',
            'autocomplete' => 'off'
        ],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ])->textInput(['class'=>'text-ipt'])->label('',['class' => 'field-label']);
    ?>

    <?php echo $form->field($model,'date_end')->widget(DatePicker::class, [
        'type' => DatePicker::TYPE_INPUT,
        'language' => 'zh-CN',
        'options' => [
            'value' => '',
            'autocomplete' => 'off'
        ],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ])->textInput(['class'=>'text-ipt'])->label(' -- ',['class' => 'field-label']);
    ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>日期</th>
            <th>用户申请数</th>
            <th>UV</th>
            <th>PV</th>
            <th>在线产品数</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data_view AS $item): ?>
            <tr>
                <td><?php echo date("Y/m/d", strtotime($item['date'])); ?></td>
                <td><?php echo $item['app_user']; ?></td>
                <td><?php echo $item['uv_num']; ?></td>
                <td><?php echo $item['pv_num']; ?></td>
                <td><?php echo $item['product_num']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

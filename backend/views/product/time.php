<?php
use yii\widgets\Pjax;
use yii\helpers\Html;
?>

<?php Pjax::begin()?>
<?= Html::a('time',['product/time'],['id' => 'refreshButton','class'=>'btn btn-lg btn-primary'])?>
    <h3>Current Time:<?=$time?></h3>
<?php Pjax::end()?>

<?php
$script = <<< JS
// $(document).ready(function() {
//   $("#refreshButton").click();
//       setInterval(function(){ setClick() }, 1000);
//       function setClick(){
//         console.log(new Date().getTime())
//         $("#refreshButton").click();
//       }
// });

JS;
$this->registerJs($script,\yii\web\View::POS_END);
?>
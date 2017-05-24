<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FaceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dwemoticon-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <?= $form->field($model, 'emoticon_cate_id')->dropDownList($cate,['prompt'=>'选择你要查询的分类']); ?>
    <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>

    <?php ActiveForm::end(); ?>

</div>

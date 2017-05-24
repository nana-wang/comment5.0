<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
	<div class="col-sm-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<span class="label label-primary pull-right"></span>
				<ul class="nav nav-pills">
					<li><a href="index.php?r=user%2Findex"> <i
							class="glyphicon glyphicon-chevron-left font-12"></i> 用户列表
					</a></li>
					<li class="active"><?= Html::a('创建新用户', ['create'], ['class' => '']) ?></li>
				</ul>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'username')->textInput()?>
        <?= $form->field($model, 'email')->textInput()?>
         <?= $form->field($model, 'password')->passwordInput($model->isNewRecord ? [] : ['placeholder'=>'meibian'])?>
        <?= $form->field($model, 'status')->dropDownList([10=>'正常',0=>'禁用'])?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat'])?>
        </div>
        <?php ActiveForm::end(); ?>
        <!-- /.box-body -->
			</div>
			<!-- /.box -->
		</div>
		<!-- /.col -->
	</div>
</div>

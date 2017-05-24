<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mdm\admin\AutocompleteAsset;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
	<div class="col-sm-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<i class="fa fa-users"></i>
				<h5 class="box-title"></h5>
				<h3 class="box-title">管理账户</h3>
				<span class="label label-primary pull-right"></span>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<div class="container-fluid">
					<ul class="nav nav-pills">
						<li><a href="index.php?r=rbac%2Faccount%2Findex"> <i
								class="glyphicon glyphicon-chevron-left font-12"></i> 账户列表
						</a></li>
						<li class="active"><a href="index.php?r=rbac%2Faccount%2Fcreate">新建账户</a></li>
					</ul>
					    <?php
        $array = array(
                'prompt' => '--请选择--',
				'disabled'=>Yii::$app->controller->action->id=='update'?true:false,
                'encode' => false,
                'onchange' => "javascript:test();",
                'id' => 'tipodecliente_lst',
                'options' => [
                        @$optionid => [
                                'Selected' => true
                        ]
                ]
        );
        echo $form->field($model, 'pid')->dropDownList(
                \mdm\admin\models\Category::getDropDownlist(), $array);
        ?>
                <?= $form->field($model, 'name')->textInput(['maxlength' => 200])?>
                <?= $form->field($model, 'description')->textarea(['rows' => 2])?>
        <div class="form-group">
            <?php
            echo Html::submitButton(
                    $model->isNewRecord ? Yii::t('rbac-admin', 'Create') : Yii::t(
                            'rbac-admin', 'Update'), 
                    [
                            'class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat'
                    ])?>
        </div>
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->
		</div>
		<!-- /.col -->
	</div>
    <?php ActiveForm::end(); ?>
<?php

    AutocompleteAsset::register($this);


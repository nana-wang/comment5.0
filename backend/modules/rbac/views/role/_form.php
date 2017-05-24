<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Json;
use mdm\admin\AutocompleteAsset;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
	<div class="col-sm-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<span class="label label-primary pull-right"></span>
				<ul class="nav nav-pills">
					<li><a href="index.php?r=rbac%2Frole%2Findex"> <i
							class="glyphicon glyphicon-chevron-left font-12"></i> 角色列表
					</a></li>
					<li class="active"><?= Html::a(Yii::t('rbac-admin', 'Create Role'), ['create'], ['class' => '']) ?></li>
				</ul>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'name')->textInput(['maxlength' => 64])?>
                <?php
                $array = array(
                        'prompt' => '--请选择--',
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

    <?php ActiveForm::end(); ?>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box -->
	</div>
	<!-- /.col -->
</div>

<?php
AutocompleteAsset::register($this);

$options = Json::htmlEncode(
        [
                'source' => array_keys(Yii::$app->authManager->getRules())
        ]);
$this->registerJs("$('#rule-name').autocomplete($options);");

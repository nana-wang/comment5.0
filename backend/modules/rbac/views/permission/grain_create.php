<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Json;
use mdm\admin\AutocompleteAsset;
/* @var $this yii\web\View */
/* @var $model mdm\admin\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
$this->title = "权限颗粒管理";
$this->params['breadcrumbs'][] = [
        'label' => '创建颗粒',
        'url' => [
                'index'
        ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-sm-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<span class="label label-primary pull-right"></span>
				<ul class="nav nav-pills">
					<li><a href="index.php?r=rbac%2Fpermission%2Fgrain"> <i
							class="glyphicon glyphicon-chevron-left font-12"></i> 颗粒关系列表
					</a></li>
					<li class="active"><?= Html::a(Yii::t('rbac-admin', '创建颗粒'), ['grain'], ['class' => ''])?></li>
				</ul>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
			
				<?php $form = ActiveForm::begin(); ?>
				<?= $form->field($model, 'name')->textInput(['maxlength' => 5])?>
				<?php
    $array = array(
            'prompt' => '--请选择--',
            'encode' => false,
            'onchange' => "javascript:test();",
            'id' => 'tipodecliente_lst',
            'options' => [
                    $optionid => [
                            'Selected' => true
                    ]
            ]
    );
    
    echo $form->field($model, 'id')->dropDownList($options_data, $array);
    // echo $form->field($model, 'menu')->dropDownList($menu_data, $array);
    ?>
    <div class="row">
    						<?php
        echo $menu_data;
        ?>
					<div class="col-md-12">
						<div class="box box-solid">
							<div class="box-header with-border">
								<i class="fa fa-text-width"></i>
								<h6 class="box-title lead">模块</h6>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<div class="checkbox_2"></div>
							</div>
							<!-- /.box-body -->
						</div>
						<!-- /.box -->
					</div>
				</div>

                <?= $form->field($model, 'description')->textarea(['rows' => 2])?>
                    <div class="form-group">
                        <?php
                        echo Html::submitButton(
                                $model->isNewRecord ? Yii::t('rbac-admin', 
                                        'Create') : Yii::t('rbac-admin', 
                                        'Update'), 
                                [
                                        'class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat'
                                ])?>
                    </div>
                    
                <?php ActiveForm::end(); ?>
				<!-- /.box-body -->

			</div>
			<!-- /.box -->
			<!-- Loading (remove the following to stop the loading)-->
			<div class="overlay" style="display: none;" id="show_lodiing">
				<i class="fa fa-refresh fa-spin"></i>
			</div>
			<!-- end loading -->
		</div>
		<!-- /.col -->
	</div>
</div>
<!-- Modal -->
<div class="modal fade sub_permission" id="myModal" tabindex="-1"
	role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title">Default Modal</h4>
			</div>
			<div class="modal-body">
				<p>One fine body…</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left"
					data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.$('#myModal').modal('show');l -->
<?php

AutocompleteAsset::register($this);

$options = Json::htmlEncode(
        [
                'source' => array_keys(Yii::$app->authManager->getRules())
        ]);

$js = <<<JS
 function  get_sub(){
    alert(111);
$('#myModal').modal('show');
}
JS;
$this->registerJs($js);
?>

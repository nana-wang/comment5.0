<?php
use common\helpers\Html;
use yii\grid\GridView;
use common\components\JsLayouts;
use yii\widgets\ActiveForm;
use Distill\Format\Simple\Img;
use yii\base\Widget;
use backend\models\DwCommentQuery;
use backend\models\search\DwCommentSearch;
use mdm\admin\AdminAsset;
//use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $model common\models\Article */
/* @var $module string */

$this->title = Yii::t('backend','Proper Master');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('backend','Proper Master'),
    'url' => [
        'propindex'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header')?>
<?php $proadd = Yii::t('backend','Proadd');?>
<?php $completed = Yii::t('backend','Completed');?>
<?php $save = Yii::t('backend','Save');?>
<?php $accountnoempty = Yii::t('backend','Account No Empty');?>
<?php $propclassnoempty = Yii::t('backend','Prop Class No Empty');?>
<?php $editdo = Yii::t('backend','Editdo');?>
<h1><?= $this->title;?>
</h1>
<?php $this->endBlock()?>
<script>
var emoticon_add_name = '<?php echo $proadd;?>';
var emoticon_edit_name = '<?php echo $completed;?>';
var category_emoticon_add_name = '<?php echo $proadd;?>';
var category_emoticon_edit_name = '<?php echo $save;?>';
var accountnoempty = '<?php echo $accountnoempty;?>';
var propclassnoempty = '<?php echo $propclassnoempty;?>';
var editdo = '<?php echo $editdo;?>';
var sure_del = '<?php echo Yii::t('backend','Sure Delete');?>';
</script>
<div class="comment-index">
	<div class="box box-primary">
		<div class="box-body">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-default collapsed-box">
						<div class="box-header with-border">
							<h3 class="box-title"><?=Yii::t('backend','Proadd');?></h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool"
									data-widget="collapse">
									<i class="fa fa-plus"></i>
								</button>
							</div>
							<!-- /.box-tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body">
								<?php $form = ActiveForm::begin([
										'action' => ['add'],
							    		'id' => "upload-form",
							            'enableAjaxValidation' => false,
							            'options' => ['enctype' => 'multipart/form-data'],]); 
							    ?>
							<div class="form-group col-md-3">
								<?= $form->field($model, 'props_available') ->dropDownList([0=>Yii::t('backend','Available'),1=>Yii::t('backend','Onavailable')])->label(Yii::t('backend','Proper State')) ; ?> 								
							</div>

							
							<div class="form-group col-md-3">
								<div class="form-group  field-dwprops-props_account_id required">
									<label class="control-label" for="dwprops-account_id"><?=Yii::t('backend','The Account');?></label>            
									<select class="form-control" onChange="change_add('props_category_op','')" id='dwprops-props_account_id'  name="DwProps[props_account_id]" >
										<option value="">--<?=Yii::t('backend','The Account');?>--</option>
										<?= backend\widgets\MenuLeft::widget([],3);?>
									</select>
									<div class="help-block"></div>
								</div>
							</div>
							<div class="form-group col-md-3">
								<div class="form-group field-dwprops-props_category_id required">
									<label class="control-label" for="dwprops-category_id"><?=Yii::t('backend','Props Class');?></label> 
									<select name="DwProps[props_category_id]" class="form-control props_category_op" id='dwprops-props_category_id'>
									<option value="" class=''><?=Yii::t('backend','Nodates');?></option>
									</select>
									<div class="help-block"></div>
								
								</div>
							</div>
							<div class="form-group col-md-3">
<?= $form->field($model, 'props_name') ->label(Yii::t('backend','Item Name')) ; ?> 
								
							</div>
							
							<div class="form-group col-md-3">
<?= $form->field($model, 'props_credit') ->label(Yii::t('backend','Proper Spoints')) ; ?> 
								
							</div>
							<div class="form-group col-md-3">
<?= $form->field($model, 'props_description') ->label(Yii::t('backend','Item Description')) ; ?> 
							</div>
							<div class="form-group col-md-3">
				<?= $form->field($model, 'file')->fileInput()->label(Yii::t('backend','Props Icon'))  ?>				
							</div>
							    <div class="form-group">
							        <?= Html::Button(Yii::t('backend','Proadd'), ['class' => 'btn btn-block btn-info','id'=>'addbutton']) ?>
							    </div>
							
							    <?php ActiveForm::end(); ?>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="box box-default collapsed-box box-default-edit">
						<div class="overlay" style="display: none;" id="edit_loading">
							<i class="fa fa-refresh fa-spin"></i>
						</div>
						<div class="box-header with-border">
							<h3 class="box-title"><?=Yii::t('backend','Edit Edit');?></h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool"
									data-widget="collapse">
									<i class="fa fa-plus"></i>
								</button>
							</div>
							<!-- /.box-tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body box-body-edit">
							<?php $form = ActiveForm::begin([
										'action' => ['edit'],
							    		'id' => "upload-form3",
							            'enableAjaxValidation' => false,
							            'options' => ['enctype' => 'multipart/form-data'],]); 
							    ?>
						<div class="form-group col-md-3">
				<?= $form->field($model, 'props_available') ->dropDownList([0=>Yii::t('backend','Available'),1=>Yii::t('backend','Onavailable')])->label(Yii::t('backend','Proper State')) ; ?> 								
							</div>
							<div class="form-group col-md-3">
<?= $form->field($model, 'props_name') ->label(Yii::t('backend','Item Name')) ; ?> 
								
							</div>
							
							<div class="form-group col-md-3">
<?= $form->field($model, 'props_credit') ->label(Yii::t('backend','Proper Spoints')) ; ?> 
								
							</div>
							<div class="form-group col-md-6">
<?= $form->field($model, 'props_description') ->label(Yii::t('backend','Item Description')) ; ?> 
								
							</div>
							<div class="form-group col-md-3">
				<?php  //$form->field($model, 'file')->fileInput()->label('道具图标')  ?>				
							</div>
							    <div class="form-group">
							    <input type="hidden" id="dwpropscategory-id" class="form-control" name="DwProps[id]">
							        <?= Html::submitButton(Yii::t('backend','Editdo'), ['class' => 'btn btn-block btn-info']) ?>
							    </div>
							
							    <?php ActiveForm::end(); ?>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</div>
			</div>
		</div>
		<div class="box box-default">
			<div class="box-body">
				<?= GridView::widget([
			        'dataProvider' => $dataProvider,
			        'columns' => [
			            'id',

						[
						'attribute' => 'props_category_id',
						'label'=>Yii::t('backend','Props Class'),
						'value'=>function($model){
							return \backend\models\DwPropsCategory::get_ropsname($model->props_account_pid,$model->props_category_id);
	
						}
						],
						[
						'attribute' => 'props_account_id',
						'label'=>Yii::t('backend','The Account'),
						'content'=>
						function($model){
							$account = \backend\models\DwAuthAccount::getAccountById($model->props_account_id);
							if( isset($account['name'])){
								return $account['name'];
							}else{
								return Yii::t('backend','No Account');
							}
							},
						],
			            'props_name',
			            'props_description',
		        		[
							'attribute' => 'props_img',			        		
							'label'=>Yii::t('backend','Props Icon'),
			        		'content'=>function($model){
		        				return Html::img(Yii::$app->urlManager->hostInfo.$model->props_img,
		        				['width'=>'30','height'=>'30']
		        				);
			        		}
		        		],
			            'props_credit',
			            [
			            'attribute' => 'props_available',
			            'label'=>Yii::t('backend','States'),
			            'content' =>function($model){
			            	$available = Yii::t('backend','Available');
			            	$onavailable = Yii::t('backend','Onavailable');
			            	$unknown = Yii::t('backend','Unknown');
			            	Yii::t('backend','States');
			            	if($model->props_available == 0 ){
			            		return '<span class="label label-success">'.$available.'</span>';
			            	}elseif($model->props_available == 1 ){
			            		return '<span class="label label-danger">'.$onavailable.'</span>';
			            	}else{
			            		return '<span class="label bg-navy">'.$unknown.'</span>';
			            	}
			            }
			            ],
			            ['class' => 'yii\grid\ActionColumn',
		        		'header' => Yii::t('backend','Operation'), //操作
		        		'template' => '{delete} {view}',
		        		'buttons' => [
	        				'view' => function ($url, $model) {
	        					$view = Yii::t('backend','Views');
	        					return '<a  onclick="props_view('.$model->id.')" data="'.$model->id.'" href="javascript:void(0);" title="'.$view.'" aria-label="'.$view.'"><span class="glyphicon glyphicon-eye-open"></span></a>';
	        				},
	        				'delete' => function ($url, $model) {
	        					$delete = Yii::t('backend','Deletes');
								return '<a data-pjax="0"  onclick="props_del('.$model->id.')" aria-label="'.$delete.'" title="'.$delete.'" href="javascript:;" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-trash"></span></a>';
							},
	        			],
        				'urlCreator' => function ($action, $model, $key, $index) {
        				
        				}],
			        ],
			    ]); ?>
			</div>
			<!-- /.box-body -->
			<!-- Loading (remove the following to stop the loading)-->
			<div class="overlay" style="display: none;" id="show_loading">
				<i class="fa fa-refresh fa-spin"></i>
			</div>
			<!-- end loading -->
		</div>
		<!-- /.box -->
	</div>
</div>

<?php 
AdminAsset::register($this);
$js = <<<JS

$(function() {
	change_add('props_category_op','');
});
JS;
$this->registerJs($js);
use backend\assets\AppAsset;
AppAsset::addScript($this,'@web/static/js/props.js');
?>
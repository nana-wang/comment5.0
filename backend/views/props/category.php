<?php
use common\helpers\Html;
use yii\grid\GridView;
use common\components\JsLayouts;
use yii\widgets\ActiveForm;
use Distill\Format\Simple\Img;

//use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $model common\models\Article */
/* @var $module string */

$this->title = Yii::t('backend','Props Class');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('backend','Props Class'),
    'url' => [
        'propindex'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header')?>
<h1><?= $this->title;?>
</h1>
<?php $this->endBlock()?>
<?php $proadd = Yii::t('backend','Proadd');?>
<?php $completed = Yii::t('backend','Completed');?>
<?php $accountgroupempty = Yii::t('backend','Account Group Empty');?>
<?php $editdo = Yii::t('backend','Editdo');?>
<script>
var props_add_name = '<?php echo $proadd;?>';
var props_edit_name = '<?php echo $completed;?>';
var accountgroupempty = '<?php echo $accountgroupempty;?>';

var editdo = '<?php echo $editdo;?>';
var sure_del = '<?php echo Yii::t('backend','Sure Delete');?>';
</script>
<div class="comment-index">
	<div class="box box-primary">
		<div class="box-body">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-default collapsed-box">
					<div id="add_loading" style="display: none;" class="overlay">
							<i class="fa fa-refresh fa-spin"></i>
						</div>
						<div class="box-header with-border">
							<h3 class="box-title"><?=Yii::t('backend','Proadd');?>/<?=Yii::t('backend','Editdo');?></h3>
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
										'action' => ['category_add'],
							            ]); 
							    ?>
							<div class="form-group col-md-3">
							<?php use yii\base\Widget;?>

									<select id="dwpropscategory-comment_channel_area" class="form-control" name="DwPropsCategory[props_account_id]">
										<option value="">--<?=Yii::t('backend','Account Group');?>--</option>
										<?= backend\widgets\MenuLeft::widget([],3);?>	
									</select>
									<?= $form->field($model, 'props_category_name') ->label(Yii::t('backend','Category Name')) ; ?> 
								
							</div>
							    <div class="form-group">
							    <input type="hidden" id="dwpropscategory-id" class="form-control" name="DwPropsCategory[id]">
							        <?= Html::Button(Yii::t('backend','Proadd'), ['class' => 'btn btn-block btn-info','id'=>'button']) ?>
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
						'props_category_name',
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
			            ['class' => 'yii\grid\ActionColumn',
		        		'header' => Yii::t('backend','Operation'),
		        		'template' => '{delete} {view}',
		        		'buttons' => [
	        				'view' => function ($url, $model) {
	        					$view = Yii::t('backend','Views');
	        					return '<a  class="view" onclick="props_category_view('.$model->id.')" data="'.$model->id.'" href="javascript:void(0);" title="'.$view.'" aria-label="'.$view.'"><span class="glyphicon glyphicon-eye-open"></span></a>';
	        				},
	        				'delete' => function ($url, $model) {
	        					$delete = Yii::t('backend','Deletes');
								return '<a data-pjax="0"  onclick="props_category_del('.$model->id.')" aria-label="'.$delete.'" title="'.$delete.'" href="javascript:;" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-trash"></span></a>';
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
use backend\assets\AppAsset;
AppAsset::addScript($this,'@web/static/js/props.js');
?>


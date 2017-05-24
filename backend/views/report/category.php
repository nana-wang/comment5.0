<?php
use common\helpers\Html;
use yii\grid\GridView;
use yii\base\Widget;
/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $model common\models\Article */
/* @var $module string */

$this->title = Yii::t('backend','Report Class');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('backend','Report Class'),
    'url' => [
        'category'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header')?>
<?= $this->title ;?>
<?php $this->endBlock()?>
<?php $retrieval = Yii::t('backend','Retrieval');?>
<?php $completeaudit = Yii::t('backend','Complete Audit');?>
<?php $proadd = Yii::t('backend','Proadd');?>
<?php $completed = Yii::t('backend','Completed');?>

<?php $surereview = Yii::t('backend','Sure Review');?>
<?php $nameempty = Yii::t('backend','Category Name Empty');?>
<?php $accountempty = Yii::t('backend','Account No Empty');?>
<?php $doing = Yii::t('backend','Doing');?>
<?php $suredelete = Yii::t('backend','Sure Delete');?>
<?php $comnotempty = Yii::t('backend','Category Name Empty');?>
<?php $addsucess = Yii::t('backend','Add Sucess');?>
<?php $modsuccess = Yii::t('backend','Modify Success');?>
<script>
var report_add_name = '<?php echo $retrieval;?>';
var report_edit_name = '<?php echo $completeaudit;?>';
var category_add_name = '<?php echo $proadd;?>';
var category_edit_name = '<?php echo $completed;?>';
var surereview = '<?php echo $surereview;?>';
var nameempty = '<?php echo $nameempty;?>';
var accountempty = '<?php echo $accountempty;?>';
var doing = '<?php echo $doing;?>';
var suredelete = '<?php echo $suredelete;?>'; //您确定要删除此项吗？
var addsucess = '<?php echo $addsucess;?>'; //添加成功
var modsuccess = '<?php echo $modsuccess;?>'; //修改成功
var comnotempty = '<?php echo $comnotempty;?>'; //内容不能为空，请填写相应的内容
var issearch = '0';
</script>
	<div class="comment-index">

	<div class="box box-primary">
		<div class="box-body">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-default collapsed-box">
					<div class="overlay" style="display: none;" id="add_loading">
							<i class="fa fa-refresh fa-spin"></i>
						</div>
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
							<div class="form-group col-md-9">
								<fieldset>
									<?=Yii::t('backend','Category Name');?>: <input type="text" class="form-control" id='name'> 
								</fieldset>
							</div>
							<div class="form-group col-md-9">
								<fieldset>
									<?=Yii::t('backend','The Account');?>:
									<select class="form-control"  id=account_report_cate  name="account_report_cate" >
									<option value="">--<?=Yii::t('backend','Account Group');?>--</option>
					<?= backend\widgets\MenuLeft::widget([],3);?>
								</select>
								</fieldset>
							</div>
							<button type="button" id="category_add"
								class="btn btn-block btn-info"></button>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="box box-default box-default-edit collapsed-box">
						<div class="overlay" style="display: none;" id="edit_loading">
							<i class="fa fa-refresh fa-spin"></i>
						</div>
						<div class="box-header with-border">
							<h3 class="box-title"><?=Yii::t('backend','Editdo');?></h3>
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
						
							<div class="form-group col-md-9">
								<fieldset>
									<?=Yii::t('backend','Category Name');?>: <input type="text" class="form-control" id='re_type_title'> 
									<input type="hidden" class="form-control" id='view_id'> 
								</fieldset>
							</div>

							
							<button type="button" id="category_edit" class="btn btn-block btn-info"></button>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</div>
			</div>
		</div>

		<div class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title"><?=Yii::t('backend','List Infor');?></h3>
			</div>
			<div class="box-body">
			 <?= GridView::widget([
			        'dataProvider' => $dataProvider,
			    	'emptyText' =>Yii::t('backend','No Data'),
			        'columns' => [
			            'id',
			            'report_type_title',
						[
						'attribute' => 'report_type_create',
						'label'=>Yii::t('backend','Creat Time'),
						'value'=>
						function($model){
							return  date('Y-m-d H:i',$model->report_type_create);
						},
						],
						[
						'attribute' => 'report_account_id',
						'label'=>Yii::t('backend','The Account'),
						'value'=>function($model){
							$account = \backend\models\DwAuthAccount::getAccountById($model->report_account_id);
							if( isset($account['name'])){
								return $account['name'];
							}else{
								return Yii::t('backend','No Account');
							}
						}
						],
			            ['class' => 'yii\grid\ActionColumn',
			            'header' => Yii::t('backend','Operation'),
						'template' => '{state} {view}',
						'buttons' => [
							'view' => function ($url, $model) {
								$view = Yii::t('backend','Views');
								return '<a  class="cview" data="'.$model->id.'" href="javascript:void(0);" title="'.$view.'" aria-label="'.$view.'"><span class="glyphicon glyphicon-eye-open"></span></a>';
							},
							'state' => function ($url, $model){
								$is_state = $model->report_type_state;
								if($is_state == 1){
									$state_name = Yii::t('backend','Enable');
									//return '<spanclass="state" data="'.$model->id.'" href="javascript:void(0);" title="'.$state_name.'"><font collor="green" >'.$state_name.'</font></span>';
									return '<a  class="state" data="'.$model->id.'" href="javascript:void(0);" title="'.$state_name.'" aria-label="'.$state_name.'"><span collor="green">'.$state_name.'</span></a>	';
								}else if($is_state == 2){
									$state_name = Yii::t('backend','Disable');
									return '<a  class="state" data="'.$model->id.'" href="javascript:void(0);" title="'.$state_name.'" aria-label="'.$state_name.'"><span collor="red">'.$state_name.'</span></a>	';
								}
							}
					    ],

			            ],
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
	</div>
</div>
<?php 
use backend\assets\AppAsset;
AppAsset::addScript($this,'@web/static/js/report.js');
?>
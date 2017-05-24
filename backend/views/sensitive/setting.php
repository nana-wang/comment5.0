<?php
use common\helpers\Html;
use yii\grid\GridView;
use yii\base\Widget;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $model common\models\Article */
/* @var $module string */

$this->title = Yii::t('backend','Grade Set');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('backend','Sensitive Word'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header')?>
<?= $this->title;?>
<?php $this->endBlock()?>
<?php $quikadd = Yii::t('backend','Quik Add');?>
<?php $completed = Yii::t('backend','Completed');?>
<?php $retrieval = Yii::t('backend','Retrieval');?>

<?php $namempty = Yii::t('backend','Name Not Empty');?>
<?php $expempty = Yii::t('backend','Explain Not Empty');?>
<?php $accempty = Yii::t('backend','Account Not Empty');?>
<?php $doing = Yii::t('backend','Doing');?>
<?php $uploadex = Yii::t('backend','Upload Excel XLS');?>
<?php $typenot = Yii::t('backend','Type Not Right');?>
<?php $pleselect = Yii::t('backend','Please Select Account');?>
<?php $fillsensitive = Yii::t('backend','Fill Sensitive Words');?>
<?php $fillvalue = Yii::t('backend','Fill Sensitive Value');?>
<?php $filldictionary = Yii::t('backend','Fill Dictionary');?>
<?php $chosemeth = Yii::t('backend','Chose Upload Mode');?>
<?php $comnotempty = Yii::t('backend','Comment Not Empty');?>
<?php $suredelete = Yii::t('backend','Sure Delete');?>
<script>
var sensitive_add_name = '<?php echo $quikadd;?>';
var sensitive_search_name = '<?php echo $retrieval;?>';
var sensitive_edit_name = '<?php echo $completed;?>';

var namempty = '<?php echo $namempty;?>';
var expempty = '<?php echo $expempty;?>';
var accempty = '<?php echo $accempty;?>';
var doing = '<?php echo $doing;?>'; //处理中...
var uploadex = '<?php echo $uploadex;?>';
var typenot = '<?php echo $typenot;?>';
var pleselect = '<?php echo $pleselect;?>'; //请选择所属账户
var fillsensitive = '<?php echo $fillsensitive;?>'; //请填写敏感词
var fillvalue = '<?php echo $fillvalue;?>'; //请填写敏感词替换值
var filldictionary = '<?php echo $filldictionary;?>'; //请填写词典生成位置
var chosemeth = '<?php echo $chosemeth;?>'; //请选择上传方式
var comnotempty = '<?php echo $comnotempty;?>'; //内容不能为空，请填写相应的内容
var suredelete = '<?php echo $suredelete;?>'; //您确定要删除此项吗？
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
							<h3 class="box-title"><?=Yii::t('backend','Quick Add');?></h3>
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
									<?=Yii::t('backend','Names');?>: <input type="text" class="form-control" id='name'> 
									<?=Yii::t('backend','Explain');?>: <input	type="text" class="form-control" id='description'>
									<?=Yii::t('backend','The Account');?>:<select class="form-control" id='level_account'  name="level_account" >
									<option value="">--<?=Yii::t('backend','Account Group');?>--</option>
					<?= backend\widgets\MenuLeft::widget([],3);?>
								</select>
								</fieldset>
							</div>

							<button type="button" id="sensitive_add"
								class="btn btn-block btn-info"></button>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="box box-default box-default-edit collapsed-box" >
						<div class="overlay" style="display: none;" id="edit_loading">
							<i class="fa fa-refresh fa-spin"></i>
						</div>
						<div class="box-header with-border">
							<h3 class="box-title"><?=Yii::t('backend','Editdo');?></h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool"
									data-widget="collapse">
									<i class="fa_edit fa fa-plus"></i>
								</button>
							</div>
							<!-- /.box-tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body  box-body-edit">
						
							<div class="form-group col-md-9">
								<fieldset>
									<?=Yii::t('backend','Names');?>: <input type="text" class="form-control" id='view_name'> 
									<?=Yii::t('backend','Explain');?>: <input	type="text" class="form-control" id='view_description'>
									<input type="hidden" class="form-control" id='view_id'> 
								</fieldset>
							</div>

							
							<button type="button" id="sensitive_edit"
								class="btn btn-block btn-info"></button>
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
            'sensitive_name',
            'sensitive_description',
			[
			'attribute' => 'sensitive_account_id',
			'label'=>Yii::t('backend','The Account'),
			'value'=>
			function($model){
				$account = \backend\models\DwAuthAccount::getAccountById($model->sensitive_account_id);
				if( isset($account['name'])){
					return $account['name'];
				}else{
					return Yii::t('backend','No Account');
				}
			},
			],
			[
			'attribute' => 'sensitive_time',
			'label'=>Yii::t('backend','Operation Time'),
			'value'=>
			function($model){
				return  date('Y-m-d H:i',$model->sensitive_time);   //主要通过此种方式实现
			},
			],
            ['class' => 'yii\grid\ActionColumn',
			'header' => Yii::t('backend','Operation'),
			'template' => '{delete} {view}',
			'buttons' => [
				'view' => function ($url, $model) {
					$view = Yii::t('backend','Views');
					return '<a  class="view" data="'.$model->id.'" href="javascript:void(0);" title="'.$view.'" aria-label="'.$view.'"><span class="glyphicon glyphicon-eye-open"></span></a>';
				},
				'delete' => function ($url, $model) {
					$delete = Yii::t('backend','Deletes');
					return '<a  class="view_del" data="'.$model->id.'" href="javascript:void(0);" title="'.$delete.'" aria-label="'.$delete.'"  ><span class="glyphicon glyphicon-trash"></span></a>	';				
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
AppAsset::addScript($this,'@web/static/js/sensitive.js');
?>
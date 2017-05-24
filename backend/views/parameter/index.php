<?php
use common\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $model common\models\Article */
/* @var $module string */

$this->title = Yii::t('backend','Account Param'); //账户参数
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('backend','Param Set'), //参数设置
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

<?php $suredelete = Yii::t('backend','Sure Delete');?>
<?php $reporttimenum = Yii::t('backend','Parameter Setting Empty');?>

<?php $doing = Yii::t('backend','Doing');?>
<?php $uploadex = Yii::t('backend','Upload Excel XLS');?>
<?php $accountreport = Yii::t('backend','Account or Report');?>
<?php $typenot = Yii::t('backend','Type Not Right');?>
<?php $pleselect = Yii::t('backend','Please Select Account');?>
<?php $fillsensitive = Yii::t('backend','Fill Sensitive Words');?>
<?php $fillvalue = Yii::t('backend','Fill Sensitive Value');?>
<?php $filldictionary = Yii::t('backend','Fill Dictionary');?>
<?php $chosemeth = Yii::t('backend','Chose Upload Mode');?>
<script>
var parameter_add_name = '<?php echo $quikadd;?>'; //快捷添加
var parameter_edit_name = '<?php echo $completed;?>'; //完成修改
var parameter_search_name = '<?php echo $retrieval;?>'; //检索

var suredelete = '<?php echo $suredelete;?>'; //您确定要删除此项吗？
var reporttimenum = '<?php echo $reporttimenum;?>'; //举报时间和次数
var doing = '<?php echo $doing;?>'; //处理中...
var uploadex = '<?php echo $uploadex;?>'; //‘账户组’或者‘举报送审次数和时间’不能为空
var accountreport = '<?php echo $accountreport;?>'; 
var typenot = '<?php echo $typenot;?>'; //上传文件类型不正确
var pleselect = '<?php echo $pleselect;?>'; //请选择所属账户
var fillsensitive = '<?php echo $fillsensitive;?>'; //请填写敏感词
var fillvalue = '<?php echo $fillvalue;?>'; //请填写敏感词替换值
var filldictionary = '<?php echo $filldictionary;?>'; //请填写词典生成位置
var chosemeth = '<?php echo $chosemeth;?>'; //请选择上传方式
var is_number = '<?php echo Yii::t('backend','Is Number');?>'; //参数为数字类型
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
									<?php use yii\base\Widget;?>
<?php echo Yii::t('backend','The Account');?>
									<select id="dwcommentsearch-comment_channel_area" class="form-control" name="DwCommentSearch[comment_channel_area]">
										
										<option value="">--<?=Yii::t('backend','Account Group');?>--</option>
										<?= \backend\models\DwAuthAccount::getMainAccountRedis();?>
														
									</select>
									<?=Yii::t('backend','Report Snum');?>: <input type="text" class="form-control" id='parameter_report_num'> 
									<?=Yii::t('backend','Report Interval Num');?>: <input type="text" class="form-control" id='parameter_report_brush'>
								</fieldset>
							</div>

							<button type="button" id="parameter_add"
								class="btn btn-block btn-info"></button>
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
							<div class="form-group col-md-9"	>
								<!-- <label>所属账户</label> 
								<select class="form-control" id='view_name'  name="view_name" disabled>
									<option value="">--账户组--</option>
									<?= backend\widgets\MenuLeft::widget([],3);?>
								</select> -->
								<fieldset>
									<!-- 账户组：<input type="text" class="form-control" id='view_name' readonly>  -->
									<?=Yii::t('backend','Report Snum');?>: <input type="text" class="form-control" id='view_parameter_report_num'> 
									<?=Yii::t('backend','Report Interval Num');?>: <input	type="text" class="form-control" id='view_parameter_report_brush'>
									<input type="hidden" class="form-control" id='view_id'> 
								</fieldset>
							</div>
							
							
							<button type="button" id="parameter_edit"
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
            [
			'attribute' => 'parameter_account_id',
			'label'=>Yii::t('backend','The Account'),
			'content'=>
			function($model){
				$msg = Yii::t('backend','No Account');
				$account = \backend\models\DwAuthAccount::getAccountById($model->parameter_account_id);
				if( isset($account['name'])){
					return $account['name'];
				}else{
					return $msg;
				}
				},
			],
            'parameter_report_num',
            'parameter_report_brush',
			[
			'attribute' => 'parameter_time',
			'label'=>Yii::t('backend','Operation Time'), //操作时间
			'value'=>
			function($model){
				return  date('Y-m-d H:i',$model->parameter_time);   //主要通过此种方式实现
			},
			],
            ['class' => 'yii\grid\ActionColumn',
			'header' => Yii::t('backend','Operation'), //操作
			'template' => '{delete} {view}',
			'buttons' => [
				'view' => function ($url, $model) {
					$view = Yii::t('backend','Views');
					$account_flg = \backend\models\search\DwCommentSearch::getPowerByaccountid( $model->parameter_account_id);
					
					if( $account_flg){
						return '<a  class="view" data="'.$model->id.'" href="javascript:void(0);" title="'.$view.'" aria-label="'.$view.'"><span class="glyphicon glyphicon-eye-open"></span></a>';
					}
				},
				'delete' => function ($url, $model) {
					$delete = Yii::t('backend','Deletes');
					$account_flg = \backend\models\search\DwCommentSearch::getPowerByaccountid( $model->parameter_account_id);
					if( $account_flg){
						return '<a  class="view_del" data="'.$model->id.'" href="javascript:void(0);" title="'.$delete.'" aria-label="'.$delete.'"  ><span class="glyphicon glyphicon-trash"></span></a>';
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
AppAsset::addScript($this,'@web/static/js/parameter.js');
?>
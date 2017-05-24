<?php
use common\helpers\Html;
use yii\grid\GridView;
use backend\widgets\ActiveForm;
use yii\jui\DatePicker;
//use kartik\date\DatePicker;
use mdm\admin\AdminAsset;
/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $model common\models\Article */
/* @var $module string */

$this->title = Yii::t('backend','Manage Report');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('backend','Manage Report'),
    'url' => [
        'index'
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
<?php $comnotempty = Yii::t('backend','Comment Not Empty');?>
<?php $addsucess = Yii::t('backend','Add Sucess');?>
<?php $modsuccess = Yii::t('backend','Modify Success');?>
<script>
var report_add_name = '<?php echo $retrieval;?>';
var report_edit_name = '<?php echo $completeaudit;?>';
var category_add_name = '<?php echo $proadd;?>';
var category_edit_name = '<?php echo $completed;?>';
var issearch = '<?php echo $issearch;?>';
var surereview = '<?php echo $surereview;?>';
var nameempty = '<?php echo $nameempty;?>';
var accountempty = '<?php echo $accountempty;?>';
var doing = '<?php echo $doing;?>';
var suredelete = '<?php echo $suredelete;?>'; //您确定要删除此项吗？
var comnotempty = '<?php echo $comnotempty;?>'; //内容不能为空，请填写相应的内容
var addsucess = '<?php echo $addsucess;?>'; //添加成功
var modsuccess = '<?php echo $modsuccess;?>'; //修改成功
</script>
<style>
img{float:left;border:1px solid rgba(212,208,208,1.00); padding:2px; width:150px; height:100px;overflow:hidden} 
img{float:left;max-width:200px;_width:expression(this.width &gt; 200 ? "150px" : this.width);} 
</style>

<div class="comment-index">
	<div class="box box-primary">
		<div class="box-body">
			<div class="row">
				<div class="col-md-12">
					<?php 
						if( $search_flg == true){
							$class1 = 'box box-default box-default-viev';
							$class2 = 'fa fa-minus';
						}else{
							$class1 = 'box box-default collapsed-box box-default-viev';
							$class2 = 'fa fa-plus';										
						}
					?>
					<div class="<?php echo $class1;?>">
						<div class="overlay" style="display: none;" id="show_loading">
							<i class="fa fa-refresh fa-spin"></i>
						</div>
						<div class="box-header with-border">
							<h3 class="box-title"><?=Yii::t('backend','Search Operation');?></h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool"
									data-widget="collapse">
									<i class="<?php echo $class2;?>"></i>
								</button>
							</div>
							<!-- /.box-tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body box-body-viev">
							
							<div class="form-group col-md-6">
								<label for="exampleInputEmail1"><?=Yii::t('backend','Informantsuid');?></label> <input
									type="text" class="form-control" id="report_from_uid"
									placeholder="<?=Yii::t('backend','Informantsuid');?>" value="<?php echo $report_from_uid;?>">
							</div>

							<div class="form-group col-md-6">
								<label for="exampleInputEmail1"><?=Yii::t('backend','Beinformantsuid');?></label> <input
									type="text" class="form-control" id="report_uid"
									placeholder="<?=Yii::t('backend','Beinformantsuid');?>" value="<?php echo $report_uid;?>">
							</div>
							<div class="form-group col-md-6">
								<label><?=Yii::t('backend','Start Time');?></label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
<?= DatePicker::widget(['id' => 'start_time','options' => ['class' => 'form-control'],'value'=>$start_time])?>
								</div>
							</div>
							<div class="form-group col-md-6">
								<label><?=Yii::t('backend','End Time');?></label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<?= DatePicker::widget(['id' => 'end_time','options' => ['class' => 'form-control'],'value'=>$end_time])?>
								</div>
							</div> 
							<div class="form-group col-md-6">
								<label for="exampleInputEmail1"><?=Yii::t('backend','Cited Content');?></label> 
								<input type="text" id="report_content" class="form-control" 
									placeholder="<?=Yii::t('backend','Cited Content');?>" value="<?php echo $report_content;?>">
							</div>
							<div class="form-group col-md-6">
								<label for="exampleInputEmail1"><?=Yii::t('backend','Beheld Title');?></label> 
								<input type="text" id="report_title" class="form-control" 
									placeholder="<?=Yii::t('backend','Beheld Title');?>" value="<?php echo $report_title;?>">
							</div>
							
							
							<div class="form-group col-md-6">
							<div class="">
							<label class="control-label"><?=Yii::t('backend','Account Group');?></label>
							<select id="report_account" onChange="change('')" class="form-control" name="report_account">
							<option value="">--<?=Yii::t('backend','Account Group');?>--</option>
								<?= backend\widgets\MenuLeft::widget([],3);?>	
							</select>
							</div> 
							</div>
							
							<div class="form-group col-md-6">
								<label><?=Yii::t('backend','Report Class');?></label>
								<select class="form-control" id="report_idtype">
									<option value=""><?=Yii::t('backend','Report Class');?></option>
									<?php 
								    	if( !empty($types)){
											foreach($types as $key =>$v){
												$checked = '';
												if($report_idtype == $key){
													$checked = 'selected';
												}
												echo '<option '.$checked.' value='.$key.'>'.$v['report_type_title'].'</option>';
											}
										}
								    ?>
								</select>
							</div>
							<div class="form-group col-md-6">
								<label><?=Yii::t('backend','Audit Status');?></label> 
								<select name="status" class="form-control" id="report_status">
									<option value="0">--<?=Yii::t('backend','Please Select');?>--</option>
									<option value="1" <?php echo $report_status=='1'?'selected':'';?>><?=Yii::t('backend','Audit');?></option>
									<option value="2" <?php echo $report_status=='2'?'selected':'';?>><?=Yii::t('backend','Audit Pass');?></option>
								
								</select>

							</div>
							
							<button type="button" id="report_add"
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
						[
						'attribute' => 'id',
						'contentOptions'=>['style'=>'width:4%'],
						'label'=>'id',
						'value'=>
							function ($model) {
								return $model->id;
							},
						],
// 						[
// 						'attribute' => 'report_idtype',
// 						'contentOptions'=>['style'=>'width:5%'],
// 						'label'=>'举报uid',
// 						'content'=>
// 						function ($model) {
// 							return $model->report_from_uid;
// 						},
// 						],
// 						[
// 						'attribute' => 'report_idtype',
// 						'contentOptions'=>['style'=>'width:5%'],
// 						'label'=>'被举报uid',
// 						'content'=>
// 						function ($model) {
// 							return $model->report_uid;
// 						},
// 						],
						
			            [
						'attribute' => 'report_idtype',
						'contentOptions'=>['style'=>'width:5%'],
						'label'=>Yii::t('backend','Class Ification'),
						'content'=>
							function ($model) {
								$Informantsuid = Yii::t('backend','Informantsuid');
								$Beinformantsuid = Yii::t('backend','Beinformantsuid');
								$report_type= \backend\models\ReportCategory::get_report_redis_buyid($model->report_idtype,$model->report_account,true);
								$uids = $Informantsuid.'【'.$model->report_from_uid.'】,'.$Beinformantsuid.'【'.$model->report_uid.'】';
								return '<span data-placement="bottom" data-original-title="'.$uids.'" data-toggle="tooltip" style="text-align:left">'.$report_type.'</span>';
								},
						],
						[
						'attribute' => 'report_content_title',
						'contentOptions'=>['style'=>'width:30%'],
						'label'=>Yii::t('backend','Report Title'),
						'content'=>
							function($model){
// 								$title_old = strip_tags($model->report_content_title);
// 							if( strlen($title_old) > 50 ){
// 									$title =  mb_strcut($title_old, 0, 50, 'utf-8') . '...';
// 									$d = '<span data-placement="bottom" data-original-title="'.$title_old.'" data-toggle="tooltip" style="text-align:left">'.$title.'</span>';
// 								}else{
// 									$d = $title_old;
// 								}
								$reporttitle = Yii::t('backend','Report Title');
								$reportcontent = Yii::t('backend','Report Content');
								if( isset($model->commentExp->comment_content)){
								$report_title = $model->commentExp->comment_content;
								}else {
								$report_title = Yii::t('backend','No Comment');
								}
// 								
								$conteng ='<p>'.$reporttitle.'：<a href="'.$model->report_url.'"  target="_blank">'.$report_title.'</a></p>';
								$conteng .='<p>'.$reportcontent.'：'.$model->report_content.'</p>';
								return $conteng;
							}
						],
						[
						'attribute' => 'report_account',
						'contentOptions'=>['style'=>'width:5%'],
						'label'=>Yii::t('backend','The Account'),
						'value'=>
						function ($model) {
							$account = \backend\models\DwAuthAccount::getAccountById($model->report_account);
								if( isset($account['name'])){
									return $account['name'];
								}else{
									return Yii::t('backend','No Account');
								}
							},
						],
						[
						'attribute' => 'report_create',
						'label'=>Yii::t('backend','Times'),
						'contentOptions'=>['style'=>'width:5.5%'],
						'value'=>
						function($model){
							return  date('Y-m-d H:i',$model->report_create);
						},
						],
						[
						'attribute' => 'report_status',
						'label'=>Yii::t('backend','States'),
						'contentOptions'=>['style'=>'width:8%'],
						'content'=>
						function($model){
							if($model->report_status==1){
								 $audit = Yii::t('backend','Audit');
			                     return '<span class="label pull-left bg-yellow">'.$audit.'</span>' ;
							}elseif($model->report_status==2){
									$audittime = Yii::t('backend','Audit Time');
									$unaudittime = Yii::t('backend','Unaudit Time');
									$auditpass = Yii::t('backend','Audit Pass');
									
								   if(!empty($model->report_audtime)){
									$report_audtime =  date('Y-m-d H:i',$model->report_audtime);
									$report_audtime = $audittime.':'.$report_audtime;								
									}else{
									$report_audtime= $unaudittime;	
									}
								
								return '<span class="label pull-left bg-blue" data-placement="bottom" data-original-title="'.$report_audtime.'" data-toggle="tooltip" style="text-align:left">'.$auditpass.'</span>' ;
							}elseif($model->report_status==3){
								$auditfailure = Yii::t('backend','Audit Fail');
								return '<span class="label pull-left bg-red">'.$auditfailure.'</span>' ;
							}
						},
						],
			            ['class' => 'yii\grid\ActionColumn',
			            'header' => Yii::t('backend','Operation'),
			            'contentOptions'=>['style'=>'width:4%'],
						'template' => '{delete}{view}',
						'buttons' => [
							'view' => function ($url, $model) {
									$view = Yii::t('backend','Views');
									return '<a class="rview" href="/admin/index.php?r=report%2Fview&amp;report_id='.$model->id.'&amp;id='.$model->report_comment_id.' title="编辑" aria-label="编辑" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span></a>';
								},
							'delete' => function ($url, $model) {
								
   								return '<a class="view_del" href="/admin/index.php?r=report%2Frepdel&amp;id='.$model->id.'&amp;report_comment_id='.$model->report_comment_id.'" title="删除" aria-label="删除" data-confirm="'.  Yii::t('backend','Sure Delete').'" data-method="post" data-pjax="0"><span class="glyphicon glyphicon-trash"></span></a>&nbsp;';
							}
					    ],
					    'urlCreator' => function ($action, $model, $key, $index) {
							if ($action === 'delete') {
								return ['repdel', 'id' => $model->id,'report_comment_id'=>$model->report_comment_id];
							} 
						}
			            ],
			        ],
			    ]); ?>
			</div>
			<!-- /.box-body -->
			<!-- Loading (remove the following to stop the loading)-->
			<div class="overlay" style="display: none;" id="repot_loading">
				<i class="fa fa-refresh fa-spin"></i>
			</div>
			<!-- end loading -->
		</div>
	</div>
</div>
<?php
AdminAsset::register($this);
$js = <<<JS


$(function() {
	var account = '$report_account';
		$('#report_account').val(account);
});

JS;
$this->registerJs($js);
?>
<?php 
use backend\assets\AppAsset;
AppAsset::addScript($this,'@web/static/js/report.js');
?>

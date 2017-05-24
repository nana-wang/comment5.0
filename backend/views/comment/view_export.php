<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use mdm\admin\AdminAsset;

/* @var $this yii\web\View */
/* @var $model common\models\Comment */

$this->title = $model->id;
$this->title = Yii::t('backend','Comment Info'); //'评论详细信息
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('backend','Comment Info'), //'评论'
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $opersecond = Yii::t('backend','Operation Of Second');?>
<?php $userlocking = Yii::t('backend','User Locking');?>
<?php $sureoper = Yii::t('backend','Sure Operation');?>
<style>
img{border:1px solid rgba(212,208,208,1.00); padding:2px; width:150px; height:100px;overflow:hidden} 
img{max-width:200px;_width:expression(this.width &gt; 200 ? "150px" : this.width);} 
</style>
<script>
var opersecond = '<?php echo $opersecond;?>';
var userlocking = '<?php echo $userlocking;?>';
var sureoper = '<?php echo $sureoper;?>'; //您确定要进行此次操作吗？
var sure_addblack = '<?php echo Yii::t('backend','Sure Remove Black');?>'; //确定要将此用户移入黑名单吗？
</script>

<div class="comment-view">
	<div class="row">
		<div class="col-sm-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><?=Yii::t('backend','Detailed Infor');?></h3>
					<span class="label label-primary pull-right"></span>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					
						<!-- text input -->
						<blockquote>
							<p><?=Yii::t('backend','Review Topic');?></p>
							<small><?php echo $model->comment_title;?> </cite></small>
						</blockquote>
						<?php 
						if($model->comment_parent_id > 0){ 
						?>
						<blockquote>
							<small><cite title="Source Title">
									<?=Yii::t('backend','Reply Topic');?>：
									<?php echo  Html::decode($parent_data['comment_content']);?>
									 </cite></small>
						</blockquote>
						<?php 
						}
						?>
						
						<blockquote>
							<small><cite title="Source Title">
									<?=Yii::t('backend','New content');?>：
									<?php 
							$comment_content = $model->commentExp->comment_content;
							 // 检测敏感词
							 if(  isset($sensitive_word) && !empty($sensitive_word)) {
								foreach ($sensitive_word as $s_w_v){
		$comment_content = preg_replace("/$s_w_v/i", "<font color=red><b>$s_w_v</b></font>", $comment_content);
								}

							 }
							 echo  Html::decode($comment_content);
							?>
									 </cite></small>
						</blockquote>
					<div class="form-group col-md-3">
							<label><?=Yii::t('backend','Channel Area');?></label> 
							<?php 
							$account = \backend\models\DwAuthAccount::getAccountById($model->comment_channel_area);
							if( isset($account['name'])){
								$account_v= $account['name'];
							}else{
								$account_v= Yii::t('backend','No Account');
							}
							$form_id =\backend\models\DwFourmCategory::get_category($model->comment_user_type);
								
							?>
							：<?php echo $account_v;?>
						</div>

						<div class="form-group col-md-3">
							<label><?=Yii::t('backend','Form Types');?></label> 
							：<?php echo $form_id;?>
						</div>

						
						<div class="form-group col-md-3">
							<label><?=Yii::t('backend','Creat Time');?></label> 
							：<?php echo date('Y-m-d H:i',$model->comment_created_at);?>
						</div>
						
					
						<?php 	if( $model->comment_status ==  Yii::$app->params['sensitive_audit']) { ?>
						<div class="form-group col-md-3">
							<label>IP地址</label> ：<?php echo $model->comment_ip;?>
						</div>
						
						<div class="form-group col-md-3">
							<label><?=Yii::t('backend','Stem From');?></label> 
							：<?php echo $model->comment_device;?>
						</div>
						<div class="form-group col-md-3">
							<label><?=Yii::t('backend','User Id');?></label> 
							：<?php echo $model->comment_user_id;?>
						</div>
						<?php 	}elseif($model->comment_status ==  Yii::$app->params['report_audit']){	
									if( !empty($report)) {
?>
                        <div class="form-group col-md-3">
							<label><?=Yii::t('backend','Report Class');?></label> 
							：<?php echo  \backend\models\ReportCategory::get_report_redis_buyid($report['report_idtype'],$report['report_account'],true);
								?>
						</div>
						<div class="form-group col-md-3">
							<label><?=Yii::t('backend','Informants');?></label> 
							：<?php echo $report['report_from_uid'];?>
						</div>
						<div class="form-group col-md-3">
							<label><?=Yii::t('backend','Beinformants');?></label> 
							：<?php echo $report['report_uid'];?>
						</div>
						<div class="form-group col-md-3">
							<label><?=Yii::t('backend','Report Time');?></label> 
							：<?php echo date('Y-m-d H:i',$report['report_create']);?>
						</div>
<?php 

									}
					    ?>
						
						<?php  } // 举报结束?>
						<div class="form-group col-md-3">
							<label><?=Yii::t('backend','Comment Status');?></label> 
							：<?php echo \backend\models\DwComment::comment_stat($model->comment_status);?>
							
						</div>
						<?php  
							
if( !empty( $comment_log)){
							$auditrecord = Yii::t('backend','Audit Record');
							$operator = Yii::t('backend','Operator');
							$operationtime = Yii::t('backend','Operation Time');
							$states = Yii::t('backend','Comment Status');
							$reason = Yii::t('backend','Historical State');
							echo '<div class="form-group col-md-12">';
							echo '<label>'.$auditrecord.'</label><br>';
							foreach ( $comment_log as $comment_log_k =>$comment_log_v ){
								$operator_name = \backend\models\DwUser::get_user_redis_name($comment_log_v['operation_id']);
								echo $operator.'：' .$operator_name . '&nbsp;&nbsp;&nbsp;&nbsp;';
								
								echo $operationtime.'：' .date('Y-m-d H:i',$comment_log_v['operation_time']). '&nbsp;&nbsp;&nbsp;&nbsp;';
								echo $reason.'：';
								echo '<span class="label label-success" >'.\backend\models\DwComment::comment_stat($comment_log_v['operation_reason']).'</span>&nbsp;&nbsp;' ;
								echo $states.'：';
								echo '<span class="label label-success" >'.\backend\models\DwComment::comment_stat($comment_log_v['comment_status']).'</span>' ;
								
								echo '<br/>';
							}
						}
						
						
						echo '</div>';
						?>
						

						
							
					
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->
		</div>
		<!-- /.col -->
	</div>
</div>

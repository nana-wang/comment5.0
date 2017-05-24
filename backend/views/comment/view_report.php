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
<script>
var opersecond = '<?php echo $opersecond;?>';
var userlocking = '<?php echo $userlocking;?>';
var sureoper = '<?php echo $sureoper;?>'; //您确定要进行此次操作吗？
</script>
举报视图
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
							<small><cite title="Source Title">
									<?=Yii::t('backend','Review Topic');?>：<?php echo $model->comment_title;?> </cite></small>
						</blockquote>
						<div class="form-group col-md-12"></div>
						<?php 
						if($model->comment_parent_id > 0){ 
						?>
							<div class="form-group col-md-12">
								<label><?=Yii::t('backend','Reply Topic');?></label> 
								<textarea disabled class="form-control" rows="8" placeholder="<?=Yii::t('backend','Replycom');?>"><?php echo $parent_data['comment_content'];?>
								</textarea>
							</div>
						<?php 
						}
						?>
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
							<input type="text" disabled class="form-control" value="<?php echo $account_v;?>" placeholder="<?=Yii::t('backend','Subject Headings');?> ...">
						</div>

						<div class="form-group col-md-3">
							<label><?=Yii::t('backend','Use Types');?></label> 
							<input type="text" disabled class="form-control"
								value="<?php echo $form_id;?>" placeholder="<?=Yii::t('backend','Use Types');?> ...">
						</div>

						
						<div class="form-group col-md-3">
							<label><?=Yii::t('backend','Creat Time');?></label> <input type="text" disabled class="form-control"
								value="<?php echo date('Y-m-d H:i',$model->comment_created_at);?>" placeholder="2016-08-22 10:00:00">
						</div>
						<!-- textarea -->
						<div class="form-group col-md-12">
							<label><?=Yii::t('backend','New content');?></label>
							<textarea disabled class="form-control" rows="8" placeholder="<?=Yii::t('backend','Replycom');?>"><?php echo $model->commentExp->comment_content;?>
							</textarea>
						</div>
						<?php  
							
						if( !empty( $comment_score)){
						$Scores = Yii::t('backend','Scores');
						echo '<div class="form-group col-md-12">';
						echo '<label>'.$Scores.'</label><br>';
							foreach ( $comment_score as $comment_score_k =>$comment_score_v ){
								echo $comment_score_v['title'] . ':';
								foreach ($comment_score_v['item_ext'] as $ext_key => $ext_v){
									if( $ext_v['item_tag_type'] == 3){
										if( in_array($ext_v['id'],$comment_score_v['checked_item_ext'])){
											$check = 'checked';
										}else{
											$check='';
										}									
										echo '<input disabled type="checkbox" '.$check.' value="'.$ext_v['id'].'" item_tag_score="'.$ext_v['item_tag_score'].'" item_id="'.$ext_v['item_id'].'">' . $ext_v['item_tag_name'] . '&nbsp;';
									}else{							    
										if( $comment_score_v['checked_item_ext'] == $ext_v['id']){
											$check = 'checked';
										}else{
											$check='';
										}								
										echo '<input disabled '.$check.' type="radio" value="'.$ext_v['id'].'" item_tag_score="'.$ext_v['item_tag_score'].'" item_id="'.$ext_v['item_id'].'">' . $ext_v['item_tag_name'] . '&nbsp;';
									}
								}
								echo '<br><br>';
							}
						}
						
						echo '</div>';
						
						if( !empty( $comment_log)){
							$auditrecord = Yii::t('backend','Audit Record');
							$operator = Yii::t('backend','Operator');
							$operationtime = Yii::t('backend','Operation Time');
							$states = Yii::t('backend','States');
							$reason = Yii::t('backend','Reason');
							echo '<div class="form-group col-md-12">';
							echo '<label>'.$auditrecord.'</label><br>';
							foreach ( $comment_log as $comment_log_k =>$comment_log_v ){
								echo ''.$operator.'：' .$comment_log_v['operation_id'] . '&nbsp;&nbsp;&nbsp;&nbsp;';
								echo ''.$operationtime.'：' .date('Y-m-d H:i',$comment_log_v['operation_time']). '&nbsp;&nbsp;&nbsp;&nbsp;';
								echo ''.$states.'：';
								echo '<span class="label label-success" >'.\backend\models\DwComment::comment_stat($comment_log_v['comment_status']).'</span>' ;
								echo ''.$reason.'：';
								echo '<span class="label label-success" >'.\backend\models\DwComment::comment_stat($comment_log_v['operation_reason']).'</span>' ;
								echo '<br/>';
							}
						}
						
						echo '</div>';
						?>
						<?php $form = ActiveForm::begin([
						        'action' => ['view_save'],
						        'method' => 'post',
								'id'=>'operation_form'
						    ]); ?>
						

						<div class="form-group col-md-3">
							<label><?=Yii::t('backend','Comment Status');?></label> 
							<input type="text" disabled class="form-control" value="<?php echo \backend\models\DwComment::comment_stat($model->comment_status);?>" placeholder="<?=Yii::t('backend','Razer');?>...">
						</div>
						<?php
						$index = '';
						$reason = Yii::t('backend','For Reasons'); //'送审原因'
						$handoper = Yii::t('backend','Handling Operation'); //'发布操作'
						// 审批清单数据
						if( $from == 'approval'){
						$index ='<div class="form-group col-md-3">';
						$index .='<label>'.$handoper.'</label>';
						
						$index .='<select class="form-control" name="comment_status_public" id="comment_status_public">';
						foreach ($comment_stat as $key => $v){
							$index .='<option value="'.$key.'">'.$v.'</option>';
						}
						$index .='</select>';
						$index .='</div>';
						$reason = Yii::t('backend','Operation Reason'); //'操作原因'
						}
						// 操作原因
						$index .='<div class="form-group col-md-3">';
						$index .='<label>'.$reason.'</label>';
						if( $model->comment_status == 7 || $model->comment_status == 8){
							$disable = 'disabled';
						}else{
							$disable = '';
						}
						$index .='<select '.$disable.' class="form-control" name="comment_status" id="comment_status">';
						foreach ($comment_stat_record as $key1 => $v1){
							$index .='<option value="'.$key1.'">'.$v1.'</option>';
						}
						$index .='</select>';
						$index .='</div>';
						echo $index;
						?>
							
							<input type="hidden" name='id' value="<?php echo $model->id;?>">
							<input type="hidden" name='from' value="<?php echo $from;?>">
							<input type="hidden" name=old_comment_status value="<?php echo $model->comment_status;?>">
							<div class="form-group col-md-3">
								<label>&nbsp;</label> 
								<input  type="button" onclick="doSubmitNew();" class="btn btn-block btn-success" value="<?=Yii::t('backend','Save');?>" />
							</div>
							<div class="form-group col-md-3">
								<label>&nbsp;</label> 
								<input  type="button" onclick="back();" class="btn btn-block btn-success" value="<?=Yii::t('backend','Returns');?>" />
						</div>
						<?php ActiveForm::end(); ?>
					
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->
		</div>
		<!-- /.col -->
	</div>
</div>
<script type="text/javascript">
function back(){
	window.location.href="<?php echo $back_url;?>"; 
}
function doSubmit(comment_status){
//         var comment_status_new = document.getElementById('comment_status').value;
//         if( comment_status == comment_status_new){
//         	 alert('评论状态操没有改变！');
//         	 return false;
//         }
// 		if( from == 'index'){

// 		}
//		var comment_log = ;
// 		if( comment_log > 1){
// 		    alert('审核超过两次，禁止操作');
// 		    return false;
// 		}else if(comment_log == 1 ){
// 			if(window.confirm('此次操作将会是二审操作，操作成功后将禁止审核操作，是否还要继续？')){ 
// 				document.getElementById('operation_form').submit();
// 			} 
// 		}else{
// 			document.getElementById('operation_form').submit();
// 		}

		if(window.confirm(opersecond)){ 
			
			document.getElementById('operation_form').submit();
		} 
		
}

function doSubmitNew(){
//  var comment_status_new = document.getElementById('comment_status').value;
//  if( comment_status == comment_status_new){
//  	 alert('评论状态操没有改变！');
//  	 return false;
//  }
//	if( from == 'index'){

//	}
//	if( comment_log > 1){
//	    alert('审核超过两次，禁止操作');
//	    return false;
//	}else if(comment_log == 1 ){
//		if(window.confirm('此次操作将会是二审操作，操作成功后将禁止审核操作，是否还要继续？')){ 
//			document.getElementById('operation_form').submit();
//		} 
//	}else{
//		document.getElementById('operation_form').submit();
//	}
    var comment_stat = <?php echo $model->comment_status;?>;
    if(comment_stat == 7  ){
				alert(userlocking);return false;
    }
	if(window.confirm(sureoper)){ 
		document.getElementById('operation_form').submit();
	} 
	
}
</script>

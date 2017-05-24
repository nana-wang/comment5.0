<?php
use common\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\base\Widget;
use mdm\admin\AdminAsset;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend','Approval List');//审批清单
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $selectdata = Yii::t('backend','Select Data');?>
<?php $chosereson = Yii::t('backend','Chose Operation Reson');?>
<?php $surebulk = Yii::t('backend','Sure Bulk');?>
<?php $thisitem = Yii::t('backend','This Item');?>
<script>
var selectdata = '<?php echo $selectdata;?>';
var chosereson = '<?php echo $chosereson;?>';
var surebulk = '<?php echo $surebulk;?>';
var issearch = '<?php echo $issearch;?>';
var thisitem = '<?php echo $thisitem;?>';
</script>
<style>
img{float:left;border:1px solid rgba(212,208,208,1.00); padding:2px; width:150px; height:100px;overflow:hidden} 
img{float:left;max-width:200px;_width:expression(this.width &gt; 200 ? "150px" : this.width);} 

.table th, .table td {
	text-align: center;
	vertical-align: middle;
}
.ht{
    border:;border-top:1px dotted #eee
    height: 1px;
    background: #333;
    background-image: -webkit-linear-gradient(left, #ccc, #333, #ccc);
    background-image: -moz-linear-gradient(left, #ccc, #333, #ccc);
    background-image: -ms-linear-gradient(left, #ccc, #333, #ccc);
    background-image: -o-linear-gradient(left, #ccc, #333, #ccc);
}
</style>

<div class="comment-index">
	<div class="box box-primary">
		<div class="box-body">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-default collapsed-box box-default-viev">
						<div id="edit_loading" style="display: none;" class="overlay">
							<i class="fa fa-refresh fa-spin"></i>
						</div>
						 <?php $form = ActiveForm::begin([
						        'action' => ['approval'],
						        'method' => 'get',
						    ]); ?>
						<div class="box-header with-border">
							<h3 class="box-title"><?=Yii::t('backend','Approval Review');?></h3>
							<div class="box-tools pull-right">       
								<button data-widget="collapse" class="btn btn-box-tool"
									type="button">
									<i class="fa fa-plus"></i>
								</button>
							</div>
							<!-- /.box-tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body box-body-view">
							<div class="form-group col-md-3">
							<?= $form->field($searchModel, 'comment_parent_id') ->dropDownList([1=>Yii::t('backend','Comments'),2=>Yii::t('backend','Reply')],['prompt'=>Yii::t('backend','Whole')])->label(Yii::t('backend','Comment Type')) ; ?> 
							</div>
							<div class="form-group col-md-3">
<?= $form->field($searchModel, 'comment_status') ->dropDownList($comment_stat_search,['prompt'=>Yii::t('backend','Please Select')])->label(Yii::t('backend','Comment Status')) ; ?> 
							</div>
							
							<div class="form-group col-md-3">
								<?= $form->field($searchModel, 'comment_ip_type') ->dropDownList([1=>'ip',2=>Yii::t('backend','Ip Segment')])->label(Yii::t('backend','Ip Range')) ; ?> 
	
							</div>
							<div class="form-group col-md-3">
								 <?php  echo $form->field($searchModel, 'comment_ip')->label(Yii::t('backend','Ip Range')) ; ?>  
							</div>
							<div  class="form-group col-md-3">
									<?php 
				echo $form->field($searchModel, 'comment_created_at')
				->widget(DatePicker::classname(), ['pluginOptions' =>['format'=>'yyyy-m-d'] ]);		
?>			

							</div>
							<div  class="form-group col-md-3">
								 <?php  echo $form->field($searchModel, 'comment_user_id')->label(Yii::t('backend','Uid Search')) ; ?>  
							</div>
							
								<div class="form-group col-md-3">
<div class="form-group field-dwcommentsearch-comment_channel_area">
<label class="control-label" for="dwcommentsearch-comment_channel_area"><?=Yii::t('backend','Account Group');?></label>
<select id="dwcommentsearch-comment_channel_area" class="form-control" name="DwCommentSearch[comment_channel_area]">
<option value="">--<?=Yii::t('backend','Account Group');?>--</option>
					<?= backend\widgets\MenuLeft::widget();?>
</select>

<div class="help-block"></div>
</div> 
							</div>
							<div  class="form-group col-md-3">
								 <?php  echo $form->field($searchModel, 'key')->label(Yii::t('backend','Keyword Search')) ; ?>  
							</div>
								
								<?= Html::submitButton(Yii::t('backend','Retrieval'), ['class' => 'btn btn-block btn-info']) ?>
					
						</div>
						<!-- /.box-body -->
						 <?php ActiveForm::end(); ?>
					</div>
					<!-- /.box -->
				</div>
			</div>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-md-12">
					<div class="box">
							<div class="box-header with-border">
							<h3 class="box-title"><?=Yii::t('backend','Batch Operation');?></h3>
							<div class="box-tools pull-right">       
							</div>
						     </div>
							<p><div class="form-group col-md-3">
							<select id="batch"  class="form-control">
							<option value="1"><?=Yii::t('backend','Release');?></option>
							<option value="2"><?=Yii::t('backend','To Examine');?></option>
							<option value="3"><?=Yii::t('backend','Hidden');?></option>
							<option value="del"><?=Yii::t('backend','Deletes');?></option>
							<option value="blicklist"><?=Yii::t('backend','Remove Black');?></option>
							</select></div>
							<div class="form-group col-md-3" style="display: none;">
							<select  id="batch_reason" class="form-control">
							<option value=""><?=Yii::t('backend','Operation Reason');?></option>
							<option value="4"><?=Yii::t('backend','Auto Inspection');?></option>
							<option value="5"><?=Yii::t('backend','Sensitive Inspection');?></option>
							<option value="6"><?=Yii::t('backend','Report Inspection');?></option>
							</select></div></p><div class="form-group col-md-3">
								<button type="button" onclick="batch_operation();" class="btn btn-block btn-info"><?=Yii::t('backend','Batch Operation');?></button>
							</div>
							<!-- /.box-tools -->
							<!-- /.box-tools -->
						<!-- /.box-header -->
						<div class="box-body">
						
<?= GridView::widget([
        'dataProvider' => $dataProvider,
    	'emptyText' =>Yii::t('backend','No Data'),
        'columns' => [
		    [   'class' =>'yii\grid\CheckboxColumn',
				'contentOptions'=>['style'=>'width:1%;text-align: center; vertical-align: middle;'],
				'checkboxOptions' => function($model) {
					return ['data' => $model->comment_url,'uid' => $model->comment_user_id,'account_id'=>$model->comment_channel_area];
				}
			],
			[
			'attribute' => 'comment_parent_id',
			'label'=>Yii::t('backend','Comment Content'),
			'contentOptions'=>['style'=>'width:45%','align'=>'left'],
			'content'=>
				function($model){
					$msg = Yii::t('backend','Review Topic');
					$title = '';
					if($model->comment_parent_id >0){
						$p_title = \backend\models\DwCommentExp::findCommentContent($model->comment_parent_id);
						$title = '<blockquote><p class="text-left">主评：'.$p_title. '</p></blockquote>';
	
					}else{
                        $title = '';
                    }
				 $support = \backend\models\DwComment::get_comment_support_redis($model->id);
                    	
                    $report= \backend\models\DwComment::get_comment_report_redis($model->id);
                    $cai = \backend\models\DwComment::get_comment_dislike_redis($model->id);
				return  $title.'<p style="text-indent: 10pt;text-align:left;">
						 <span data-placement="bottom" data-original-title="被举报【'.$report.'】次" data-toggle="tooltip" class="label label-danger" style="text-align:left">'.$report.'</span>
                         &nbsp;<span data-placement="bottom" data-original-title="顶了【'.$support.'】次" data-toggle="tooltip" class="label label-success">'.$support.'</span>
                         &nbsp;<span class="label label-info" data-toggle="tooltip" data-original-title="踩了【'.$cai.'】次" data-placement="bottom">'.$cai.'</span> 
                         &nbsp;<span>'.$msg.'：'.$model->comment_title.'</span></p>
					     <span style="float:left;text-indent: 10pt;text-align:left;" data-toggle="tooltip" data-original-title="用户UID:'.$model->comment_user_id.', IP:'.$model->comment_ip.' ,来源：中国广东" data-placement="top">'.$model->commentExp->comment_content.'</span>';
			},
			],
			[
			'attribute' => 'comment_channel_area',
			'label'=>Yii::t('backend','Account Group'),
			'contentOptions'=>['style'=>'width: 8%; text-align: center; vertical-align: middle;'],
			'content'=>
			function($model){
					$msg = Yii::t('backend','No Account');
					$account = \backend\models\DwAuthAccount::getAccountById($model->comment_channel_area);
					if( isset($account['name'])){
						return $account['name'];
					}else{
						return $msg;
					}
					},
			],
			[
			'attribute' => 'comment_user_type',
			'label'=>Yii::t('backend','Form Types'),
			'contentOptions'=>['style'=>'width: 8%; text-align: center; vertical-align: middle;'],
			'content'=>
			function($model){
				return \backend\models\DwFourmCategory::get_category($model->comment_user_type);
			},
			],
			[
			'attribute' => 'comment_parent_id',
			'label'=>Yii::t('backend','Types'),
			'contentOptions'=>['style'=>'width: 5%; text-align: center; vertical-align: middle;'],
			'content'=>
				function($model){
					$reply= Yii::t('backend','Reply');
					$comments= Yii::t('backend','Comments');
					if($model->comment_parent_id >0){
						return $reply;
					}else{
						return $comments;
					}
				},
			],
			[
			'attribute' => 'comment_status',
			'label'=>Yii::t('backend','States'),
			'contentOptions'=>['style'=>'width: 5%; text-align: center; vertical-align: middle;'],
			'content'=>
				function($model){
					$msg = Yii::t('backend','Unknown');
					// 审核状态
					$operation_stat =\backend\models\DwComment::comment_stat($model->comment_status);
					if( $model->comment_status == 2){
						return '<span class="label bg-yellow" >'.$operation_stat.'</span>' ;
					}elseif( $model->comment_status == 4){
						return '<span class="label bg-aqua" >'.$operation_stat.'</span>' ;
					}elseif( $model->comment_status == 5){
						return '<span class="label bg-olive" >'.$operation_stat.'</span>' ;
					}elseif( $model->comment_status == 6){
						return '<span class="label bg-maroon" >'.$operation_stat.'</span>' ;
					}else {
						return $msg;
					}
				},
			],
			[
			'attribute' => 'comment_created_at',
			'label'=>Yii::t('backend','Times'),
			'contentOptions'=>['style'=>'width: 6%; text-align: center; vertical-align: middle;'],
			'value'=>
			function($model){
				return  date('y/m/d H:i',$model->comment_created_at);   //主要通过此种方式实现
			},
			],
            ['class' => 'yii\grid\ActionColumn',
			'header'=>Yii::t('backend','Operation'),
			'contentOptions'=>['style'=>'width: 7%; text-align: center; vertical-align: middle;'],
			'template' => '{delete} {view} {lock}',
			'buttons' => [
				'view' => function ($url, $model) {
					$view = Yii::t('backend','Views');
					return '<a data-pjax="0" aria-label="'.$view.'" title="'.$view.'" href="/admin/index.php?r=comment%2Fview&amp;id='.$model->id.'&from=approval"   class="btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span></a>';
				},
// 				'lock' => function ($url, $model) {
// 					return '<a data-params="{&quot;id&quot;:1}" data-method="post" title="封禁用户" href="/admin/index.php?r=user%2Fban"><i class="fa fa-ban"></i></a>';
// 				},
				'delete' => function ($url, $model) {
					$suredelete = Yii::t('backend','Sure Delete');
					$delete = Yii::t('backend','Deletes');
					return '<a data-pjax="0" data-confirm="'.$suredelete.'" data-method="post"   aria-label="'.$delete.'" title="'.$delete.'" href="/admin/index.php?r=comment%2Fcomment_del&amp;delid='.$model->id.'" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-trash"></span></a>';
				},
				],
			'urlCreator' => function ($action, $model, $key, $index) {
				if ($action === 'delete') {
					return ['sensitive_del', 'id' => $model->id];
				}
			}],
        ],
    ]); ?>

							

						</div>
						<!-- /.box-body -->
		<div class="overlay" style="display: none;" id="show_loading">
				<i class="fa fa-refresh fa-spin"></i>
			</div>
					</div>
					<!-- /.box -->
					
				</div>
			</div>
		</div>

		<!-- /.box -->
	</div>
</div>
<?php
AdminAsset::register($this);
$js = <<<JS



$(function() {
	var comment_channel_area = '$comment_channel_area_id';
		$('#dwcommentsearch-comment_channel_area').val(comment_channel_area);
});

JS;
$this->registerJs($js);
?>
<script type="text/javascript">
if(issearch==1){
    $('.box-default-view').removeClass("collapsed-box");
    $('.box-body-view').css('display','block');
}
function reason_show(){
	 var batch = $('#batch').val();
	 if(!isNaN(batch)){
		 
		 $('#batch_reason').show();
	 }else{
		 $('#batch_reason').hide();
		}
}
function batch_operation(){
	obj = document.getElementsByName("selection[]");
	check_val = [];
	check_url = [];
	check_uid = [];
	check_account_id = [];
    for(k in obj){
    	if(obj[k].checked){
            check_val.push(obj[k].value);
            check_uid.push(obj[k].getAttribute("uid"));
            check_url.push(obj[k].getAttribute("data"));
            check_account_id.push(obj[k].getAttribute("account_id"));
        }
        
    }
    if( check_val == ''){
		alert(selectdata);
		return false;
    }
    var batch2 = document.getElementById('batch');
    var batch = $('#batch').val();
    var batch_reason = $('#batch_reason').val();
//     if(!isNaN(batch)){
		// 审核操作
//     	if(batch_reason == ''){
//         	alert(chosereson);
//     		return false;
//         }
//     }
    var text =batch2.options[batch2.selectedIndex].text;//获取文本

    var suredel = surebulk+text+thisitem;
    if(confirm(suredel)){
        $.ajax({
            type: "POST",
            url: "index.php?r=comment/comment_batch",
            data: {
                'id': check_val,
                'type': batch,
                'reason':batch_reason,
                'check_url':check_url,
                'check_uid':check_uid,
                'check_account_id':check_account_id
            },
            beforeSend: commentloading_list,
            success: commentresponse_batch
        })
    }
}

function commentloading_list(){
	$('#show_loading').show();
}
function commentresponse_batch(data){
	$('#show_loading').hide();
	data = eval('(' + data + ')');
	if(data.flg == true ){
		alert(data.data);
		location.reload(); 
	}else{
		alert(data.data);
	}
}

</script>

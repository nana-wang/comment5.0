<?php
use yii\helpers\Html;
use yii\grid\GridView;
use Qiniu\json_decode;
use yii\helpers\Url;
use yii\base\Widget;

/* @var $this yii\web\View */
/* @var $model common\models\Comment */

// $this->title = $model->id;
$this->title = Yii::t('backend','Comment Info');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('backend','Comments'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $chosegroup = Yii::t('backend','Chose Account Group');?>
<?php $newadd = Yii::t('backend','Newadd');?>
<?php $surestop = Yii::t('backend','Sure Stop');?>
<?php $editdo = Yii::t('backend','Editdo');?>
<?php $oneselect = Yii::t('backend','One Select');?>
<?php $suredelete = Yii::t('backend','Sure Delete');?>
<?php $deletes = Yii::t('backend','Deletes');?>
<?php $tagsort = Yii::t('backend','Tag Sort');?>
<?php $mostfive = Yii::t('backend','Most Five');?>
<?php $choseformtype = Yii::t('backend','Form Types Empty');?>
<script>
var chosegroup = '<?php echo $chosegroup;?>';
var newadd = '<?php echo $newadd;?>';
var surestop = '<?php echo $surestop;?>'; //您确定要停用此项吗
var editdo = '<?php echo $editdo;?>'; //编辑
var oneselect = '<?php echo $oneselect;?>'; //一次只能选择一项
var suredelete = '<?php echo $suredelete;?>'; //确认删除
var deletes = '<?php echo $deletes;?>'; //删除
var tagsort = '<?php echo $tagsort;?>';
var mostfive = '<?php echo $mostfive;?>';
var choseformtype = '<?php echo $choseformtype;?>'; //请选择表单类型
</script>
<div class="comment-view">
	<div class="row">
		<div class="col-sm-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><?=Yii::t('backend','Comment Form');?></h3>
					<span class="label label-primary pull-right"></span>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="container  col-md-12">
						<div class="row clearfix">
							<div class="col-md-12 column">
								<div class="tabbable" id="tabs-689681">
									<ul class="nav nav-tabs">
										<li ><a href="<?php echo Url::toRoute(['comment/fourm', 'web_type' => 'item'])?>" ><?=Yii::t('backend','Form Set');?></a></li>
										<li ><a href="<?php echo Url::toRoute(['comment/fourm', 'web_type' => 'category'])?>" ><?=Yii::t('backend','Form Types');?></a></li>
										<!--  <li ><a href="<?php echo Url::toRoute(['comment/fourm', 'web_type' => 'area'])?>" >使用区域</a></li>-->
										<li class="active"><a href="<?php echo Url::toRoute(['comment/fourm', 'web_type' => 'makeing'])?>" ><?=Yii::t('backend','Generation Manage');?></a></li>
									</ul>
									<div class="tab-content">
										
<!-- 表单生成 -->
										<div class="tab-pane active" id="panel-9792324">
	<p></p>										
	<div class="alert alert-success alert-dismissible"  id='makeing_ok' style="display:none;">
		<button type="button" class="close" data-dismiss="alert"
			aria-hidden="true">×</button>
		<h4><i class="icon fa fa-check"></i> ok !</h4>
		<?=Yii::t('backend','Operation Complete');?>
	</div>
	<div class="alert alert-warning alert-dismissible" id='makeing_error'  style="display:none;">
		<button type="button" class="close" data-dismiss="alert"  
			aria-hidden="true">×</button>
		<h4><i class="icon fa fa-warning"></i> error !</h4>
		<?=Yii::t('backend','Operation Fail');?>
	</div>
											
<p>										
<blockquote>
	<p><?=Yii::t('backend','Search Operation');?><?=Yii::t('backend','Explain');?></p>
	<small><cite title="Source Title"><?=Yii::t('backend','Jurisdiction');?></cite></small>
	<small><cite title="Source Title"><?=Yii::t('backend','Channel');?></cite></small>
	<small><cite title="Source Title"><?=Yii::t('backend','Authentication');?></cite></small>
</blockquote>
</p>
<!-- 操作区域 -->
<div class='row'>
		<div class="col-md-12">
			<div class="box box-default">
				<div class="box-header with-border">
					<h3 class="box-title"><?=Yii::t('backend','Operatingarea');?></h3>
				</div>
				<div class="box-body">
					<form role="form">
						<!-- text input -->

						<div class="form-group col-md-12">
							<label><?=Yii::t('backend','The Account');?><b style="color: red">&nbsp;*&nbsp;</b></label> <select class="form-control" onChange="account_change_makeing();" id='fourm_item_account'>
								<option value="">--<?=Yii::t('backend','Please Select');?>--</option>
								<?= backend\widgets\MenuLeft::widget();?>
								</select>
						</div>
						<div class="form-group col-md-12">
							<label><?=Yii::t('backend','Form Types');?><b style="color: red">&nbsp;*&nbsp;</b></label> <select class="form-control" id='fourm_category'>
								<option value="">--<?=Yii::t('backend','Please Select');?>--</option>
		<?php 
// 		if( !empty($category_list)){
// 			foreach ($category_list as $c_k =>$c_v){
// 				echo '<option value="'.$c_v['id'].'">'.$c_v['fourm_title'].'</option>';
// 			}
// 		}
		?>						
							</select>
						</div>
						

						<!-- textarea -->
						<div class="form-group col-md-12">
							<label><?=Yii::t('backend','Generate Js');?><b style="color: red">&nbsp;*&nbsp;</b></label>
							<textarea class="form-control" rows="8"
								placeholder="<?=Yii::t('backend','Replycom');?>"><script src="http://demo.dwnews.com/js/api_common.js"></script>
<script src="http://comment.login.dwnews.com/js/messenger.js"></script>
<link rel="stylesheet" type="text/css" href="http://demo.dwnews.com/dist/css/wangEditor.min.css">
<script type="text/javascript" src="http://demo.dwnews.com/dist/js/wangEditor.js"></script>
<div id="commentArea"> </div>
<div class="pl_box">
  <ul id="commentList">
  </ul>
</div>
<div style="text-align:center;padding-bottom:50px;display:none;" id='morepage'>
  <div id='pagemorediv'><a href="javascript:void(0)" id='pagemore'>加载更多</a></div>
  <div id='loadingimg' style="display:none;"><img src="images/loading.gif" ></div>
</div>
<script type="text/javascript">
//由后端接口提供
var access_token = '后端接口生成的access_token';	
//评论系统5.0调用
JSsdk.init({
    CommentApiUrl: 'http://comment.api.dwnews.com/v1/frontend?access_token=' + access_token,
    CommentUrl: '9e59d215aba68abc16092266b87a272b',
    CommentTitle: '习近平主席亚洲之行的6个感人细节',
    CommentUserID: 0,
    CommentNumber: 10,
    CommentContainerClass: 'box_pl',
    CommentDivId: 'commentArea',
    CommentList: 'commentList',
    CommentChannelArea: '1',
    CommentFormCategoryId: '36'
});
</script></textarea>
						</div>
						<div class="form-group col-md-12">
							<label><?=Yii::t('backend','Css Set');?><b style="color: red">&nbsp;*&nbsp;</b></label>
							<textarea class="form-control" rows="8"
								placeholder="<?=Yii::t('backend','Replycom');?>">123123123213131</textarea>
						</div>
						<div class="form-group col-md-12">
							<label><?=Yii::t('backend','Template');?><b style="color: red">&nbsp;*&nbsp;</b></label>
							<textarea class="form-control" rows="8"
								placeholder="<?=Yii::t('backend','Replycom');?>">23131312321313</textarea>
						</div>
							<div class="form-group col-md-3">
								<button type="button" id='makeing_button' class="btn btn-block btn-success"><?=Yii::t('backend','Generate');?></button>
							</div>
							<div class="form-group col-md-3">
								<button type="makeing_clear" class="btn btn-block btn-danger"><?=Yii::t('backend','Empty');?></button>
							</div>
				
				</div>
				<!-- /.box-body -->
				<!-- Loading (remove the following to stop the loading)-->
				<div class="overlay" style="display: none;" id="make_loading">
					<i class="fa fa-refresh fa-spin"></i>
				</div>
				<!-- end loading -->
			</div>
			<!-- /.box -->
		</div>
		</div>
<!-- 操作区域 end-->

										</div><!--/panel-9792324-->
									</div>    <!--/tab-content-->
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->
		</div>
		<!-- /.col -->
	</div>
</div>
<?php 
use backend\assets\AppAsset;
AppAsset::addScript($this,'@web/static/js/comment.js');
?>

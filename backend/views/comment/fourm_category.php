<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use Qiniu\json_decode;
use yii\helpers\Url;
use mdm\admin\AdminAsset;
use yii\helpers\Json;
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
<?php $choseformtype = Yii::t('backend','Chose Form Type');?>

<?php $formtypenamenoempty = Yii::t('backend','Form Type Name No Empty');?>
<?php $formtypenoempty = Yii::t('backend','Form Type No Empty');?>
<?php $pleaseselectthesort = Yii::t('backend','Please Select The Sort');?>
<?php $pleaseselectthemethod = Yii::t('backend','Please Select The Method');?>
<?php $pleaseselectalicense = Yii::t('backend','Please Select A License');?>
<?php $pleaseselectcomments = Yii::t('backend','Please Select Comments');?>
<?php $pleaseselectisreply = Yii::t('backend','Please Select Is Reply');?>
<?php $pleaseselectisanonymous = Yii::t('backend','Please Select Is Anonymous');?>
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

var formtypenamenoempty = '<?php echo $formtypenamenoempty;?>'; //您确定要停用此项吗
var formtypenoempty = '<?php echo $formtypenoempty;?>'; //编辑
var pleaseselectthesort = '<?php echo $pleaseselectthesort;?>'; //一次只能选择一项
var pleaseselectthemethod = '<?php echo $pleaseselectthemethod;?>'; //确认删除
var pleaseselectalicense = '<?php echo $pleaseselectalicense;?>'; //删除
var pleaseselectcomments = '<?php echo $pleaseselectcomments;?>';
var pleaseselectisreply = '<?php echo $pleaseselectisreply;?>';
var pleaseselectisanonymous = '<?php echo $pleaseselectisanonymous;?>'; //请选择表单类型

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
					<div class="container col-md-12">
						<div class="row clearfix">
							<div class="col-md-12 column">
								<div class="tabbable" id="tabs-689681">
									<ul class="nav nav-tabs">
										<li ><a href="<?php echo Url::toRoute(['comment/fourm', 'web_type' => 'item'])?>" ><?=Yii::t('backend','Form Set');?></a></li>
										<li class="active"><a href="<?php echo Url::toRoute(['comment/fourm', 'web_type' => 'category'])?>" ><?=Yii::t('backend','Form Types');?></a></li>
										<!--  <li><a href="<?php echo Url::toRoute(['comment/fourm', 'web_type' => 'area'])?>" >使用区域</a></li>-->
										<li><a href="<?php echo Url::toRoute(['comment/fourm', 'web_type' => 'makeing'])?>" ><?=Yii::t('backend','Generation Manage');?></a></li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="panel-583733">
											
											<p>		</p>
		<p>									
	<div class="alert alert-success alert-dismissible" id='formtype_ok' style="display:none;">
		<button type="button" class="close" data-dismiss="alert"
			aria-hidden="true">×</button>
		<h4>
			<i class="icon fa fa-check"></i> ok !
		</h4>
		<?=Yii::t('backend','Operation Complete');?>
	</div>
	
	<div class="alert alert-warning alert-dismissible" id='formtype_error' style="display:none;">
		<button type="button" class="close" data-dismiss="alert"
			aria-hidden="true">×</button>
		<h4>
			<i class="icon fa fa-warning"></i> error !
		</h4>
		<?=Yii::t('backend','Operation Fail');?>
	</div>
	</p>
											<p>
											
<!-- 表单类型新增 -->										
	<div class="box box-default box-default-edit collapsed-box">
	<div class="overlay" style="display: none;" id="formtype_add_loading">
		<i class="fa fa-refresh fa-spin"></i>
	</div>
		<div class="box-header with-border">
			<h3 class="box-title"><?=Yii::t('backend','Newadd');?>/<?=Yii::t('backend','Editdo');?></h3>
			<div class="box-tools pull-right">
				<button class="btn btn-box-tool" data-widget="collapse">
					<i class="fa fa-plus"></i>
				</button>
			</div>
			<!-- /.box-tools -->
		</div>
		<!-- /.box-header -->
		<div class="box-body box-body-edit">
		  <div class="form-group">
				<label><?=Yii::t('backend','Names');?><b style="color: red">&nbsp;*&nbsp;</b></label> 
				<input type="text" name='fourm_title'	class="form-control" value=""	placeholder="<?=Yii::t('backend','Form Type Name');?>">
			</div>
			<div class="form-group">
				<label><?=Yii::t('backend','Account');?><b style="color: red">&nbsp;*&nbsp;</b></label> 
				<select class=" form-control" onChange="account_change('');" name="fourm_item_account" id="fourm_item_account">
					<option value="">--<?=Yii::t('backend','Please Select');?>--</option>
					<?= backend\widgets\MenuLeft::widget();?>
				</select>
						
			</div>
			<div class="checkbox">
				<div class="assignment-index">
	<div class="row">
		<div class="form-group  col-md-5">
			<select id="list-avaliable" multiple="" size="10" class="form-control">
				</select>
		</div>
		<div class="form-group col-md-1 ">
			<div class="" style="margin-top: 10px;">
			    <a href="javascript:;" onclick="account_item_yidong('list-avaliable','list-assigned');" id="btn-assign" class="btn btn-block btn-info btn-flat">&gt;&gt;</a>
				<a href="javascript:;" onclick="account_item_yidong('list-assigned','list-avaliable');" id="btn-revoke" class="btn btn-block btn-danger btn-flat">&lt;&lt;</a>
			    <a href="javascript:;" onclick="account_item_up();" id="btn-up" class="btn btn-block btn-info btn-flat">&#8593;</a>
				<a href="javascript:;" onclick="account_item_down();" id="btn-down" class="btn btn-block btn-danger btn-flat">&#8595;</a>
			
			</div>
		</div>
		<div class="form-group  col-md-5">
			<select id="list-assigned" multiple="" size="10" class="form-control">
			</select>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div id="presmission_data"></div>
			<!-- /.box -->
		</div>
	</div>
</div>
			</div>
			<hr>
			<div class="form-group col-md-3">
				<label><?=Yii::t('backend','Sorting Method');?><b style="color: red">&nbsp;*&nbsp;</b></label> <select class="form-control" name='fourm_order'>
					<option value="">--<?=Yii::t('backend','Please Select');?>--</option>
					<option value="0"><?=Yii::t('backend','Latest First');?></option>
					<option value="1"><?=Yii::t('backend','Top Priority');?></option>
				</select>
			</div>
			<div class="form-group col-md-3">
				<label><?=Yii::t('backend','Release Method');?><b style="color: red">&nbsp;*&nbsp;</b></label> <select class="form-control" name='fourm_meth'>
					<option value="">--<?=Yii::t('backend','Please Select');?>--</option>
					<option value="0"><?=Yii::t('backend','Autodo');?></option>
					<option value="1"><?=Yii::t('backend','Need Approval');?></option>
					
				</select>
			</div>
			<div class="form-group col-md-3">
				<label><?=Yii::t('backend','Modify Permission');?><b style="color: red">&nbsp;*&nbsp;</b></label> <select class="form-control" name='fourm_pess'>
					<option value="">--<?=Yii::t('backend','Please Select');?>--</option>
					<option value="0"><?=Yii::t('backend','Unmodifyly');?></option>
					<option value="1"><?=Yii::t('backend','Modifyly');?></option>
					
				</select>
			</div>
			<div class="form-group col-md-3">
				<label><?=Yii::t('backend','User Comment Set');?><b style="color: red">&nbsp;*&nbsp;</b></label> <select class="form-control" name='fourm_number'>
					<option value="">--<?=Yii::t('backend','Please Select');?>--</option>
					<option value="0"><?=Yii::t('backend','Moremore');?></option>
					<option value="1"><?=Yii::t('backend','Onemore');?></option>
				</select>
			</div>
			<div class="form-group col-md-3">
				<label><?=Yii::t('backend','Replyly');?><b style="color: red">&nbsp;*&nbsp;</b></label> <select class="form-control" name='fourm_reply'>
					<option value="">--<?=Yii::t('backend','Please Select');?>--</option>
					<option value="0"><?=Yii::t('backend','Yesdo');?></option>
					<option value="1"><?=Yii::t('backend','Nodo');?></option>
				</select>
			</div>
			<div class="form-group col-md-3">
				<label><?=Yii::t('backend','Anonymous Comment');?><b style="color: red">&nbsp;*&nbsp;</b></label> 
				<select class="form-control" name='fourm_anonymous'>
					<option value="">--<?=Yii::t('backend','Please Select');?>--</option>
					<option value="0"><?=Yii::t('backend','Yesdo');?></option>
					<option value="1"><?=Yii::t('backend','Nodo');?></option>
				</select>
			</div>
			<div class="form-group col-md-3">
				<label>&nbsp;</label>
				<input type="hidden" name='category_id' value=''>
				<button type="button" id="formtype_button_add" class="btn btn-block btn-success "><?=Yii::t('backend','Newadd');?></button>
			</div>
			<div class="form-group col-md-3">
				<label>&nbsp;</label>
				<button type="button" onclick="category_clear('list-assigned','list-avaliable');" id="formtype_button_clear" class="btn btn-block label-danger"><?=Yii::t('backend','Empty');?></button>
			</div>
		</div>
		<!-- /.box-body -->
	</div>
	<!-- 表单类型新增end -->
											<!-- /.box -->
											<div class="box box-default">
												<div class="box-header with-border">
													<h3 class="box-title"><?=Yii::t('backend','List Infor');?></h3>
												</div>
												<div class="box-body">
	<?= GridView::widget([
        'dataProvider' => $dataProvider_category,
    	'emptyText' =>Yii::t('backend','No Data'),
        'columns' => [
		    'id',
			'fourm_title',
			[
			'attribute' => 'fourm_idtype_id',
			'label'=>Yii::t('backend','Form Set Item'),
			'content'=>
			function($model){
					return \backend\models\DwFourmCategoryItem::get_fourm_item_name($model->fourm_idtype_id);
			},
			],
			[
			'attribute' => 'fourm_order',
			'label'=>Yii::t('backend','Sort'),
			'content'=>
				function($model){
					$hot = Yii::t('backend','Hot');
					$newest = Yii::t('backend','Newest');
					$nodefined = Yii::t('backend','Nodefined');
					if( $model->fourm_order == 1){
						return 	'<span class="label label-success">'.$hot.'</span>';
					}elseif($model->fourm_order == 0){
						return '<span class="label bg-yellow">'.$newest.'</span>';
					}else{
						return '<span class="label bg-navy">'.$nodefined.'</span>';
					}
				},
			],
			[
			'attribute' => 'fourm_meth',
			'label'=>Yii::t('backend','Release Method'),
			'content'=>
				function($model){
					$needapproval = Yii::t('backend','Need Approval');
					$autodo = Yii::t('backend','Autodo');
					$nodefined = Yii::t('backend','Nodefined');
					if( $model->fourm_meth == 1){
						return 	'<span class="label label-success">'.$needapproval.'</span>';
					}elseif($model->fourm_meth == 0){
						return '<span class="label bg-yellow">'.$autodo.'</span>';
					}else{
						return '<span class="label bg-navy">'.$nodefined.'</span>';
					}
				},
			],
			[
			'attribute' => 'fourm_pess',
			'label'=>Yii::t('backend','Modify Permission'),
			'content'=>
			function($model){
				$modifyly = Yii::t('backend','Modifyly');
				$unmodifyly = Yii::t('backend','Unmodifyly');
				$nodefined = Yii::t('backend','Nodefined');
				if( $model->fourm_pess == 1){
					return 	'<span class="label label-success">'.$modifyly.'</span>';
				}elseif($model->fourm_pess == 0){
					return '<span class="label bg-yellow">'.$unmodifyly.'</span>';
				}else{
					return '<span class="label bg-navy">'.$nodefined.'</span>';
				}
			},
			],
			[
			'attribute' => 'fourm_number',
			'label'=>Yii::t('backend','Comment Num'),
			'content'=>
				function($model){
					$onemore = Yii::t('backend','Onemore');
					$moremore = Yii::t('backend','Moremore');
					$nodefined = Yii::t('backend','Nodefined');
					if( $model->fourm_number == 1){
						return 	'<span class="label label-success">'.$onemore.'</span>';
					}elseif($model->fourm_number == 0){
						return '<span class="label bg-yellow">'.$moremore.'</span>';
					}else{
						return '<span class="label bg-navy">'.$nodefined.'</span>';
					}
				}
			],
			[
			'attribute' => 'fourm_reply',
			'label'=>Yii::t('backend','Reply'),
			'content'=>
				function($model){
					$noreply = Yii::t('backend','Noreply');
					$replydo = Yii::t('backend','YesReplyly');
					$nodefined = Yii::t('backend','Nodefined');
					if( $model->fourm_reply == 1){
						return 	'<span class="label label-success">'.$noreply.'</span>';
					}elseif($model->fourm_reply == 0){
						return '<span class="label bg-yellow">'.$replydo.'</span>';
					}else{
						return '<span class="label bg-navy">'.$nodefined.'</span>';
					}
				},
			],
			[
			'attribute' => 'fourm_anonymous',
			'label'=>Yii::t('backend','Anonymous'),
			'content'=>
				function($model){
					$noanonymous = Yii::t('backend','No Anonymous');
					$anonymously = Yii::t('backend','Anonymously');
					$nodefined = Yii::t('backend','Nodefined');
					if( $model->fourm_anonymous == 1){
						return 	'<span class="label label-success">'.$noanonymous.'</span>';
					}elseif($model->fourm_anonymous == 0){
						return '<span class="label bg-yellow">'.$anonymously.'</span>';
					}else{
						return '<span class="label bg-navy">'.$nodefined.'</span>';
					}
				},
			],
			[
			'attribute' => 'fourm_account',
			'contentOptions'=>['style'=>'width: 10%;'],
			'label'=>Yii::t('backend','The Account'),
			'content'=>
			function($model){
				$noaccount = Yii::t('backend','No Account');
				$account = \backend\models\DwAuthAccount::getAccountById($model->fourm_account);
				if( isset($account['name'])){
					return $account['name'];
				}else{
					return $noaccount;
				}
			},
			],
			[
			'attribute' => 'fourm_dateline',
			'label'=>Yii::t('backend','Times'),
			'value'=>
				function($model){
					return  date('Y/m/d',$model->fourm_dateline);   //主要通过此种方式实现
				},
			],
			//'fourm_actions_uid',
			[
				'attribute' => 'fourm_actions_uid',
				'label'=>Yii::t('backend','Operator'),
				'content'=>
					function($model){
						$msg = Yii::t('backend','No Account');
						$sensitive_operator = \backend\models\DwUser::get_user_redis_name($model->fourm_actions_uid);
						return $sensitive_operator;
					},
			],
            ['class' => 'yii\grid\ActionColumn',
			'header'=>Yii::t('backend','Operation'),
			'template' => '{delete} {view}',
			'buttons' => [
				'view' => function ($url, $model) {
					$view = Yii::t('backend','Views');
					return '<a data-pjax="0" aria-label="'.$view.'" title="'.$view.'" href="javascript:;" onclick="formTypeEdit_view('.$model->id.')" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span></a>';
				},
				'delete' => function ($url, $model) {
					$delete = Yii::t('backend','Deletes');
					Yii::t('backend','Search Operation');
					return '<a data-pjax="0"  onclick="category_del('.$model->id.')" aria-label="'.$delete.'" title="'.$delete.'" href="javascript:;" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-trash"></span></a>';
				},
				],
			],
        ],
    ]); ?>
												</div>
												<!-- /.box-body -->
												<!-- Loading (remove the following to stop the loading)-->
												<div class="overlay" style="display: none;" id="formtype_loading">
													<i class="fa fa-refresh fa-spin"></i>
												</div>
												<!-- end loading -->
											</div>
											<!-- /.box -->
											<div class="form-group col-md-12"></div>
										</div>
								
										
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


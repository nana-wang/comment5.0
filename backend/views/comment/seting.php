<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use Qiniu\json_decode;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model common\models\Comment */

// $this->title = $model->id;
$this->title = Yii::t('backend','Comment Info'); //评论详细信息
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('backend','Comments'), //评论
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>

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
					<div class="container">
						<div class="row clearfix">
							<div class="col-md-12 column">
								<div class="tabbable" id="tabs-689681">
									<ul class="nav nav-tabs">
										<li class="active"><a href="#panel-979239" data-toggle="tab"><?=Yii::t('backend','Form Set');?></a></li>
										<li ><a href="#panel-583733" data-toggle="tab"><?=Yii::t('backend','Form Types');?></a></li>
										<li><a href="#panel-9792391" data-toggle="tab"><?=Yii::t('backend','Use Area');?></a></li>
										<li><a href="#panel-9792324" data-toggle="tab"><?=Yii::t('backend','Generation Manage');?></a></li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane" id="panel-583733">
											
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
	<div class="box box-default collapsed-box">
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
		<div class="box-body">
			<div class="form-group">
							
				<input type="text" name='fourm_title'	class="form-control" value=""	placeholder="<?=Yii::t('backend','Form Type Name');?>...">
			</div>
			<div class="checkbox">
				<?php 
					if( !empty($category_item)){
						foreach ($category_item as $k_category_item => $v_category_item){
							echo '<label> <input type="checkbox" name="fourm_idtype_id[]" value="'.$v_category_item['id'].'" >'.$v_category_item['fourm_item_title'].'</label>&nbsp;&nbsp;';
						}
					}
				?>
			</div>
			<hr>
			<div class="form-group col-md-3">
				<label><?=Yii::t('backend','Sorting Method');?></label> <select class="form-control" name='fourm_order'>
					<option value="">--<?=Yii::t('backend','Please Select');?>--</option>
					<option value="0"><?=Yii::t('backend','Latest First');?></option>
					<option value="1"><?=Yii::t('backend','Top Priority');?></option>
				</select>
			</div>
			<div class="form-group col-md-3">
				<label><?=Yii::t('backend','Release Method');?></label> <select class="form-control" name='fourm_meth'>
					<option value="">--<?=Yii::t('backend','Please Select');?>--</option>
					<option value="1"><?=Yii::t('backend','Need Approval');?></option>
					<option value="0"><?=Yii::t('backend','Autodo');?></option>
				</select>
			</div>
			<div class="form-group col-md-3">
				<label><?=Yii::t('backend','Modify Permission');?></label> <select class="form-control" name='fourm_pess'>
					<option value="">--<?=Yii::t('backend','Please Select');?>--</option>
					<option value="1"><?=Yii::t('backend','Modifyly');?></option>
					<option value="0"><?=Yii::t('backend','Unmodifyly');?></option>
				</select>
			</div>
			<div class="form-group col-md-3">
				<label><?=Yii::t('backend','User Comment Set');?></label> <select class="form-control" name='fourm_number'>
					<option value="">--<?=Yii::t('backend','Please Select');?>--</option>
					<option value="1"><?=Yii::t('backend','Onemore');?></option>
					<option value="0"><?=Yii::t('backend','Moremore');?></option>
				</select>
			</div>
			<div class="form-group col-md-3">
				<label><?=Yii::t('backend','Replyly');?></label> <select class="form-control" name='fourm_reply'>
					<option value="">--<?=Yii::t('backend','Please Select');?>--</option>
					<option value="0"><?=Yii::t('backend','Yesdo');?></option>
					<option value="1"><?=Yii::t('backend','Nodo');?></option>
				</select>
			</div>
			<div class="form-group col-md-3">
				<label><?=Yii::t('backend','Anonymous Comment');?></label> 
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
				<button type="button" onclick="category_clear();" id="formtype_button_clear" class="btn btn-block label-danger"><?=Yii::t('backend','Empty');?></button>
			</div>
		</div>
		<!-- /.box-body -->
	</div>
	<!-- 表单类型新增end -->
	<!-- 表单类型编辑 -->
	<!-- 										
	<div class="box box-default collapsed-box">
	<div class="overlay" style="display: none;" id="formtype_edit_loading">
		<i class="fa fa-refresh fa-spin"></i>
	</div>
		<div class="box-header with-border">
			<h3 class="box-title">编辑</h3>
			<div class="box-tools pull-right">
				<button class="btn btn-box-tool" data-widget="collapse">
					<i class="fa fa-plus"></i>
				</button>
			</div>
		</div>
		<div class="box-body">
			<div class="form-group">
				<label>表单类型名称</label> <input type="text"
					class="form-control" value="表单类型名称"
					placeholder="表单类型名称 ...">
			</div>
			<div class="checkbox">
				<label> <input type="checkbox" name="optionsRadios"
					id="optionsRadios1" value="option1" checked=""> 文字
				</label>&nbsp;&nbsp;
				<label> <input type="checkbox" name="optionsRadios"
					id="optionsRadios2" value="option2"> 数字
				</label>&nbsp;&nbsp;
				<label> <input type="checkbox" name="optionsRadios"
					id="optionsRadios2" value="option2"> 数字
				</label>
			</div>
			
			<hr>
			<div class="form-group col-md-3">
				<label> 排序方法</label> <select class="form-control">
					<option>--请选择--</option>
					<option value="最新优先">最新优先</option>
					<option value="热门优先">热门优先</option>
				</select>
			</div>
			<div class="form-group col-md-3">
				<label> 发布方法</label> <select class="form-control">
					<option>--请选择--</option>
					<option value="需审批">需审批</option>
					<option value="自动">自动</option>
				</select>
			</div>
			<div class="form-group col-md-3">
				<label> 修改权限</label> <select class="form-control">
					<option>--请选择--</option>
					<option value="可修改">可修改</option>
					<option value="不可修改">不可修改</option>
				</select>
			</div>
			<div class="form-group col-md-3">
				<label> 用户评论数设定</label> <select class="form-control">
					<option>--请选择--</option>
					<option value="一人一条">一人一条</option>
					<option value="一人多条">一人多条</option>
				</select>
			</div>
			<div class="form-group col-md-3">
				<label> 是否可回复</label> <select class="form-control">
					<option>--请选择--</option>
					<option value="是">是</option>
					<option value="否">否</option>
				</select>
			</div>
			<div class="form-group col-md-3">
				<label> 是否匿名评论</label> <select class="form-control">
					<option>--请选择--</option>
					<option value="是">是</option>
					<option value="否">否</option>
				</select>
			</div>
			<div class="form-group col-md-3">
				<label>&nbsp;</label>
				<button type="button" id="formtype_button_edit" class="btn btn-block btn-success ">增加</button>
			</div>
		</div>
	</div>
	-->
<!-- 表单类型编辑end -->											
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
			'label'=>Yii::t('backend','Form Types'),
			'content'=>
			function($model){
					return \backend\models\DwFourmCategoryItem::get_fourm_item_idtype($model->fourm_idtype_id);
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
					if( $model->fourm_order == 1){
						return 	'<span class="label label-success">'.$needapproval.'</span>';
					}elseif($model->fourm_order == 0){
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
					$replyly = Yii::t('backend','Replyly');
					$nodefined = Yii::t('backend','Nodefined');
					if( $model->fourm_reply == 1){
						return 	'<span class="label label-success">'.$noreply.'</span>';
					}elseif($model->fourm_reply == 0){
						return '<span class="label bg-yellow">'.$replyly.'</span>';
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
			'attribute' => 'fourm_dateline',
			'label'=>Yii::t('backend','Times'),
			'value'=>
				function($model){
					return  date('Y/m/d H:i',$model->fourm_dateline);   //主要通过此种方式实现
				},
			],
			'fourm_actions_uid',
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
<!-- /表单设定 -->
										<div class="tab-pane active" id="panel-979239">
	<p>
	<div class="alert alert-success alert-dismissible" id="ok" style="display: none;">
		<button type="button" class="close" data-dismiss="alert"
			aria-hidden="true">×</button>
		<h4>
			<i class="icon fa fa-check"></i> ok !
		</h4>
		<?=Yii::t('backend','Operation Complete');?>
	</div>
	<div class="alert alert-warning alert-dismissible" id="error" style="display: none;">
		<button type="button" class="close" data-dismiss="alert"
			aria-hidden="true">×</button>
		<h4>
			<i class="icon fa fa-warning"></i> error !
		</h4>
		<?=Yii::t('backend','Operation Fail');?>
	</div>
	</p>
											
	<div class="box box-default collapsed-box">
	<div class="overlay" style="display: none;" id="form_add_loading">
		<i class="fa fa-refresh fa-spin"></i>
	</div>
		<div class="box-header with-border">
			<h3 class="box-title"><?=Yii::t('backend','Newadd');?>/<?=Yii::t('backend','Editdo');?></h3>
			<div class="box-tools pull-right">
				<button data-widget="collapse" class="btn btn-box-tool">
					<i class="fa fa-plus"></i>
				</button>
			</div>
			<!-- /.box-tools -->
		</div>
		<!-- /.表单设定 新建编辑 -->
		<div class="box-body">
			<form class="form-horizontal" role="form">
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label"><?=Yii::t('backend','Names');?></label>
					<div class="col-sm-10">
						<input type="text" name="fourm_item_title" class="form-control" value="" placeholder="<?=Yii::t('backend','Column Name');?>">
					</div>
				</div>
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label"><?=Yii::t('backend','Typegenus');?></label>
					<div class="col-sm-5">
							<div class="radio">
								<label> <input type="radio" name="fourm_item_idtype" value="2" > <?=Yii::t('backend','Pic');?>
								</label>&nbsp;&nbsp;&nbsp;&nbsp;
								<label> <input type="radio" name="fourm_item_idtype" value="1"> <?=Yii::t('backend','Words');?>
								</label>&nbsp;&nbsp;&nbsp;&nbsp;
								<label> <input type="radio" name="fourm_item_idtype" value="3"> <?=Yii::t('backend','Tags');?>
								</label>
							</div>
					</div>
				</div>
			<span style="display: none;" id="word">	
				<hr>
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label"><?=Yii::t('backend','Prompttext');?></label>
					<div class="col-sm-10">
						<input type="text" class="form-control" value="" name="word_content_prompt"	placeholder="<?=Yii::t('backend','Prompttext');?>提示文字 ...">
					</div>
				</div>

				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label"><?=Yii::t('backend','Textceiling');?></label>
					<div class="col-sm-10">
						<input type="text" name="word_content_online" class="form-control" value=""	placeholder="<?=Yii::t('backend','Textceiling');?> ...">
					</div>
				</div>
			</span>
			<span style="display: none;" id='img'>		
				<hr>
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label"><?=Yii::t('backend','Upload Pic Limit');?></label>
					<div class="col-sm-10">
						<input type="text" name="img_content_online"  class="form-control" value="" placeholder="<?=Yii::t('backend','Upload Pic Limit');?> ...">
					</div>
				</div>
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label"><?=Yii::t('backend','Web Path');?></label>
					<div class="col-sm-10">
						<input type="text" name="img_content_path" class="form-control" value=""	placeholder="<?=Yii::t('backend','Web Path');?>...">
					</div>
				</div>
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label"><?=Yii::t('backend','Pic Type');?></label>
					<div class="col-sm-5">
							<div class="radio">
								<label> <input type="radio" name="img_content_type"		 value="1" ><?=Yii::t('backend','Sticker');?>
								</label>&nbsp;&nbsp;&nbsp;&nbsp;
								<label> <input type="radio" name="img_content_type"		 value="2"> <?=Yii::t('backend','Emotion');?>
								</label>
							</div>
					</div>
				</div>
			</span>	
			
			<span style="display: none;" id='tag'>		
				<hr>
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label"><?=Yii::t('backend','Tags Limit');?></label>
					<div class="col-sm-10">
						<input type="text" class="form-control"  name="tag_content_online"value=""		placeholder="<?=Yii::t('backend','Tags Limit');?> ...">
					</div>
				</div>
			</span>	
				<hr>
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label"><?=Yii::t('backend','Ifrequired');?></label>
					<div class="col-sm-5">
							<div class="radio">
								<label> <input type="radio" name="fourm_item_is_ver" value="0" checked=""><?=Yii::t('backend','Yesdo');?>
								</label>&nbsp;&nbsp;&nbsp;&nbsp;
								<label> <input type="radio" name="fourm_item_is_ver" value="1"><?=Yii::t('backend','Nodo');?>
								</label>
							</div>
					</div>
				</div>
					
			  </form>
			  <div class="form-group col-md-3">
					  <input type="hidden" name="id" value="">
						<button type="button" id="submit_button" class="btn btn-block btn-success"><?=Yii::t('backend','Newadd');?></button>
					</div>
					<div class="form-group col-md-3">
						<button type="button" id="submit_button_clear" class="btn btn-block label-danger"><?=Yii::t('backend','Empty');?></button>
					</div>
		</div>
		<!-- /.表单设定 新建编辑  end-->
	</div>
	<!-- 编辑表单 -->
	<!-- 
		<div class="box box-default collapsed-box">
		<div class="overlay" style="display: none;" id="form_edit_loading">
		<i class="fa fa-refresh fa-spin"></i>
	</div>
		<div class="box-header with-border">
			<h3 class="box-title">编辑</h3>
			<div class="box-tools pull-right">
				<button data-widget="collapse" class="btn btn-box-tool">
					<i class="fa fa-plus"></i>
				</button>
			</div>
	</div>
		-->	
		<!-- /.表单设定 新建编辑 -->
		<!--  
		<div class="box-body">
			<form class="form-horizontal" role="form">
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label">名称</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" value="數字空格"
							placeholder="栏目名称 ...">
					</div>
				</div>
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label">所属类型</label>
					<div class="col-sm-5">
							<div class="radio">
								<label> <input type="radio" name="optionsRadios_edit"
									id="optionsRadios1" value="img" > 图片
								</label>&nbsp;&nbsp;&nbsp;&nbsp;
								<label> <input type="radio" name="optionsRadios_edit"
									id="optionsRadios2" value="word"> 文字
								</label>&nbsp;&nbsp;&nbsp;&nbsp;
								<label> <input type="radio" name="optionsRadios_edit"
									id="optionsRadios2" value="tag"> 标签
								</label>
							</div>
					</div>
				</div>
			<span style="display: none;" id="word_edit">	
				<hr>
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label">提示文字</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" value=""
							placeholder="提示文字 ...">
					</div>
				</div>

				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label">文字上限</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" value=""
							placeholder="文字上限 ...">
					</div>
				</div>
			</span>
			<span style="display: none;" id='img_edit'>		
				<hr>
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label">上传图片上限</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" value=""
							placeholder="上传图片上限 ...">
					</div>
				</div>
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label">服务器路径</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" value=""
							placeholder="服务器路径 ...">
					</div>
				</div>
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label">图片类型</label>
					<div class="col-sm-5">
							<div class="radio">
								<label> <input type="radio" name="imgradio"
									id="optionsRadios1" value="option1" checked="">贴纸
								</label>&nbsp;&nbsp;&nbsp;&nbsp;
								<label> <input type="radio" name="imgradio"
									id="optionsRadios2" value="option2"> 表情
								</label>
							</div>
					</div>
				</div>
			</span>	
			
			<span style="display: none;" id='tag_edit'>		
				<hr>
				
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label">标签上限</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" value="标签上限"
							placeholder="标签上限 ...">
					</div>
				</div>
			</span>	
				<hr>
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label">是否必填</label>
					<div class="col-sm-5">
							<div class="radio">
								<label> <input type="radio" name="yesnoradio"
									id="optionsRadios1" value="option1" checked="">是
								</label>&nbsp;&nbsp;&nbsp;&nbsp;
								<label> <input type="radio" name="yesnoradio"
									id="optionsRadios2" value="option2">否
								</label>
							</div>
					</div>
				</div>
					<div class="form-group col-md-3">
						<button type="button" id="submit_button_edit" class="btn btn-block btn-success">编辑</button>
					</div>
			</form>
		</div>
		-->
		<!-- /.表单设定 新建编辑  end-->
	<!-- </div>-->
	<!-- 编辑表单end -->
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
			'fourm_item_title',
			[
			'attribute' => 'fourm_item_idtype',
			'label'=>Yii::t('backend','Form Types'),
			'content'=>
			function($model){
					return \backend\models\DwFourmCategoryItem::get_fourm_item_idtype($model->fourm_item_idtype);
			},
			],
			[
			'attribute' => 'fourm_item_content',
			'label'=>Yii::t('backend','Parameter Content'),
			'content'=>
			function($model){
				$prompttext = Yii::t('backend','Prompttext'); //提示文字
				$textceiling = Yii::t('backend','Textceiling'); //文字上限
				$uploadlimit = Yii::t('backend','Upload Pic Limit'); //上传图片上限
				$webpath = Yii::t('backend','Web Path'); //服务器路径
				$sticker = Yii::t('backend','Sticker');  //贴纸
				$emotion = Yii::t('backend','Emotion');  //表情
				$nodefined = Yii::t('backend','Nodefined'); //未定义
				$pictype = Yii::t('backend','Pic Type'); //图片类型
				$tagslimit = Yii::t('backend','Tags Limit'); //标签上限
				$fourm_item_content = json_decode($model->fourm_item_content,true);
				if( $model->fourm_item_idtype == 1){
						$str = ''.$prompttext.':'	.$fourm_item_content['word_content_prompt'].'<br>';
						$str .= ''.$textceiling.':'	.$fourm_item_content['word_content_online'].'<br>';
				}elseif($model->fourm_item_idtype == 2){
					$str = ''.$uploadlimit.':'	.$fourm_item_content['img_content_online'].'<br>';
					$str .= ''.$webpath.':'	.$fourm_item_content['img_content_path'].'<br>';
					if($fourm_item_content['img_content_type'] == 1){
						$img = $sticker;
					}elseif($fourm_item_content['img_content_type'] == 2){
						$img = $emotion;
					}else{
						$img = $nodefined;
					}
					$str .= ''.$pictype.':'	.$img.'<br>';
				}elseif($model->fourm_item_idtype == 3){
					$str = ''.$tagslimit.':'	.$fourm_item_content['tag_content_online'].'<br>';
				}
				return $str;
			},
			],
			[
			'attribute' => 'fourm_item_is_ver',
			'label'=>Yii::t('backend','Ismust'),
			'content'=>
				function($model){
					$yesdo = Yii::t('backend','Yesdo');
					$nodo = Yii::t('backend','Nodo');
					if($model->fourm_item_is_ver==0){
						return '<span class="label label-success">'.$yesdo.'</span>' ;
					}elseif($model->fourm_item_is_ver==1){
						return '<span class="label  bg-yellow">'.$nodo.'</span>' ;
					}
				},
			],
            ['class' => 'yii\grid\ActionColumn',
			'header'=>Yii::t('backend','Operation'),
			'template' => '{delete} {view}',
			'buttons' => [
				'view' => function ($url, $model) {
					$view = Yii::t('backend','Views');
					return '<a data-pjax="0" aria-label="'.$view.'" title="'.$view.'" href="javascript:;" onclick="edit_view('.$model->id.')" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span></a>';
				},
				'delete' => function ($url, $model) {
					$delete = Yii::t('backend','Deletes');
					return '<a data-pjax="0"  onclick="category_item_del('.$model->id.')" aria-label="'.$delete.'" title="'.$delete.'" href="javascript:;" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-trash"></span></a>';
				},
				],
			'urlCreator' => function ($action, $model, $key, $index) {
				
			}],
        ],
    ]); ?>
												</div>
												<!-- /.box-body -->
												<!-- Loading (remove the following to stop the loading)-->
												<div  class="overlay" style="display: none;" id="type_loading">
													<i class="fa fa-refresh fa-spin"></i>
												</div>
												<!-- end loading -->
											</div>

											<div class="form-group col-md-12"></div>
										</div>
										
<!-- 使用区域 -->										
<div class="tab-pane" id="panel-9792391">
<p>		</p>
	<div class="alert alert-success alert-dismissible" id="userarea_ok" style="display:none;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<h4>
			<i class="icon fa fa-check"></i> ok !
		</h4>
		<?=Yii::t('backend','Operation Complete');?>
	</div>
	<div class="alert alert-warning alert-dismissible" id="userarea_error" style="display:none;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<h4>
			<i class="icon fa fa-warning"></i> error !
		</h4>
		<?=Yii::t('backend','Operation Fail');?>
	</div>
	<p></p>
	<p></p>
											
	<!-- 使用区域 新增 -->										
	<div class="box box-default collapsed-box">
	<div class="overlay" style="display: none;" id="usearea_add_loading">
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
		<div class="box-body">
			<div class="form-group">
				<label><?=Yii::t('backend','Use Area');?></label> <input type="text" name="fourm_area" class="form-control" value="" placeholder="<?=Yii::t('backend','Form Type Name');?> ...">
			</div>
		<div class="form-group col-md-3">
				<label>&nbsp;</label>
				<input type="hidden" name="area_id" value="">
				<button type="button" id="usearea_button_add" class="btn btn-block btn-success "><?=Yii::t('backend','Newadd');?></button>
			</div>
				<div class="form-group col-md-3">
				<label>&nbsp;</label>
				<button type="button" id="usearea_button_clear" onclick="area_clear()" class="btn btn-block label-danger"><?=Yii::t('backend','Empty');?></button>
			</div>
		</div>
		<!-- /.box-body -->
	</div>
  <!-- 新增end -->
	<!-- 使用区域 编辑 -->	
	<!--									
	<div class="box box-default collapsed-box">
	<div class="overlay" style="display: none;" id="usearea_edit_loading">
		<i class="fa fa-refresh fa-spin"></i>
	</div>
		<div class="box-header with-border">
			<h3 class="box-title">编辑</h3>
			<div class="box-tools pull-right">
				<button class="btn btn-box-tool" data-widget="collapse">
					<i class="fa fa-plus"></i>
				</button>
			</div>
		</div>
		
		<div class="box-body">
			<div class="form-group">
				<label>使用区域</label> <input type="text" class="form-control" value="" placeholder="表单类型名称 ...">
			</div>
			<div class="form-group col-md-3">
				<label>&nbsp;</label>
				<button type="button" id="usearea_button_edit" class="btn btn-block btn-success ">编辑</button>
			</div>
		</div>
	</div>
	-->
<!-- 使用区域 编辑end -->											
											<!-- /.box -->
											<div class="box box-default">
												<div class="box-header with-border">
													<h3 class="box-title"><?=Yii::t('backend','List Infor');?></h3>
												</div>
												<div class="box-body">
		<?= GridView::widget([
        'dataProvider' => $dataProvider_area,
    	'emptyText' =>Yii::t('backend','No Data'),
        'columns' => [
		    'id',
			'fourm_area',
			'fourm_actions_uid',
			[
			'attribute' => 'fourm_dateline',
			'label'=>Yii::t('backend','Times'),
			'content'=>
				function($model){
					return  date('Y/m/d H:i',$model->fourm_dateline);   //主要通过此种方式实现
				},
			],
            ['class' => 'yii\grid\ActionColumn',
			'header'=>Yii::t('backend','Operation'),
			'template' => '{delete} {view}',
			'buttons' => [
				'view' => function ($url, $model) {
					$view = Yii::t('backend','Views');
					return '<a data-pjax="0" aria-label="'.$view.'" title="'.$view.'" href="javascript:;" onclick="usearea_view('.$model->id.')" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span></a>';
				},
				'delete' => function ($url, $model) {
					$delete = Yii::t('backend','Deletes');
					return '<a data-pjax="0"  onclick="area_del('.$model->id.')" aria-label="'.$delete.'" title="'.$delete.'" href="javascript:;" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-trash"></span></a>';
				},
				],
			'urlCreator' => function ($action, $model, $key, $index) {
				
			}],
        ],
    ]); ?>
												</div>
												<!-- /.box-body -->
												<!-- Loading (remove the following to stop the loading)-->
												<div class="overlay" style="display: none;" id="usearea_loading">
													<i class="fa fa-refresh fa-spin"></i>
												</div>
												<!-- end loading -->
											</div>
											<!-- /.box -->
											<div class="form-group col-md-12"></div>
										</div>								
<!-- 使用区域end-->										
										
<!-- 表单生成 -->
										<div class="tab-pane" id="panel-9792324">
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
	<p><?=Yii::t('backend','Review Search');?><?=Yii::t('backend','Explain');?></p>
	<small><cite title="Source Title"> <?=Yii::t('backend','Jurisdiction');?></cite></small>
	<small><cite title="Source Title"><?=Yii::t('backend','Channel');?></cite></small>
	<small><cite title="Source Title"> <?=Yii::t('backend','Authentication');?></cite></small>
</blockquote>
</p>
<!-- 操作区域 -->
<div class='row'>
		<div class="col-md-12">
			<div class="box box-default">
				<div class="box-header with-border">
					<h3 class="box-title"><?=Yii::t('backend','Review Search');?><?=Yii::t('backend','Operatingarea');?></h3>
				</div>
				<div class="box-body">
					<form role="form">
						<!-- text input -->

						<div class="form-group col-md-12">
							<label><?=Yii::t('backend','Use Area');?></label> <select class="form-control">
								<option value="">--<?=Yii::t('backend','Please Select');?>--</option>
								<?php 
		if( !empty($area_list)){
			foreach ($area_list as $a_k =>$a_v){
				echo '<option value="'.$a_v['id'].'">'.$a_v['fourm_area'].'</option>';
			}
		}
		?>	
							</select>
						</div>
						<div class="form-group col-md-12">
							<label><?=Yii::t('backend','Form Types');?></label> <select class="form-control">
								<option value="">--<?=Yii::t('backend','Please Select');?>--</option>
								<?php 
								if( !empty($category_list)){
									foreach ($category_list as $c_k =>$c_v){
										echo '<option value="'.$c_v['id'].'">'.$c_v['fourm_title'].'</option>';
									}
								}
								?>						
							</select>
						</div>
						<div class="form-group col-md-12">
							<label><?=Yii::t('backend','Css Set');?></label>
							<textarea class="form-control" rows="8"
								placeholder="<?=Yii::t('backend','Replycom');?>">123123123213131</textarea>
						</div>
						<div class="form-group col-md-12">
							<label><?=Yii::t('backend','Template');?></label>
							<textarea class="form-control" rows="8"
								placeholder="<?=Yii::t('backend','Replycom');?>">23131312321313</textarea>
						</div>

						<!-- textarea -->
						<div class="form-group col-md-12">
							<label><?=Yii::t('backend','Generate Js');?></label>
							<textarea class="form-control" rows="8"
								placeholder="<?=Yii::t('backend','Replycom');?>">123123131313131</textarea>
						</div>
							<div class="form-group col-md-3">
								<button type="button" id='makeing_button' class="btn btn-block btn-success"><?=Yii::t('backend','Generate');?></button>
							</div>
							<div class="form-group col-md-3">
								<button type="submit" class="btn btn-block btn-danger"><?=Yii::t('backend','Empty');?></button>
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


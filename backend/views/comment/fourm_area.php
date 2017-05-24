<?php
use yii\helpers\Html;
use yii\grid\GridView;
use Qiniu\json_decode;
use yii\helpers\Url;


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
										<li ><a href="<?php echo Url::toRoute(['comment/fourm', 'web_type' => 'category'])?>" ><?=Yii::t('backend','Form Types');?></a></li>
										<li class="active"><a href="<?php echo Url::toRoute(['comment/fourm', 'web_type' => 'area'])?>" ><?=Yii::t('backend','Use Area');?></a></li>
										<li><a href="<?php echo Url::toRoute(['comment/fourm', 'web_type' => 'makeing'])?>" ><?=Yii::t('backend','Generation Manage');?></a></li>
									</ul>
									<div class="tab-content">
										
<!-- 使用区域 -->										
<div class="tab-pane active" id="panel-9792391">
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
				<label><?=Yii::t('backend','Use Area');?></label> <input type="text" name="fourm_area" class="form-control" value="" placeholder="<?=Yii::t('backend','Form Type Name');?>">
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


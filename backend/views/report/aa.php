<?php
use common\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $model common\models\Article */
/* @var $module string */

$this->title = '举报类型';
$this->params['breadcrumbs'][] = [
    'label' => '举报管理',
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header')?>
<?= $this->title;?>
<?php $this->endBlock()?>
<script>
var sensitive_add_name = '快捷添加';
var sensitive_edit_name = '完成修改';
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
							<h3 class="box-title">快速添加</h3>
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
									名称: <input type="text" class="form-control" id='name'> 
									说明: <input	type="text" class="form-control" id='description'>
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
					<div class="box box-default collapsed-box">
						<div class="overlay" style="display: none;" id="edit_loading">
							<i class="fa fa-refresh fa-spin"></i>
						</div>
						<div class="box-header with-border">
							<h3 class="box-title">编辑</h3>
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
									名称: <input type="text" class="form-control" id='view_name'> 
									说明: <input	type="text" class="form-control" id='view_description'>
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
				<h3 class="box-title">列表信息</h3>
			</div>
			<div class="box-body">
			 <?= GridView::widget([
        'dataProvider' => $dataProvider,
    	'emptyText' =>'没有数据',
        'columns' => [
            'id',
            'sensitive_name',
            'sensitive_description',
			[
			'attribute' => 'sensitive_time',
			'label'=>'操作时间',
			'value'=>
			function($model){
				return  date('Y-m-d H:i',$model->sensitive_time);   //主要通过此种方式实现
			},
			],
            ['class' => 'yii\grid\ActionColumn',
			'header' => '操作',
			'template' => '{delete} {view}',
			'buttons' => [
				'view' => function ($url, $model) {
					return '<a  class="view" data="'.$model->id.'" href="javascript:void(0);" title="查看" aria-label="查看"><span class="glyphicon glyphicon-eye-open"></span></a>';
				},
				'delete' => function ($url, $model) {
					return '<a  class="view_del" data="'.$model->id.'" href="javascript:void(0);" title="删除" aria-label="删除" data-confirm="您确定要删除此项吗？" ><span class="glyphicon glyphicon-trash"></span></a>	';				
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

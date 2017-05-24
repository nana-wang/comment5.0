<?php
use common\helpers\Html;
use yii\grid\GridView;
use common\components\JsLayouts;
use yii\widgets\ActiveForm;
use Distill\Format\Simple\Img;

//use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $model common\models\Article */
/* @var $module string */

$this->title = '道具分类';
$this->params['breadcrumbs'][] = [
    'label' => '道具分类',
    'url' => [
        'propindex'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header')?>
<h1><?= $this->title;?>
</h1>
<?php $this->endBlock()?>
<script>
var props_add_name = '添加';
var props_edit_name = '完成修改';
</script>
<div class="comment-index">
	<div class="box box-primary">
		<div class="box-body">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-default collapsed-box">
					<div id="add_loading" style="display: none;" class="overlay">
							<i class="fa fa-refresh fa-spin"></i>
						</div>
						<div class="box-header with-border">
							<h3 class="box-title">添加/编辑</h3>
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
								<?php $form = ActiveForm::begin([
										'action' => ['category_add'],
							            ]); 
							    ?>
							<div class="form-group col-md-3">
<?= $form->field($model, 'props_category_name') ->label('分类名称') ; ?> 
								
							</div>
							    <div class="form-group">
							    <input type="hidden" id="dwpropscategory-id" class="form-control" name="DwPropsCategory[id]">
							        <?= Html::submitButton('添加', ['class' => 'btn btn-block btn-info','id'=>'button']) ?>
							    </div>
							    <?php ActiveForm::end(); ?>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</div>
			</div>
			
		</div>
		<div class="box box-default">
			<div class="box-body">
				<?= GridView::widget([
			        'dataProvider' => $dataProvider,
			        'columns' => [
			            'id',
						'props_category_name',
			            ['class' => 'yii\grid\ActionColumn',
		        		'header' => '操作',
		        		'template' => '{delete} {view}',
		        		'buttons' => [
	        				'view' => function ($url, $model) {
	        					return '<a  class="view" onclick="props_category_view('.$model->id.')" data="'.$model->id.'" href="javascript:void(0);" title="查看" aria-label="查看"><span class="glyphicon glyphicon-eye-open"></span></a>';
	        				},
	        				'delete' => function ($url, $model) {
								return '<a data-pjax="0"  onclick="props_category_del('.$model->id.')" aria-label="删除" title="删除" href="javascript:;" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-trash"></span></a>';
							},
	        			],
        				'urlCreator' => function ($action, $model, $key, $index) {
        				
        				}],
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
		<!-- /.box -->
	</div>
</div>

<?php 
use backend\assets\AppAsset;
AppAsset::addScript($this,'@web/static/js/props.js');
?>


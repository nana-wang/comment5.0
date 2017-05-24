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

$this->title = '道具管理';
$this->params['breadcrumbs'][] = [
    'label' => '道具管理',
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
var emoticon_add_name = '添加';
var emoticon_edit_name = '完成修改';
var category_emoticon_add_name = '添加';
var category_emoticon_edit_name = '保存';
</script>
<div class="comment-index">
	<div class="box box-primary">
		<div class="box-body">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-default collapsed-box">
						<div class="box-header with-border">
							<h3 class="box-title">添加</h3>
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
										'action' => ['add'],
							    		'id' => "upload-form",
							            'enableAjaxValidation' => false,
							            'options' => ['enctype' => 'multipart/form-data'],]); 
							    ?>
							<div class="form-group col-md-3">
<?= $form->field($model, 'props_available') ->dropDownList([0=>'可用',1=>'不可用'])->label('道具状态') ; ?> 
								
							</div>
							<div class="form-group col-md-3">
<?= $form->field($model, 'props_category_id') ->dropDownList($propscategory_redis)->label('道具分类') ; ?> 
								
							</div>
							<div class="form-group col-md-3">
<?= $form->field($model, 'props_name') ->label('道具名称') ; ?> 
								
							</div>
							
							<div class="form-group col-md-3">
<?= $form->field($model, 'props_credit') ->label('道具兑换积分') ; ?> 
								
							</div>
							<div class="form-group col-md-3">
<?= $form->field($model, 'props_description') ->label('道具描述') ; ?> 
							</div>
							<div class="form-group col-md-3">
				<?= $form->field($model, 'file')->fileInput()->label('道具图标')  ?>				
							</div>
							    <div class="form-group">
							        <?= Html::submitButton('添加', ['class' => 'btn btn-block btn-info']) ?>
							    </div>
							
							    <?php ActiveForm::end(); ?>
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
							<h3 class="box-title">修改编辑</h3>
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
										'action' => ['edit'],
							    		'id' => "upload-form3",
							            'enableAjaxValidation' => false,
							            'options' => ['enctype' => 'multipart/form-data'],]); 
							    ?>
							<div class="form-group col-md-3">
<?= $form->field($model, 'props_available') ->dropDownList([0=>'可用',1=>'不可用'])->label('道具状态') ; ?> 
								
							</div>
							<div class="form-group col-md-3">
<?= $form->field($model, 'props_category_id') ->dropDownList($propscategory_redis)->label('道具分类') ; ?> 
								
							</div>
							<div class="form-group col-md-3">
<?= $form->field($model, 'props_name') ->label('道具名称') ; ?> 
								
							</div>
							
							<div class="form-group col-md-3">
<?= $form->field($model, 'props_credit') ->label('道具兑换积分') ; ?> 
								
							</div>
							<div class="form-group col-md-12">
<?= $form->field($model, 'props_description') ->label('道具描述') ; ?> 
								
							</div>
							<div class="form-group col-md-3">
				<?php  //$form->field($model, 'file')->fileInput()->label('道具图标')  ?>				
							</div>
							    <div class="form-group">
							    <input type="hidden" id="dwpropscategory-id" class="form-control" name="DwProps[id]">
							        <?= Html::submitButton('编辑', ['class' => 'btn btn-block btn-info']) ?>
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

						[
						'attribute' => 'props_category_id',
						'label'=>'道具分类',
						'value'=>function($model){
							return \backend\models\DwPropsCategory::get_category_redis($model->props_category_id);
	
						}
						],
			            'props_name',
			            'props_description',
		        		[
							'attribute' => 'props_img',			        		
							'label'=>'道具图标',
			        		'content'=>function($model){
		        				return Html::img(Yii::$app->urlManager->hostInfo.$model->props_img,
		        				['width'=>'30','height'=>'30']
		        				);
			        		}
		        		],
			            'props_credit',
			            [
			            'attribute' => 'props_available',
			            'label'=>'状态',
			            'content' =>function($model){
			            	if($model->props_available == 0 ){
			            		return '<span class="label label-success">可用</span>';
			            	}elseif($model->props_available == 1 ){
			            		return '<span class="label label-danger">不可用</span>';
			            	}else{
			            		return '<span class="label bg-navy">未知</span>';
			            	}
			            }
			            ],
			            ['class' => 'yii\grid\ActionColumn',
		        		'header' => '操作',
		        		'template' => '{delete} {view}',
		        		'buttons' => [
	        				'view' => function ($url, $model) {
	        					return '<a  onclick="props_view('.$model->id.')" data="'.$model->id.'" href="javascript:void(0);" title="查看" aria-label="查看"><span class="glyphicon glyphicon-eye-open"></span></a>';
	        				},
	        				'delete' => function ($url, $model) {
								return '<a data-pjax="0"  onclick="props_del('.$model->id.')" aria-label="删除" title="删除" href="javascript:;" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-trash"></span></a>';
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

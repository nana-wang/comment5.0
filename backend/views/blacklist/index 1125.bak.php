<?php
use common\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $model common\models\Article */
/* @var $module string */

$this->title = '黑名单管理';
$this->params['breadcrumbs'][] = [
    'label' => '黑名单',
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header')?>
<h1><?= $this->title;?>
</h1>
<?php $this->endBlock()?>
<script>
var blacklist_add_name = '检索';
</script>
<div class="comment-index">

	<div class="box box-primary">
		<!-- 检索结束开始 -->
		
			<div class="box-body">
			<div class="row">
				<div class="col-md-12">  
					<div class="box box-default collapsed-box">
						<div class="box-header with-border">
							<h3 class="box-title">检索</h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool"
									data-widget="collapse">
									<i class="fa fa-plus"></i>
								</button>
							</div>
						</div><!-- /.box-header -->
						<?php $form = ActiveForm::begin([
				    		    'action' => ['index'],
						        'method' => 'get',
				            ]); 
					    ?>
						<div class="box-body" >
							<div class="form-group col-md-3">
			<?= $form->field($searchModel, 'blacklist_uid') ->label('黑名单uid') ; ?> 
							</div>
							<!--  
							<div class="form-group col-md-3">
			<?= $form->field($searchModel, 'blacklist_action_uid') ->label('操作人uid') ; ?> 
							</div>
							<div class="form-group col-md-3">
			<?php 
				//echo $form->field($searchModel, 'blacklist_create')
				//->widget(DatePicker::classname(), ['pluginOptions' =>['format'=>'yyyy-m-d'] ]);		
?>			
							</div>-->
							<?= Html::submitButton('检索', ['class' => 'btn btn-block btn-info']) ?>
						</div>
						<!-- /.box-body -->
						<?php ActiveForm::end(); ?>
					</div>
					<!-- /.box -->
				</div>
			</div>
		</div>
		<!-- 检索结束 -->
		
		
		<div class="box box-default" id='list'>
			<div class="box-body">
			<?= GridView::widget([
		        'dataProvider' => $dataProvider,
		    	'emptyText' =>'没有数据',
		        'columns' => [
		            'id',
// 		            [
// 					'attribute' => 'blacklist_uid',
// 					'label'=>'黑名单用户',
// 					'value'=>
// 						function ($model) {
// 							return \backend\models\User::get_user_name($model->blacklist_uid,'username');
// 						},
// 					],
					'blacklist_uid',
		            'blacklist_action_uid',
		            [
					'attribute' => 'blacklist_create',
					'label'=>'创建时间',
					'value'=>
					function($model){
						return  date('Y-m-d H:i',$model->blacklist_create);
					},
					],
		            [
                        'class' => 'backend\widgets\grid\ActionColumn',
                        'header' => '操作',
						'template' => '{assign}',
						'buttons' => [
						'assign' => function ($url, $model) {
								return '<a  class="view_del" data="'.$model->id.'" href="javascript:void(0);" title="移除" aria-label="移除"><span class="glyphicon glyphicon-hand-left"></span></a>	';				
						}
					    ],
                    ]

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
AppAsset::addScript($this,'@web/static/js/blacklist.js');
?>

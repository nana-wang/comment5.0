<?php
use common\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $model common\models\Article */
/* @var $module string */

$this->title = Yii::t('backend','Black Manage'); //黑名单管理
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('backend','Black List'), //黑名单
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
<?php $retrieval = Yii::t('backend','Retrieval');?>

<?php $choseuser = Yii::t('backend','Chose users');?>
<?php $doing = Yii::t('backend','Doing');?>
<?php $addsucess = Yii::t('backend','Add Sucess');?>
<?php $sureremove = Yii::t('backend','Sure Remove User');?>

<script>
var blacklist_add_name = '<?php echo $retrieval;?>'; //检索
var issearch = '<?php echo $issearch;?>';
var choseuser = '<?php echo $choseuser;?>'; //选择用户
var doing = '<?php echo $doing;?>'; //处理中...
var addsucess = '<?php echo $addsucess;?>'; //添加成功
var sureremove = '<?php echo $sureremove;?>'; //您确定要移除此用户吗

</script>
<div class="comment-index">

	<div class="box box-primary">
		<!-- 检索结束开始 -->
		
			<div class="box-body">
			<div class="row">
				<div class="col-md-12">  
					<div class="box box-default collapsed-box box-default-viev">
						<div class="box-header with-border">
							<h3 class="box-title"><?=Yii::t('backend','Retrieval');?></h3>
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
						<div class="box-body box-body-view" >
							<div class="form-group col-md-3">
								<label><?=Yii::t('backend','The Account');?></label>                  
								<select class="form-control" id='blacklist_account_id' name="DwBlacklistSearch[blacklist_account_id]" >
									<option value="">--<?=Yii::t('backend','The Account');?>--</option>
									<?= \backend\models\DwAuthAccount::getAccountSelectRedis();?>
								</select>
							</div>
							<div class="form-group col-md-3">
							<?= $form->field($searchModel, 'blacklist_level') ->dropDownList([1=>Yii::t('backend','Normal'),2=>Yii::t('backend','Locking'),3=>Yii::t('backend','Abnormal')])->label(Yii::t('backend','Grade')) ; ?> 
								
							</div>
							<div class="form-group col-md-3">
							<?= $form->field($searchModel, 'blacklist_uid') ->label(Yii::t('backend','Blick User')) ; ?> 
							</div>
							<!--  
							<div class="form-group col-md-3">
			<?= $form->field($searchModel, 'blacklist_action_uid') ->label(Yii::t('backend','Operatoruid')) ; ?> 
							</div>
							<div class="form-group col-md-3">
			<?php 
				//echo $form->field($searchModel, 'blacklist_create')
				//->widget(DatePicker::classname(), ['pluginOptions' =>['format'=>'yyyy-m-d'] ]);		
?>			
							</div>-->
							<?= Html::submitButton(Yii::t('backend','Retrieval'), ['class' => 'btn btn-block btn-info']) ?>
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
		    	'emptyText' =>Yii::t('backend','No Data'),
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
					[
					'attribute' => 'blacklist_action_uid',
					'label'=>Yii::t('backend','Operator'),
					'content'=>
					function($model){
						$sensitive_operator = \backend\models\DwUser::get_user_redis_name($model->blacklist_action_uid);
						return $sensitive_operator;
					},
					],
		            [
					'attribute' => 'blacklist_account_id',
					'label'=>Yii::t('backend','The Account'),
					'content'=>
					function($model){
						$msg = Yii::t('backend','No Account');
						$account = \backend\models\DwAuthAccount::getAccountById($model->blacklist_account_id);
						if( isset($account['name'])){
							return $account['name'];
						}else{
							return $msg;
						}
						},
					],
					 [
			            'attribute' => 'blacklist_level',
			            'label'=>Yii::t('backend','Grade'),
			            'content' =>function($model){
			            	$normal = Yii::t('backend','Normal');
			            	$locking = Yii::t('backend','Locking');
			            	$abnormal = Yii::t('backend','Abnormal');
			            	if($model->blacklist_level ==1 ){
			            		return '<span class="label label-success">'.$normal.'</span>';
			            	}elseif($model->blacklist_level == 2 ){
			            		return '<span class="label label-danger">'.$locking.'</span>';
			            	}else{
			            		return '<span class="label bg-navy">'.$abnormal.'</span>';
			            	}
			            }
			            ],
		            [
					'attribute' => 'blacklist_create',
					'label'=>Yii::t('backend','Creat Time'),
					'value'=>
					function($model){
						return  date('Y-m-d H:i',$model->blacklist_create);
					},
					],
		            [
                        'class' => 'backend\widgets\grid\ActionColumn',
                        'header' => Yii::t('backend','Operation'),
						'template' => '{assign}',
						'buttons' => [
						'assign' => function ($url, $model) {
								$remove = Yii::t('backend','Remove');
								return '<a  class="view_del" data="'.$model->id.'" href="javascript:void(0);" title="'.$remove.'" aria-label="'.$remove.'"><span class="glyphicon glyphicon-trash"></span></a>	';				
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

<?php
use common\helpers\Html;
use yii\grid\GridView;
use yii\base\Widget;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $model common\models\Article */
/* @var $module string */

$this->title = Yii::t('backend','Emotion Class');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('backend','Emotion Class'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $proadd = Yii::t('backend','Proadd');?>
<?php $completed = Yii::t('backend','Completed');?>
<?php $save = Yii::t('backend','Save');?>

<?php $suredelete = Yii::t('backend','Sure Delete');?>
<?php $emotionempty = Yii::t('backend','Emotio Name Empty');?>
<?php $doing = Yii::t('backend','Doing');?>
<?php $savesuccess= Yii::t('backend','Save Success');?>
<?php $accountempty= Yii::t('backend','Account No Empty');?>
<?php $categoryempty= Yii::t('backend','Category Name Empty');?>
<?php $addsucess= Yii::t('backend','Add Sucess');?>
<script>
var emoticon_add_name = '<?php echo $proadd;?>';
var emoticon_edit_name = '<?php echo $completed;?>';
var category_emoticon_add_name = '<?php echo $proadd;?>';
var category_emoticon_edit_name = '<?php echo $save;?>';

var suredelete = '<?php echo $suredelete;?>'; //您确定要删除此项吗？
var emotionempty = '<?php echo $emotionempty;?>'; //表情名称不能为空
var doing = '<?php echo $doing;?>'; //处理中
var savesuccess = '<?php echo $savesuccess;?>'; //保存成功
var accountempty = '<?php echo $accountempty;?>'; //‘所属账户’不能为空
var categoryempty = '<?php echo $categoryempty;?>'; //‘分类名称’不能为空
var addsucess = '<?php echo $addsucess;?>'; //添加成功
var issearch = '0';

</script>
<?php $this->beginBlock('content-header')?>
<?= $this->title;?>
<?php $this->endBlock()?>
<div class="comment-index">
	<div class="box box-primary">
		<div class="box-body">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-default collapsed-box">
						<div class="box-header with-border">
							<h3 class="box-title"><?=Yii::t('backend','Quick Add');?></h3>
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
						<div class="form-group col-md-6">
								<label for="exampleInputEmail1"><?=Yii::t('backend','The Account');?></label> 
								<select class="form-control"  id="emoticon_account"  name="emoticon_account" >
									<option value="">--<?=Yii::t('backend','Account Group');?>--</option>
						<?= backend\widgets\MenuLeft::widget([],3);?>
								</select>
							</div>
							<div class="form-group col-md-6">
								<label for="exampleInputEmail1"><?=Yii::t('backend','Category Name');?></label> 
								<input type="email"	class="form-control"  id="emoticon_category_name" placeholder="<?=Yii::t('backend','Category Name');?>">
							</div>
							
							<button type="button" id="category_emoticon_add"
								class="btn btn-block btn-info"></button>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="box box-default box-default-edit collapsed-box">
						<div class="overlay" style="display: none;" id="category_edit_loading">
							<i class="fa fa-refresh fa-spin"></i>
						</div>
						<div class="box-header with-border">
							<h3 class="box-title"><?=Yii::t('backend','Edit Edit');?></h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool"
									data-widget="collapse">
									<i class="fa fa-plus"></i>
								</button>
							</div>
							<!-- /.box-tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body box-body-edit">
							<div class="form-group col-md-6">
								<label for="exampleInputEmail1"><?=Yii::t('backend','Category Name');?></label>
								<input type="email" class="form-control"  id="category_view_name" placeholder="<?=Yii::t('backend','Category Name');?>">
								<input type="hidden" class="form-control" id='category_view_id'> 
							</div>

							<button type="button" id="category_emoticon_edit"
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
				<h3 class="box-title"><?=Yii::t('backend','List Infor');?></h3>
			</div>
			<div class="box-body">
				<?= GridView::widget([
			        'dataProvider' => $dataProvider,
			        'columns' => [
			            ['class' => 'yii\grid\SerialColumn'],
			            'id',
			            'emoticon_category_name',
						[
						'attribute' => 'report_account_id',
						'label'=>Yii::t('backend','The Account'),
						'value'=>function($model){
							$account = \backend\models\DwAuthAccount::getAccountById($model->emoticon_account_id);
							if( isset($account['name'])){
								return $account['name'];
							}else{
								return Yii::t('backend','No Account');
							}
						}
						],
			            'emoticon_category_create_time:datetime',
			            'emoticon_category_update_time:datetime',
		        		['class' => 'yii\grid\ActionColumn',
		        		'header' => Yii::t('backend','Operation'),
		        		'template' => '{delete} {view}',
		        		'buttons' => [
	        				'view' => function ($url, $model) {
	        					$view = Yii::t('backend','Views');
	        					return '<a  class="view_category" data="'.$model->id.'" href="javascript:void(0);" title="'.$view.'" aria-label="'.$view.'"><span class="glyphicon glyphicon-eye-open"></span></a>';
	        				},
	        				'delete' => function ($url, $model) {
	        					$delete = Yii::t('backend','Deletes');
	        					return '<a  class="view_del" data="'.$model->id.'" href="javascript:void(0);" title="'.$delete.'" aria-label="'.$delete.'"  ><span class="glyphicon glyphicon-trash"></span></a>	';
	        				}
        					],
        				],
			        ],
			    ]); ?>
			</div>
			<!-- /.box-body -->
			<!-- Loading (remove the following to stop the loading)-->
			<div class="overlay" style="display: none;" id="category_show_loading">
				<i class="fa fa-refresh fa-spin"></i>
			</div>
			<!-- end loading -->
		</div>
	</div>
</div>
<?php 
use backend\assets\AppAsset;
AppAsset::addScript($this,'@web/static/js/emoticon.js');
?>
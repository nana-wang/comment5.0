<?php
use common\helpers\Html;
use yii\grid\GridView;
use common\components\JsLayouts;
use yii\widgets\ActiveForm;
use Distill\Format\Simple\Img;
use mdm\admin\AdminAsset;
//use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $model common\models\Article */
/* @var $module string */

$this->title = Yii::t('backend','Emotion Manage');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('backend','Emotion Manage'),
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
<?php $proadd = Yii::t('backend','Proadd');?>
<?php $completed = Yii::t('backend','Completed');?>
<?php $save = Yii::t('backend','Save');?>


<?php $suredelete = Yii::t('backend','Sure Delete');?>
<?php $emotionempty = Yii::t('backend','Emotio Name Empty');?>
<?php $doing = Yii::t('backend','Doing');?>
<?php $savesuccess= Yii::t('backend','Save Success');?>
<?php $accountempty= Yii::t('backend','Account No Empty');?>
<?php $categoryempty= Yii::t('backend','Category Name Empty');?>
<?php $expressionempty= Yii::t('backend','Expression Empty');?>
<?php $pleaseuploadfile= Yii::t('backend','Please Upload File');?>
<?php $addsucess= Yii::t('backend','Add Sucess');?>
<?php $accountnameempty= Yii::t('backend','Account Name Empty');?>
<script>
var emoticon_add_name = '<?php echo $proadd;?>';
var emoticon_edit_name = '<?php echo $completed;?>';
var category_emoticon_add_name = '<?php echo $proadd;?>';
var category_emoticon_edit_name = '<?php echo $save;?>';
var issearch = '<?php echo $issearch;?>';

var suredelete = '<?php echo $suredelete;?>'; //您确定要删除此项吗？
var emotionempty = '<?php echo $emotionempty;?>'; //表情名称不能为空
var doing = '<?php echo $doing;?>'; //处理中
var savesuccess = '<?php echo $savesuccess;?>'; //保存成功
var accountempty = '<?php echo $accountempty;?>'; //‘所属账户’不能为空
var categoryempty = '<?php echo $categoryempty;?>'; //‘分类名称’不能为空
var expressionempty = '<?php echo $expressionempty?>';   //表情分类不能为空
var pleaseuploadfile = '<?php echo $pleaseuploadfile;?>'; // 请上传文件
var addsucess = '<?php echo $addsucess;?>'; //添加成功
</script>
<div class="comment-index">
	<div class="box box-primary">
		<div class="box-body">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-default collapsed-box">
						<div class="box-header with-border">
							<h3 class="box-title"><?=Yii::t('backend','Batch Add');?></h3>
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
										'action' => ['emoticon/upload'],
							    		'id' => "upload-form",
							            'enableAjaxValidation' => false,
							            'options' => ['enctype' => 'multipart/form-data'],]); 
							    ?>
							    <div class="form-group col-md-3">
								<div class="form-group field-dwemoticon-emoticon_cate_id required">
								<label for="dwemoticon-emoticon_cate_id" class="control-label"><?=Yii::t('backend','The Account');?></label>
								<select class="form-control" onChange="change('')" id='dwemoticon-emoticon_account_id'  name="DwEmoticon[emoticon_account_id]" >
									<option value="">--<?=Yii::t('backend','Account Group');?>--</option>
									<?= backend\widgets\MenuLeft::widget([],3);?>
								</select>
								<div class="help-block"></div>
								</div>
							</div>
							    <div class="form-group col-md-3">
							<!-- //$form->field($model, 'emoticon_cate_id')->dropDownList($cate)  -->
							<div class="form-group field-dwemoticon-emoticon_cate_id required">
								<label for="dwemoticon-emoticon_cate_id" class="control-label"><?=Yii::t('backend','The Category');?></label>
								<select name="DwEmoticon[emoticon_cate_id]" class="form-control" id="dwemoticon-emoticon_cate_id">
								<option value=""><?=Yii::t('backend','The Category');?></option>
								</select>
								
								<div class="help-block"></div>
								</div>	
							</div>
							
								 <div class="form-group col-md-5">
								<?= $form->field($model, 'file')->fileInput() ?>
							</div>
							    <div class="form-group">
							        <?= Html::Button(Yii::t('backend','Uploads'), ['id' => 'upsubmit','class' => 'btn btn-block btn-info']) ?>
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
					<div class="box box-default box-default-edit collapsed-box">
						<div class="overlay" style="display: none;" id="edit_loading">
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
						<div class="box-body  box-body-edit">
							<div class="form-group col-md-6">
							<div style="display: none;" id="emoticon_preview">
								<?=Yii::t('backend','Emotion');?>：<img id="emo_preview" alt="" src="">
							</div>
								<label for="dwemoticon-emoticon_cate_id" class="control-label"><?=Yii::t('backend','Emotio Name');?></label>
								<input type="text" class="form-control" id='emoticonName'>
<!-- 								所属分类: <select name="emoticon_cate" class="form-control" id='emoticon_cate'> --> 
								    <?php 
// 								    	if( !empty($cate)){
// 											foreach($cate as $key =>$v){
// 												echo '<option value='.$key.'>'.$v.'</option>';
// 											}
// 										}
								    ?>
<!-- 								</select> -->
								<input type="hidden" id='emoticon_cate' value=''>
								<input type="hidden" id='emoticon_id' value=''>
							</div>
							<button type="button" id="emoticon_edit"
								class="btn btn-block btn-info"></button>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</div>
			</div>
			<?php  //echo $this->render('_search', ['model' => $searchModel,'cate'=>$cate]); ?>
			<div class="row">
				<div class="col-md-12">
						<div class="box box-default collapsed-box box-default-viev">
						<div class="overlay" style="display: none;" id="search_loading">
							<i class="fa fa-refresh fa-spin"></i>
						</div>
						<div class="box-header with-border">
							<h3 class="box-title"><?=Yii::t('backend','Retrieval');?></h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse">
									<i class="fa fa-plus"></i>
								</button>
							</div>
							<!-- /.box-tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body box-body-view">
						<div class="form-group col-md-3">
								<label><?=Yii::t('backend','The Account');?></label> 
								<select class="form-control" onchange="change_search('')" id="account_search" name="account_search">
									<option value="">--<?=Yii::t('backend','Account Group');?>--</option>
									<?= \backend\models\DwAuthAccount::getAccountSelectRedis();?>
								</select>
							</div>
							<div class="form-group col-md-3">
								<label><?=Yii::t('backend','Emotion Class');?></label> 
								<select name="emoticon_cate_id_search" class="form-control" id="emoticon_cate_id_search">
										<option value="" ><?=Yii::t('backend','Emotion Class');?></option>
										<?php 
								    	if( !empty($cate)){
											foreach($cate as $key =>$v){
												$check='';
												if( $cate_id == $key){
													$check ='selected';
												}
												echo '<option '.$check.' value='.$key.'>'.$v.'</option>';
											}
										}
								    ?>	
											
								</select>
							</div>
							<button type="button" id="emoticon_search" class="btn btn-block btn-info"><?=Yii::t('backend','Retrieval');?></button>
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
			        	['attribute' => 'emoticon_cate_id','label'=>Yii::t('backend','Class Ification'), 'value' => 'catename.emoticon_category_name'],
			            'emoticon_name',
		        		[
			        		'label'=>Yii::t('backend','Emotion'),
			        		'format'=>'raw',
			        		'value'=>function($m){
		        				return Html::img(Yii::$app->urlManager->hostInfo.'/upload/'.$m->emoticon_url,
		        				['width'=>'30','height'=>'30']
		        				);
			        		}
		        		],
		        		[
		        		'attribute' => 'emoticon_account_id',
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
			            'emoticon_create_time:datetime',
			
			            ['class' => 'yii\grid\ActionColumn',
		        		'header' => Yii::t('backend','Operation'),
		        		'template' => '{delete} {view}',
		        		'buttons' => [
							'delete' => function ($url, $model) {
								return '<a href="/admin/index.php?r=emoticon%2Femoticon_del&id='.$model->id.'" title="删除" aria-label="删除" data-confirm="'.  Yii::t('backend','Sure Delete').'" data-method="post" data-pjax="0"><span class="glyphicon glyphicon-trash"></span></a>';
							},
							'view' => function ($url, $model) {
								$view = Yii::t('backend','Views');
								return '<a  class="view" data="'.$model->id.'" href="javascript:void(0);" title="'.$view.'" aria-label="'.$view.'"><span class="glyphicon glyphicon-eye-open"></span></a>';
							}
						],
        				'urlCreator' => function ($action, $model, $key, $index) {
        				if ($action === 'delete') {
        					return ['emoticon_del', 'id' => $model->id];
        				}
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

AdminAsset::register($this);
$js = <<<JS

$(function() {
	var account_search = '$account';
	$('#account_search').val(account_search);
	change('');
});
JS;
$this->registerJs($js);

use backend\assets\AppAsset;
AppAsset::addScript($this,'@web/static/js/emoticon.js');
?>
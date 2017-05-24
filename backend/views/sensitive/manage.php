<?php
use common\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\base\Widget;
use backend\models\DwCommentQuery;
use backend\models\search\DwCommentSearch;
use mdm\admin\AdminAsset;
/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $model common\models\Article */
/* @var $module string */

$this->title = Yii::t('backend','Manage Sensitive Word'); //管理敏感词
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('backend','Sensitive Word'),
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

<?php $quikadd = Yii::t('backend','Quik Add');?>
<?php $completed = Yii::t('backend','Completed');?>
<?php $retrieval = Yii::t('backend','Retrieval');?>


<?php $namempty = Yii::t('backend','Name Not Empty');?>
<?php $expempty = Yii::t('backend','Explain Not Empty');?>
<?php $accempty = Yii::t('backend','Account Not Empty');?>
<?php $doing = Yii::t('backend','Doing');?>
<?php $uploadex = Yii::t('backend','Upload Excel XLS');?>
<?php $typenot = Yii::t('backend','Type Not Right');?>
<?php $pleselect = Yii::t('backend','Please Select Account');?>
<?php $fillsensitive = Yii::t('backend','Fill Sensitive Words');?>
<?php $fillvalue = Yii::t('backend','Fill Sensitive Value');?>
<?php $filldictionary = Yii::t('backend','Fill Dictionary');?>
<?php $chosemeth = Yii::t('backend','Chose Upload Mode');?>
<?php $comnotempty = Yii::t('backend','Comment Not Empty');?>
<?php $suredelete = Yii::t('backend','Sure Delete');?>
<script>
var sensitive_add_name = '<?php echo $quikadd;?>';
var sensitive_edit_name = '<?php echo $completed;?>';
var sensitive_search_name = '<?php echo $retrieval;?>';
//js语言包
var namempty = '<?php echo $namempty;?>';
var expempty = '<?php echo $expempty;?>';
var accempty = '<?php echo $accempty;?>';
var doing = '<?php echo $doing;?>'; //处理中...
var uploadex = '<?php echo $uploadex;?>';
var typenot = '<?php echo $typenot;?>';
var pleselect = '<?php echo $pleselect;?>'; //请选择所属账户
var fillsensitive = '<?php echo $fillsensitive;?>'; //请填写敏感词
var fillvalue = '<?php echo $fillvalue;?>'; //请填写敏感词替换值
var filldictionary = '<?php echo $filldictionary;?>'; //请填写词典生成位置
var chosemeth = '<?php echo $chosemeth;?>'; //请选择上传方式
var comnotempty = '<?php echo $comnotempty;?>'; //内容不能为空，请填写相应的内容
var suredelete = '<?php echo $suredelete;?>'; //您确定要删除此项吗？
var Sensitive_Level_empty = '<?php echo Yii::t('backend','Sensitive Level empty');?>'; //'敏感詞等級',
</script>
<div class="comment-index">

	<div class="box box-primary">
		<div class="box-body">
			<div class="row">
				<div class="col-md-12">  
					<div class="box box-default collapsed-box">
						<div class="box-header with-border">
							<h3 class="box-title"><?=Yii::t('backend','Batch Sensitive Word');?></h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool"
									data-widget="collapse">
									<i class="fa fa-plus"></i>
								</button>
							</div>
							<!-- /.box-tools -->
						</div>
						<!-- /.box-header -->
						<?php $form = ActiveForm::begin([
							    		'id' => "form",
							            'enableAjaxValidation' => false,
							            'options' => ['enctype' => 'multipart/form-data'],]); 
							    ?>
						<div class="box-body" style="display: none;">
							<div class="form-group col-md-3">
								<label><?=Yii::t('backend','Upload Mode');?></label> 
								<select id="sec" name="sec" onchange="sensitive();"
									class="form-control">
									<option value=''><?=Yii::t('backend','Chose Upload Mode');?></option>
									<option value="excel"><?=Yii::t('backend','Bulk Upload');?></option>
									<option value="han"><?=Yii::t('backend','Manually Add');?></option>
									<option value="system"><?=Yii::t('backend','System Generation');?></option>
								</select>
							</div>
<div class="form-group col-md-3">
								<label><?=Yii::t('backend','The Account');?></label>                  
								<select class="form-control" onChange="change_add('')" id='sensitive_account'  name="sensitive_account" >
									<option value="">--<?=Yii::t('backend','Account Group');?>--</option>
					<?= backend\widgets\MenuLeft::widget([],3);?>
								</select>
							</div>
							<div class="form-group col-md-3">
								<label><?=Yii::t('backend','Sensitive Level');?></label> 
								<select name="sensitive_level_id" class="form-control" id='sensitive_level_id'>
								<option value=""><?=Yii::t('backend','Nodates');?></option>
								    <?php 
// 								    	if( !empty($level)){
// 											foreach($level as $key =>$v){
// 												echo '<option value='.$v['id'].'>'.$v['sensitive_name'].'</option>';
// 											}
// 										}
								    ?>
								</select>
							</div>
								<div class="form-group col-md-3">
								<label><?=Yii::t('backend','Sensitive Operate');?></label> 
								<select class="form-control" id='sensitive_action'  name="sensitive_action" >
									<option value=1><?=Yii::t('backend','Prohibit');?></option>
									<option value=2><?=Yii::t('backend','To Examine');?></option>
									<option value=3><?=Yii::t('backend','Replace');?></option>
								</select>
							</div>
							
							<div class="form-group col-md-9" id="excel_add"	style="display: none;">
								<fieldset>
<!-- 									<input type="file"  id='upload' name="excel"> -->
									<?= $form->field($model, 'file')->fileInput() ?>
								</fieldset>
								
							</div>
							
							<div class="form-group col-md-9" id="han_add"
								style="display: none;">
								<fieldset>
									<?=Yii::t('backend','Sensitive Word');?><b style="color: red">&nbsp;*&nbsp;</b>: <input type="text" class="form-control" id='sensitive_name'  name="sensitive_name"/> 
									<?=Yii::t('backend','Replace Content');?><b style="color: red">&nbsp;*&nbsp;</b>: <input type="text" class="form-control" id='sensitive_replace'  name="sensitive_replace"/>
								</fieldset>
							</div>
							<!--  
							<div class="form-group col-md-9" id="system"
								style="display: none;">
								<fieldset>
									默认路径: <input readonly="readonly" type="text" id="path" value="/focus/web/sensitive/sensitive.txt"  placeholder="/data/shell/dit.dic"	class="form-control" />
								</fieldset>
							</div>
							-->
							<button type="button" id="sensitive_add_manage"
								class="btn btn-block btn-info"></button>
						</div>
						<!-- /.box-body -->
						<?php ActiveForm::end(); ?>
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
							<h3 class="box-title"><?=Yii::t('backend','Edit Sensitive');?></h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool"
									data-widget="collapse">
									<i class="fa_edit fa fa-plus"></i>
								</button>
							</div>
							<!-- /.box-tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body box-body-edit">
						<div class="form-group col-md-3" style="display:none;">
								<label><?=Yii::t('backend','The Account');?></label> 
								
								<select class="form-control" disabled id='sensitive_account_m'  name="sensitive_account_m" >
									<option value="">--<?=Yii::t('backend','Account Group');?>--</option>
					<?= backend\widgets\MenuLeft::widget([],3);?>
								</select>
								
							</div>
							<div class="form-group col-md-3"  style="display: none;">
								<label><?=Yii::t('backend','Sensitive Level');?></label> 
								<select name="sensitive_level_id_m"  class="form-control" id='sensitive_level_id_m'>
								    <?php 
// 								    	if( !empty($level)){
// 											foreach($level as $key =>$v){
// 												echo '<option value='.$v['id'].'>'.$v['sensitive_name'].'</option>';
// 											}
// 										}
								    ?>
								</select>
							</div>
							<div class="form-group col-md-3">
								<label><?=Yii::t('backend','Sensitive Operate');?></label> 
								<select class="form-control" id='sensitive_action_m'  name="sensitive_action_m" >
									<option value=1><?=Yii::t('backend','Prohibit');?></option>
									<option value=2><?=Yii::t('backend','To Examine');?></option>
									<option value=3><?=Yii::t('backend','Replace');?></option>
								</select>
							</div>
							<div class="form-group col-md-3">
								<label><?=Yii::t('backend','Sensitive Word');?></label> 
								<input type="text" class="form-control" id='sensitive_name_m'/> 
							</div>
							
							<div class="form-group col-md-3">
								<label><?=Yii::t('backend','Replace Content');?></label> 
								<input type="text" class="form-control" id='sensitive_replace_m'/>
									<input type="hidden" id='sensitive_id_m' value=''>
							</div>
							
<!-- 							<div class="form-group col-md-9" id="han_add"	> -->
<!-- 								<fieldset> -->
<!-- 									敏感词: <input type="text" class="form-control" id='sensitive_name_m'/>  -->
<!-- 									替换内容: <input		type="text" class="form-control" id='sensitive_replace_m'/> -->
<!-- 									<input type="hidden" id='sensitive_id_m' value=''> -->
<!-- 								</fieldset> -->
<!-- 							</div> -->
							
							<button type="button" id="sensitive_edit_m"
								class="btn btn-block btn-info"></button>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</div>
			</div>
    <!-- 检索 -->
    <div class="row">
				<div class="col-md-12">
				<?php 
									if( $search_flg == true){
										$class1 = 'box box-default';
										$class2 = 'fa fa-minus';
									}else{
										$class1 = 'box box-default collapsed-box';
										$class2 = 'fa fa-plus';										
									}
									?>
					<div class="<?php echo $class1;?>">
						<div class="overlay" style="display: none;" id="search_loading">
							<i class="fa fa-refresh fa-spin"></i>
						</div>
						<div class="box-header with-border">
							<h3 class="box-title"><?=Yii::t('backend','Retrieval');?></h3>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse">
									<i class="<?php echo $class2;?>"></i>
								</button>
							</div>
							<!-- /.box-tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body" >
						<div class="form-group col-md-3">
								<label><?=Yii::t('backend','The Account');?></label> 
								<select class="form-control" onChange="change_search('')"  id='sensitive_account_s'  name="sensitive_account_s" >
									<option value=""><?=Yii::t('backend','The Account');?></option>
					<?= backend\widgets\MenuLeft::widget([],3);?>
								</select>
							</div>
							<div class="form-group col-md-3">
								<label><?=Yii::t('backend','Sensitive Level');?></label> 
								<select name="sensitive_level_id_s" class="form-control" id='sensitive_level_id_s'>
											<option value=''><?=Yii::t('backend','Sensitive Level');?></option>
											 <?php 
								    	if( !empty($level)){
											foreach($level as $key =>$v){
												if( $v['id'] == $sensitive_level_id){
													$check = 'selected';
												}else{
													$check = '';
												}
												echo '<option '.$check.' value='.$v['id'].'>'.$v['sensitive_name'].'</option>';
											}
										}
								    ?>
								</select>
							</div>
							<div class="form-group col-md-3">
								<label><?=Yii::t('backend','Sensitive Operate');?></label> 
								<select class="form-control" id="sensitive_action_s" name="sensitive_action_s">
									<option value=0><?=Yii::t('backend','Sensitive Operate');?></option>
									<option value=1 <?php echo $sensitive_action=='1'?'selected':'';?>><?=Yii::t('backend','Prohibit');?></option>
									<option value=2 <?php echo $sensitive_action=='2'?'selected':'';?>><?=Yii::t('backend','To Examine');?></option>
									<option value=3 <?php echo $sensitive_action=='3'?'selected':'';?>><?=Yii::t('backend','Replace');?></option>
								</select>
							</div>
							<div class="form-group col-md-3" id="han_add">
								<label><?=Yii::t('backend','Sensitive Word');?></label> 
									 <input type="text" class="form-control" id="sensitive_name_s" value="<?php echo $sensitive_name;?>"> 
							</div>
							
							<div class="form-group col-md-3" id="han_add">
								<label><?=Yii::t('backend','Operator');?></label> 
									 <input type="text" class="form-control" id="sensitive_operator" value="<?php echo $sensitive_operator;?>"> 
							</div>
							<button type="button" id="sensitive_search_name" class="btn btn-block btn-info"></button>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</div>
			</div>
    <!-- 检索结束 -->

		</div>
		<div class="box box-default" id='list'>
			<div class="box-header with-border">
				<h3 class="box-title"><?=Yii::t('backend','List Infor');?></h3>
			</div>
			<div class="box-body">
			
			<?= GridView::widget([
        'dataProvider' => $dataProvider,
    	'emptyText' =>Yii::t('backend','No Data'),
        'columns' => [
            'id',
			[
			'attribute' => 'sensitive_level_id',
			'label'=>Yii::t('backend','Sensitive Level'),
			'value'=>
			function ($model) {
return \backend\models\DwSensitiveLevel::get_level_redisname($model->sensitive_account,$model->sensitive_level_id);
			},
			],
			
            'sensitive_name',
			'sensitive_replace',
			[
			'attribute' => 'sensitive_action',
			'label'=>Yii::t('backend','Sensitive Do'),
			'content'=>
			function($model){
				$Prohibit = Yii::t('backend','Prohibit');
				$Examine = Yii::t('backend','To Examine');
				$Replace = Yii::t('backend','Replace');
				if($model->sensitive_action==1){
                     return '<span class="label pull-left bg-red">'.$Prohibit.'</span>' ;
				}elseif($model->sensitive_action==2){
					return '<span class="label pull-left bg-yellow">'.$Examine.'</span>' ;
				}elseif($model->sensitive_action==3){
					return '<span class="label pull-left bg-blue">'.$Replace.'</span>' ;
				}
			},
			],
			[
			'attribute' => 'sensitive_account',
			'label'=>Yii::t('backend','The Account'),
			'content'=>
			function($model){
				$msg = Yii::t('backend','No Account');
				$account = \backend\models\DwAuthAccount::getAccountById($model->sensitive_account);
				if( isset($account['name'])){
					return $account['name'];
				}else{
					return $msg;
				}
				},
			],
			[
			'attribute' => 'sensitive_operator',
			'label'=>Yii::t('backend','Operator'),
			'content'=>
			function($model){
				$msg = Yii::t('backend','No Account');
				$sensitive_operator = \backend\models\DwUser::get_user_redis_name($model->sensitive_operator);
				return $sensitive_operator;
			},
			],
			
			[
			'attribute' => 'sensitive_time',
			'label'=>Yii::t('backend','Operation Time'),
			'value'=>
			function($model){
				return  date('Y-m-d H:i',$model->sensitive_time);   //主要通过此种方式实现
			},
			],
            ['class' => 'yii\grid\ActionColumn',
			'header' => Yii::t('backend','Operation'),
			'template' => '{delete} {view}',
			'buttons' => [
				'view' => function ($url, $model) {
					$view = Yii::t('backend','Views');
					$account_flg = \backend\models\search\DwCommentSearch::getPowerByaccountid( $model->sensitive_account);
					
					if( $account_flg){
					return '<a  class="view_manage"  onclick="sensitive_view('.$model->id.');" href="javascript:void(0);" title="'.$view.'" aria-label="'.$view.'"><span class="glyphicon glyphicon-eye-open"></span></a>';
					}				
					},
				'delete' => function ($url, $model) {
					$delete = Yii::t('backend','Deletes');
					$account_flg = \backend\models\search\DwCommentSearch::getPowerByaccountid( $model->sensitive_account);
					if( $account_flg){
						return '<a  class="sensitive_del" data="'.$model->id.'" href="javascript:void(0);" title="'.$delete.'" aria-label="'.$delete.'"  ><span class="glyphicon glyphicon-trash"></span></a>';
					}
				}
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
AppAsset::addScript($this,'@web/static/js/sensitive.js');
?>
<?php
AdminAsset::register($this);
$js = <<<JS

$(function() {
	var sensitive_account = '$sensitive_account';
		$('#sensitive_account_s').val(sensitive_account);
});

JS;
$this->registerJs($js);
?>

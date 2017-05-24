<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use Qiniu\json_decode;
use yii\helpers\Url;
use yii\base\Widget;
use backend\assets\AppAsset;


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
<?php $chosegroup = Yii::t('backend','Please Select Account');?>
<?php $newadd = Yii::t('backend','Newadd');?>
<?php $surestop = Yii::t('backend','Sure Stop');?>
<?php $editdo = Yii::t('backend','Editdo');?>
<?php $oneselect = Yii::t('backend','One Select');?>
<?php $suredelete = Yii::t('backend','Sure Delete');?>
<?php $deletes = Yii::t('backend','Deletes');?>
<?php $tagsort = Yii::t('backend','Tag Sort');?>
<?php $mostfive = Yii::t('backend','Most Five');?>
<?php $choseformtype = Yii::t('backend','Chose Form Type');?>

<?php $columnnamenoempty = Yii::t('backend','Column Name No Empty');?>
<?php $pleaseselecttypegenus = Yii::t('backend','Please Select Type Genus');?>
<?php $fillinthetag = Yii::t('backend','Fill In The Tag');?>
<?php $pleaseselectlabeltype = Yii::t('backend','Please Select Label Type');?>
<?php $tagupperlimiterror = Yii::t('backend','Tag Upper Limit Error');?>
<?php $uploadtagupperlimitnoempty = Yii::t('backend','Upload Tag Upper Limit No Empty');?>
<?php $uploadtagupperlimitisnumber = Yii::t('backend','Upload Tag Upper Limit Is Number');?>
<?php $serverpathcannotnoempty = Yii::t('backend','Server Path Cannot No Empty');?>
<?php $pleaseselectpicturetype = Yii::t('backend','Please Select Picture Type');?>
<?php $prompttextcannotnoempty = Yii::t('backend','Prompt Text Cannot No Empty');?>
<?php $textlinenoempty = Yii::t('backend','Text Line No Empty');?>
<?php $textlinecanonlynumbers = Yii::t('backend','Text Line Can Only Numbers');?>

<script>
var chosegroup = '<?php echo $chosegroup;?>';
var newadd = '<?php echo $newadd;?>';
var surestop = '<?php echo $surestop;?>'; //您确定要停用此项吗
var editdo = '<?php echo $editdo;?>'; //编辑
var oneselect = '<?php echo $oneselect;?>'; //一次只能选择一项
var suredelete = '<?php echo $suredelete;?>'; //确认删除
var deletes = '<?php echo $deletes;?>'; //删除
var tagsort = '<?php echo $tagsort;?>';
var mostfive = '<?php echo $mostfive;?>';
var choseformtype = '<?php echo $choseformtype;?>'; //请选择表单类型

var columnnamenoempty = '<?php echo $columnnamenoempty;?>';
var pleaseselecttypegenus = '<?php echo $pleaseselecttypegenus;?>';
var fillinthetag = '<?php echo $fillinthetag;?>';
var pleaseselectlabeltype = '<?php echo $pleaseselectlabeltype;?>';
var tagupperlimiterror = '<?php echo $tagupperlimiterror;?>';
var uploadtagupperlimitnoempty = '<?php echo $uploadtagupperlimitnoempty;?>';
var uploadtagupperlimitisnumber = '<?php echo $uploadtagupperlimitisnumber;?>';
var serverpathcannotnoempty = '<?php echo $serverpathcannotnoempty;?>';
var pleaseselectpicturetype = '<?php echo $pleaseselectpicturetype;?>';
var prompttextcannotnoempty = '<?php echo $prompttextcannotnoempty;?>';
var textlinenoempty = '<?php echo $textlinenoempty;?>';
var textlinecanonlynumbers = '<?php echo $textlinecanonlynumbers;?>';

</script>
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
										<li class="active"><a href="<?php echo Url::toRoute(['comment/fourm', 'web_type' => 'item'])?>" ><?=Yii::t('backend','Form Set');?></a></li>
										<li ><a href="<?php echo Url::toRoute(['comment/fourm', 'web_type' => 'category'])?>" ><?=Yii::t('backend','Form Types');?></a></li>
										<!--  <li><a href="<?php echo Url::toRoute(['comment/fourm', 'web_type' => 'area'])?>" >使用区域</a></li>-->
										<li><a href="<?php echo Url::toRoute(['comment/fourm', 'web_type' => 'makeing'])?>" ><?=Yii::t('backend','Generation Manage');?></a></li>
									</ul>
									<div class="tab-content">
<!-- 表单设定 -->
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
											
	<div class="box box-default box-default-edit collapsed-box">
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
		<div class="box-body box-body-edit">
			<form class="form-horizontal" role="form">
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label"><?=Yii::t('backend','The Account');?><b style="color: red">&nbsp;*&nbsp;</b></label>
					<div class="col-sm-10">
						<select class=" form-control " name="fourm_item_account" id="fourm_item_account">
						<option value="">--<?=Yii::t('backend','Please Select');?>--</option>
						<?= backend\widgets\MenuLeft::widget();?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label"><?=Yii::t('backend','Names');?><b style="color: red">&nbsp;*&nbsp;</b></label>
					<div class="col-sm-10">
						<input type="text" name="fourm_item_title" class="form-control" value="" placeholder="<?=Yii::t('backend','Column Name');?>">
					</div>
				</div>
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label"><?=Yii::t('backend','Typegenus');?><b style="color: red">&nbsp;*&nbsp;</b></label>
					<div class="col-sm-5">
							<div class="radio">
								<label> <input type="radio" name="fourm_item_idtype" value="2" onclick="javascript:show_tags_btn(1);"> <?=Yii::t('backend','Pic');?>
								</label>&nbsp;&nbsp;&nbsp;&nbsp;
								<label> <input type="radio" name="fourm_item_idtype" value="1" onclick="javascript:show_tags_btn(2);"><?=Yii::t('backend','Words');?> 
								</label>&nbsp;&nbsp;&nbsp;&nbsp;
								<label> <input type="radio" name="fourm_item_idtype" value="3" onclick="javascript:show_tags_btn(3);"><?=Yii::t('backend','Tags');?> 
								</label>
							</div>
					</div>
				</div>
			<span style="display: none;" id="word">	
				<hr>
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label"><?=Yii::t('backend','Prompttext');?><b style="color: red">&nbsp;*&nbsp;</b></label>
					<div class="col-sm-10">
						<input type="text" class="form-control" value="" name="word_content_prompt"	pla();ceholder="<?=Yii::t('backend','Prompttext');?>">
					</div>
				</div>

				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label"><?=Yii::t('backend','Textceiling');?><b style="color: red">&nbsp;*&nbsp;</b></label>
					<div class="col-sm-10">
						<input type="text" name="word_content_online" class="form-control" value=""	placeholder="<?=Yii::t('backend','Textceiling');?>">
					</div>
				</div>
			</span>
			<span style="display: none;" id='img'>		
				<hr>
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label"><?=Yii::t('backend','Upload Pic Limit');?><b style="color: red">&nbsp;*&nbsp;</b></label>
					<div class="col-sm-10">
						<input type="text" name="img_content_online"  class="form-control" value="" placeholder="<?=Yii::t('backend','Upload Pic Limit');?> ...">
					</div>
				</div>
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label"><?=Yii::t('backend','Web Path');?><b style="color: red">&nbsp;*&nbsp;</b></label>
					<div class="col-sm-10">
						<input type="text" name="img_content_path" class="form-control" value=""	placeholder="<?=Yii::t('backend','Web Path');?>...">
					</div>
				</div>
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label"><?=Yii::t('backend','Pic Type');?><b style="color: red">&nbsp;*&nbsp;</b></label>
					<div class="col-sm-5">
							<div class="radio">
<!-- 							onclick="show_props(1)" -->
								<label> <input type="radio" name="img_content_type"	 value="1" ><?=Yii::t('backend','Sticker');?>
								</label>&nbsp;&nbsp;&nbsp;&nbsp;
								<label> <input type="radio" name="img_content_type"	 value="2"><?=Yii::t('backend','Emotion');?>
								</label>
							</div>
					</div>
				</div>
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label"></label>
					<div class="col-sm-10">
							<div class="checkbox_1" style="display: none;">
							<?php if( !empty($props_redis)){
								       foreach ($props_redis as $pr_k=>$pr_v){

echo '<label> <input type="checkbox" name="img_emotion_type1" value="'.$pr_k.'" >'.$pr_v.'</label>&nbsp;&nbsp;&nbsp;&nbsp;';
									   }
								   }
								
						    ?>
	
							</div>
							<div class="checkbox_2" style="display: none;">
							<?php if( !empty($emotion_redis)){
								       foreach ($emotion_redis as $em_k=>$em_v){

echo '<label> <input type="checkbox" name="img_emotion_type2" value="'.$em_k.'" >'.$em_v.'</label>&nbsp;&nbsp;&nbsp;&nbsp;';
									   }
								   }
								
						    ?>
	
							</div>
					</div>
				</div>
			</span>	
			
			<span style="display: none;" id='tag'>	
<div class="form-group">
  <label for="firstname" class="col-sm-2 control-label"><?=Yii::t('backend','Tag Type');?><b style="color: red">&nbsp;*&nbsp;</b></label>
  <div class="col-sm-5">
    <div class="radio">
      <label>
        <input name="fourm_item_tags_type" value="1" onclick="idtype_setting(1);" type="radio">
        <?=Yii::t('backend','Star Rating');?> </label>
      &nbsp;&nbsp;&nbsp;&nbsp;
      <label>
        <input name="fourm_item_tags_type" value="2" onclick="idtype_setting(2);" type="radio">
       <?=Yii::t('backend','Scoring');?> </label>
      &nbsp;&nbsp;&nbsp;&nbsp;
      <label>
        <input name="fourm_item_tags_type" value="3"  onclick="idtype_setting(3);" type="radio">
        <?=Yii::t('backend','Tag Score');?></label>
    </div>
  </div>
</div>	
				<hr>
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label"><?=Yii::t('backend','Tags Limit');?><b style="color: red">&nbsp;*&nbsp;</b></label>
					<div class="col-sm-10">
						<input type="text" class="form-control"  id="tag_content_online" name="tag_content_online"value=""		placeholder="<?=Yii::t('backend','Tags Limit');?> ...">
					</div>
				</div>
				<!-- 添加多个 tags start -->
				<span id="append_form">
					<div class="form-group tags_input" >
 					  <label class="col-sm-2 control-label" for="firstname"><small class="label pull-bottom bg-red"><span onclick="tag_del(this);" class="item_delete" style="cursor:pointer;"><?=Yii::t('backend','Deletes');?></span></small></label>
					  <div class="col-sm-5">
					    <input type="text" placeholder="<?=Yii::t('backend','Tag Sort');?>" value="" name="tags[]" class="form-control">&nbsp; 
					  </div>
					</div>
				</span>
				
				<!-- 添加多个tags end -->
			</span>	
				<hr>
				<div class="form-group">
					<label for="firstname" class="col-sm-2 control-label"><?=Yii::t('backend','Ifrequired');?><b style="color: red">&nbsp;*&nbsp;</b></label>
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
					   <input type="hidden" name="item_ext_delid" id ='item_ext_delid' value="">
						<button type="button" id="submit_button" class="btn btn-block btn-success"><?=Yii::t('backend','Newadd');?></button>
					</div>
					<div class="form-group col-md-3">
						<button type="button" id="submit_button_clear" class="btn btn-block label-danger"><?=Yii::t('backend','Empty');?></button>
					</div>
					<div class="form-group col-md-3">
						<button class="btn btn-block label-warning" id="add_tags" type="button" style="display:none;"><?=Yii::t('backend','Add Tag');?></button>
					</div>
					
		</div>
		<!-- /.表单设定 新建编辑  end-->
	</div>
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
			'contentOptions'=>['style'=>'width: 10%;'],
			'label'=>Yii::t('backend','Typegenus'),
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
				$prompttext = Yii::t('backend','Prompttext');
				$textceiling = Yii::t('backend','Textceiling');
				$picmax = Yii::t('backend','Pic Limit');
				$webpath = Yii::t('backend','Web Path');
				$sticker = Yii::t('backend','Sticker');
				$emotion = Yii::t('backend','Emotion');
				$nodefined = Yii::t('backend','Nodefined');
				$tagtype = Yii::t('backend','Tag Type');
				$starrating = Yii::t('backend','Star Rating');
				$scoring = Yii::t('backend','Scoring');
				$tagscore = Yii::t('backend','Tag Score');
				$tagslimit = Yii::t('backend','Tags Limit');
				$tagcombin = Yii::t('backend','Tagcombin');
				$fourm_item_content = json_decode($model->fourm_item_content,true);
				if( $model->fourm_item_idtype == 1){
					$str = ''.$prompttext.'：'	.$fourm_item_content['word_content_prompt'].'<br>';
					$str .= ''.$textceiling.'：'	.$fourm_item_content['word_content_online'].'<br>';
				}elseif($model->fourm_item_idtype == 2){
					$str = ''.$picmax.'：'	.$fourm_item_content['img_content_online'].'<br>';
					$str .= ''.$webpath.'：'	.$fourm_item_content['img_content_path'].'<br>';
					if($fourm_item_content['img_content_type'] == 1){
						$img = ''.$sticker.'：';
						$emotion_type = '';
						if( !empty($fourm_item_content['img_emotion_type'])){
							$redis = backend\models\DwPropsCategory::get_category_redis_all();
							foreach ( $fourm_item_content['img_emotion_type'] as $key => $v ){
								if( isset($redis[$v]  )){
									$emotion_type .= $redis[$v] . '，';
								}
							}
							$img .='<span class="label bg-purple" >'.$emotion_type."</span>";
						}
					}elseif($fourm_item_content['img_content_type'] == 2){
						$img = ''.$emotion.'：';
						$emotion_type = '';
						if( !empty($fourm_item_content['img_emotion_type'])){
							$redis = backend\models\DwemoticonCategory::getCate_redis();
							foreach ( $fourm_item_content['img_emotion_type'] as $key => $v ){
								if( isset($redis[$v]  )){
									$emotion_type .= $redis[$v] . '，';
								}
							}
							$img .='<span class="label bg-purple" >'.$emotion_type."</span>";
						}
					}else{
						$img = ''.$nodefined.'';
					}
					$str .= $img.'<br>';
				}elseif($model->fourm_item_idtype == 3){
					    if($model->fourm_item_tag_type == 1) {
$str = ''.$tagtype.'：<span class="label bg-navy" >'.$starrating.'</span><br>';
						}elseif($model->fourm_item_tag_type == 2) {
$str = ''.$tagtype.'：<span class="label bg-navy" >'.$scoring.'</span><br>';
						}elseif($model->fourm_item_tag_type == 3) {
$str = ''.$tagtype.'：<span class="label bg-navy" >'.$tagscore.'</span><br>';
						}else{
$str =''.$tagtype.'：<span class="label bg-navy" >'.$nodefined.'</span><br>';
}
						$str .= ''.$tagslimit.'：'	.$fourm_item_content['tag_content_online'].'<br>';
						$tag = backend\models\DwFourmCategoryItemExt::getTagRedisByid($model->id);
						//$tag = json_decode($model->fourm_item_tag,true);
						$str .= ''.$tagcombin.'：<span class="label bg-purple" >'.$tag.'</span>';
						
					
				}
				return $str;
			},
			],
			[
			'attribute' => 'fourm_item_account',
			'contentOptions'=>['style'=>'width: 10%;'],
			'label'=>Yii::t('backend','The Account'),
			'content'=>
			function($model){
				$noaccount = Yii::t('backend','No Account');
				$account = \backend\models\DwAuthAccount::getAccountById($model->fourm_item_account);
				if( isset($account['name'])){
					return $account['name'];
				}else{
					return $noaccount;
				}
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
						return '<span class="label label-success" >'.$yesdo.'</span>' ;
					}elseif($model->fourm_item_is_ver==1){
						return '<span class="label  bg-yellow">'.$nodo.'</span>' ;
					}
				},
			],
			[
			'attribute' => 'fourm_item_stats',
			'label'=>Yii::t('backend','States'),
			'content'=>
			function($model){
				$useful = Yii::t('backend','Useful');
				$unuseful = Yii::t('backend','Unuseful');
				if($model->fourm_item_stats==0){
					return '<span class="label label-success" >'.$useful.'</span>' ;
				}elseif($model->fourm_item_stats==1){
					return '<span class="label  bg-yellow">'.$unuseful.'</span>' ;
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
// 				'delete' => function ($url, $model) {
// 					return '<a data-pjax="0"  onclick="category_item_del('.$model->id.')" aria-label="停用" title="停用" href="javascript:;" class="btn btn-default btn-xs" ><i class="fa fa-ban"></i></a>';
// 				},
				
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

<?php 
AppAsset::addScript($this,'@web/static/js/comment.js');
?>
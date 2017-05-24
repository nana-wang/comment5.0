<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$showFace = <<<SHOWFACE
if(document.getElementById('emoji').style.display == 'block') {
	document.getElementById('emoji').style.display = 'none'
}else
	document.getElementById('emoji').style.display = 'block'
SHOWFACE;

?>
<script>
/********tab切换**********/
function selectTab() {
	var tabs=document.getElementById("tab").getElementsByTagName("li");
	var divs=document.getElementById("tab_con").getElementsByTagName("div");
	
	for(var i=0;i<tabs.length;i++){
		tabs[i].onclick=function(){changea(this);}
	}
	function changea(obj){
		for(var i=0;i<tabs.length;i++){
			if(tabs[i]==obj){
				tabs[i].className="fli";
				divs[i].className="fdiv";
			}
			else{
				tabs[i].className="";
				divs[i].className="";
			}
		}
	}
}
/********tab切换end**********/
function insertsmiley(icon) {
	document.getElementById('content').value+= ':'+icon+':';   
}
</script>
<style>
	.floor{border: 1;border-bottom:1px solid #D5D5D5;}
	#editor{margin-top: 20px;padding:0;margin:20px 0;width:100%;height:auto;border: 1;}
	#emoji{background-color:#D5D5D5;margin-top: 20px;padding:0;margin:10px 0;width:100%;height:150px;border: 1;display:none}
	img{margin:1px 1px 1px 1px}
	
	ul,li,div {padding:0;margin:0;}
	.tab ul li {float:left;width:100px;height:30px;line-height:30px;text-align:center;background-color:#fff;border:1px #bbb solid;border-bottom:none;}
	ul li.fli {background-color:#ccc;color:red;}
	ul {overflow:hidden;zoom:1;list-style-type:none;}
	#tab_con {width:100%;height:200px;}
	#tab_con div {width:100%;height:120px;display:none;border:1px #D5D5D5 solid;border-top:none;}
	#tab_con div.fdiv {display:block;background-color:#D5D5D5;}
</style>
<?php $form=ActiveForm::begin([
		'id'=>'upload',
		'enableAjaxValidation' => false
	]);
?>
	<?= $form->field($model,'text')->textarea(['rows'=>6,'id'=>'content','class'=>'col-sm-1 col-md-12','name' => 'content']);?>
	<?= Html::button('添加表情', ['class' => 'btn btn-success','onclick' => $showFace]) ?>
	<?=  Html::submitButton('提交', ['class'=>'btn btn-primary','name' =>'submit-button']) ?>
	<div class='tab' onclick = 'selectTab()'><?= Html::tag('div',$img,['id'=>'emoji','class'=>'col-sm-1 col-md-12','display' => 'none'])?></div>
	<div id='commentbox'>
		
	</div>
<?php ActiveForm::end();?>
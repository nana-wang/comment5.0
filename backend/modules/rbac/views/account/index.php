<?php
use yii\grid\GridView;
use yii\helpers\Html;
use mdm\admin\AdminAsset;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '账户群组管理 ';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title; ?>
<?php $this->endBlock() ?>
<div class="box box-primary">
			<div class="box-header with-border">
				<ul class="nav nav-pills">
						<li class="active"><a href="index.php?r=rbac%2Faccount%2Findex">
								账户列表 </a></li>
						<li><a href="index.php?r=rbac%2Faccount%2Fcreate">新建账户</a></li>
					</ul>
				<span class="label label-primary pull-right"></span>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
		<?= \backend\widgets\grid\TreeGrid::widget([
            'dataProvider' => $dataProvider,
            'keyColumnName' => 'id',
            'parentColumnName' => 'pid',
            'parentRootValue' => 0, //first parentId value
            'pluginOptions' => [
                'initialState' => '',
            ],
            'columns' => [
				[
				'attribute' => 'name',
				'label'=>'名称',
				'content'=>
					function($dataProvider){
						if( $dataProvider->stat == 1){
							// 账户被停用
							return  $dataProvider->name.'<span class="label  bg-red">账户停用</span>';
						}else{
							return $dataProvider->name;
						}
					}
					],
                [
                        'attribute' => '操作',
                        'content' => function($dataProvider){
                        $str =  Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['update', 'id' => $dataProvider->id], ['class' => 'btn btn-xs btn-default', 'data-toggle' => 'tooltip', 'title' => '编辑账户']).'&nbsp;&nbsp;';
                        if( $dataProvider->stat == 0){
                        // 停用
                        $str .=  '<a class= "btn btn-xs btn-default actstop" data-toggle = "tooltip", title = "停用账户" data-reload="' . $dataProvider->id.'" > <i class="glyphicon glyphicon-eye-close font-12" ></i></a>&nbsp;&nbsp;';
                        }else{
                        // 启用
                        $str .=  '<a class= "btn btn-xs btn-default actstart" data-toggle = "tooltip", title = "启用账户" data-reload="' . $dataProvider->id.'" > <i class="glyphicon glyphicon-eye-close font-12" ></i></a>&nbsp;&nbsp;';
                        }
                        $str .=  '<a class= "btn btn-xs btn-default actdel" data-toggle = "tooltip", title = "删除账户"  data-reload="' . $dataProvider->id .'" ><i class="glyphicon glyphicon-remove font-12" ></i></a>';
                       
                        return $str;
                         },
                ],
            ],
        ]); ?>					
			</div>
			<!-- /.box-body -->
			<!-- Loading (remove the following to stop the loading)-->
			<div class="overlay" id="show_loging" style="display: none;">
				<i class="fa fa-refresh fa-spin"></i>
			</div>
			<!-- end loading -->
		</div>
		
		
<?php
AdminAsset::register($this);
$js = <<<JS
 $('.actdel').bind('click',function(){
    var id =  $(this).attr('data-reload')
             if(id==''){
            alert('参数非法!');
            return false;
            }
		if(confirm("确认要删除？")){ 
 			$.ajax({
        	            type: "POST",
        	            url: "index.php?r=rbac%2Faccount%2Fdelete",
        	            data: {id:id},
        	            beforeSend: Listloading,
        	            success: Listloading_Authority
        	});

		} 
               
});
function Listloading(){
$("#show_loging").show();
}
function Listloading_Authority(data){
	$("#show_loging").hide();
	data = eval('(' + data + ')');
    alert(data.message);
    window.location.reload()
}

$('.actstop').bind('click',function(){
    var id =  $(this).attr('data-reload')
             if(id==''){
            alert('参数非法!');
            return false;
            }
		if(confirm("此操作可能会影响相关的权限问题，确认要停用账户吗？")){
                $.ajax({
        	            type: "POST",
        	            url: "index.php?r=rbac%2Faccount%2Fstop",
        	            data: {id:id},
        	            beforeSend: Listloading,
        	            success: Listloading_Authority
        	        });
		}
});
function Listloading(){
$("#show_loging").show();
}
function Listloading_Authority(data){
	$("#show_loging").hide();
	data = eval('(' + data + ')');
    alert(data.message);
    window.location.reload()
}
       
//账户启用

$('.actstart').bind('click',function(){
    var id =  $(this).attr('data-reload')
             if(id==''){
            alert('参数非法!');
            return false;
            }
		if(confirm("确认要启用账户吗？")){
                $.ajax({
        	            type: "POST",
        	            url: "index.php?r=rbac%2Faccount%2Fstart",
        	            data: {id:id},
        	            beforeSend: Listloading,
        	            success: Listloading_Authority
        	        });
		}
});
function Listloading(){
$("#show_loging").show();
}
function Listloading_Authority(data){
	$("#show_loging").hide();
	data = eval('(' + data + ')');
    alert(data.message);
    window.location.reload()
}


JS;
$this->registerJs($js);
?>
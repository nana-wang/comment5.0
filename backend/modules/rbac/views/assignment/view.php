<?php
use yii\helpers\Html;
use mdm\admin\AdminAsset;
use yii\helpers\Json;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model yii\web\IdentityInterface */

$this->title = Yii::t('rbac-admin', 'Assignments');
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
fieldset {
	padding: .35em .625em .75em;
	margin: 0 2px;
	border: 1px solid silver
}

legend {
	padding: .5em;
	border: 0;
	width: auto
}
</style>
<div class="row">
	<div class="col-sm-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<i class="fa fa-users"></i>
				<h5 class="box-title">
					<h3 class="box-title">管理员 [<?php echo Yii::$app->user->identity->username;?>] 为 [<?php echo $model->username;?>] 指派权限</h3>
				</h5>
				<span class="label label-primary pull-right"></span>
			</div>
			<ul class="nav nav-pills">
						<li><a href="index.php?r=user%2Findex"> <i class="glyphicon glyphicon-chevron-left font-12"></i> 用户列表
						</a></li>
					</ul>
			<!-- /.box-header -->
			<div class="box-body">
			
				<div class="assignment-index">
					<div class="row">
						<div class="form-group  col-md-5">
                    <?= Yii::t('rbac-admin', 'Avaliable') ?>:
                    <input id="search-avaliable" class="form-control"><br>
							<select id="list-avaliable" multiple size="10"
								class="form-control">
							</select>
						</div>
						<div class="form-group col-md-2 ">
							<div class="" style="margin-top: 70px;">
								<br> <br> <a href="#" id="btn-assign"
									class="btn btn-block btn-info btn-flat">&gt;&gt;</a>&nbsp;&nbsp;
								<a href="#" id="btn-revoke"
									class="btn btn-block btn-danger btn-flat">&lt;&lt;</a>
							</div>
						</div>
						<div class="form-group  col-md-5">
                    <?= Yii::t('rbac-admin', 'Assigned') ?>:
                    <input id="search-assigned" class="form-control"><br>
							<select id="list-assigned" multiple size="10"
								class="form-control">
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div id="presmission_data"></div>
							<!-- /.box -->
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
<?php
AdminAsset::register($this);
$properties = Json::htmlEncode(
        [
                'userId' => $model->{$idField},
                'assignUrl' => Url::to(
                        [
                                'assign'
                        ]),
                'searchUrl' => Url::to(
                        [
                                'search'
                        ])
        ]);
$js = <<<JS
yii.admin.initProperties({$properties});

$('#search-avaliable').keydown(function () {
    yii.admin.searchAssignmet('avaliable');
});
$('#search-assigned').keydown(function () {
    yii.admin.searchAssignmet('assigned');
});
$('#btn-assign').click(function () {
    yii.admin.assign('assign');
    return false;
});
$('#btn-revoke').click(function () {
    yii.admin.assign('revoke');
    return false;
});

yii.admin.searchAssignmet('avaliable', true);
yii.admin.searchAssignmet('assigned', true);

$('#btn-Authority').bind('click',function(){
    var v = $('#list-assigned').val();
    if( v == null){
             alert('选择分配好的角色才能指派权限小项!');
    return false;
    }else{
            $.ajax({
        	            type: "POST",
        	            url: "index.php?r=rbac/assignment/permission",
        	            data: {id:v},
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
    if(data.status=='ok'){
	       $('#presmission_data').html(data.data);
    }else if(data.status=='error'){
            alert(data.data);
    }
}
JS;
$this->registerJs($js);

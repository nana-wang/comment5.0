<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Json;
use mdm\admin\AdminAsset;
use mdm\admin\AutocompleteAsset;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
	<div class="col-sm-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<span class="label label-primary pull-right"></span>
				<ul class="nav nav-pills">
					<li><a href="index.php?r=rbac%2Fpermission%2Findex"> <i
							class="glyphicon glyphicon-chevron-left font-12"></i> 权限列表
					</a></li>
					<li class="active"><?= Html::a(Yii::t('rbac-admin', 'Create Permission'), ['create'], ['class' => ''])?></li>
				</ul>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<?php $form = ActiveForm::begin(); ?>
				   <?php
    if ($model->isNewRecord) {
        $action = 'getsubper(this,"sub");';
        $permission_id = '';
    } else {
        $permission_id = '<input type="hidden" name="permission_id" value="' .
                 $permission_id . '">';
        $action = 'getsubper(this,"sub");';
    }
    $array = array(
            'prompt' => '--请选择--',
            'encode' => false,
            'onchange' => $action,
            'id' => 'tipodecliente_lst',
            'options' => [
                    @$optionid => [
                            'Selected' => true
                    ]
            ]
    );
    echo $form->field($model, 'menuID')->dropDownList($menu, $array);
    echo '<div id="sub_select"></div>';
    echo '<div id="sub_select_options"></div>';
    echo $permission_id;
    ?>
				
                <?= $form->field($model, 'name')->textInput(['maxlength' => 64])?>
                <?= $form->field($model, 'description')->textarea(['rows' => 2])?>

    <div class="form-group">
        <?php
        echo Html::submitButton(
                $model->isNewRecord ? Yii::t('rbac-admin', 'Create') : Yii::t(
                        'rbac-admin', 'Update'), 
                [
                        'class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat'
                ])?>
    </div>

    <?php ActiveForm::end(); ?>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->
			<div class="overlay" id="show_loging" style="display: none;">
				<i class="fa fa-refresh fa-spin"></i>
			</div>
		</div>
		<!-- /.col -->
	</div>
</div>
<script>
function getsubper(obj,type){
    if( obj.value == null){
             alert('选择所属模块');
    return false;
    }else{
           $.ajax({
                        type: "POST",
            	            url: "index.php?r=rbac/permission/getpersub",
            	            data: {id:obj.value,type:type},
            	            beforeSend: Listloading,
            	            success: Listloading_Authority
            	        });
    }
}
function Listloading(){
$("#show_loging").show();
}
function Listloading_Authority(data){
	$("#show_loging").hide();
	data = eval('(' + data + ')');
    if(data.status=='ok'){
           if(data.type=='sub'){
       	        $('#sub_select').html(data.data);
          	     $('#sub_select_options').html('');
           }else if(data.type='options'){
        	         $('#sub_select_options').html(data.data);
            }
    }else if(data.status=='error'){
                alert(data.data);
       	        $('#sub_select').html('');
        	        $('#sub_select_options').html('');
    }
}
 </script>

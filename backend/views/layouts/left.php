<?php
use yii\base\Widget;
?>
<aside class="main-sidebar">
	<section class="sidebar">
		<div class="input-group  sidebar-form col-xs-11">
			<div class="box box-default">
				<div class="box-header with-border">
					<span class="" style="font-size: 14px;"> <b>正在操作</b>&nbsp;<i
						id="sub_account" style="font-size: 11px;"><?php echo \Yii::$app->user->identity->username;?></i>
					</span>
				</div>
				<div class="box-body">
					<select class=" form-control" name="account" id="account">
						<option value="">--请选择--</option>
				<?= backend\widgets\MenuLeft::widget();?>
			</select>
				</div>
				<!-- /.box-body -->
				<!-- Loading (remove the following to stop the loading)-->
				<div class="overlay" id="menu_loding" style="display: none;">
					<i class="fa fa-refresh fa-spin"></i>
				</div>
				<!-- end loading -->
			</div>

		</div>

		<!-- search form -->
		<div class="box_bak">
			<div class="box-body">
				<span id="menu_data"></span>
			</div>
			<!-- /.box_bak-body -->
			<!-- Loading (remove the following to stop the loading)-->
			<div class="overlay" id="menu_loading" style="display: none;">
				<i class="fa fa-spinner fa-spin"></i>
			</div>
			<!-- end loading -->
		</div>
		<!-- /.search form -->
	</section>
</aside>
<?php
$cookies = Yii::$app->request->cookies;
$cookie = $cookies->getValue('account_stat_id');
$url = '/' . Yii::$app->controller->getRoute();
?>
<script src="../js/jquery.js"></script>
<script>

$(function(){
	var cookie = '<?php echo $cookie;?>';
	var url = '<?= $url; ?>';
	if(cookie!=null){
		var account_id = <?php echo isset($cookie)?$cookie:0;?>;
		$('#sub_account').html($.trim($("#account").find("option:selected").text()));
		if(account_id==''){
			//alert('禁止操作!');
		return false;
		}else{
		$.ajax({
	        type: 'GET',
	        url: 'index.php?r=rbac%2Faccount%2Faccountmenu',
	        data:{'account_id':account_id,'redirect':url},
	        cache:false,    
		    dataType:'json',
	        beforeSend: loading,
	        success: Response,
	        error: function (XMLHttpRequest, textStatus, errorThrown) 
	        {
	         try 
	         {
	          //alert(XMLHttpRequest.responseText);
	          $('#menu_loding').hide();
	         }
	         catch (ex) { alert("Exception occured.. "); }
	         finally { }
	        } 
	    })
		}
		}
});

$('#account').change(function(){
	var account_id = this.value;
	var url = '<?= $url; ?>';
	$('#sub_account').html($.trim($("#account").find("option:selected").text()));
	if(account_id==''){
		alert('禁止操作!');
	}else{
	$.ajax({
        type: 'GET',
        url: 'index.php?r=rbac%2Faccount%2Faccountmenu',
        data:{'account_id':account_id,'redirect':url},
        cache:false,    
	    dataType:'json',
        beforeSend: loading,
        success: Response,
        error: function (XMLHttpRequest, textStatus, errorThrown) 
        {
         try 
         {
          //alert(XMLHttpRequest.responseText);
          $('#menu_loding').hide();
         }
         catch (ex) { alert("Exception occured.. "); }
         finally { }
        } 
    })
	}
});
function loading() {
    $('#menu_loding').show();
}
function Response(data) {
	if(data.status=='ok'){
		   $('#menu_loding').hide();
		   $('#menu_data').html(data.data);
	    //$('#show_data').html(data.data);
		}else{
			//$('#show_data').html('没有数据');	
		}
}


</script>



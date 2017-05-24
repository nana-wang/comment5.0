<?php 
// if( !empty($item)){
// 	foreach ($item as $k_category_item => $v_category_item){
// 		$T = \backend\models\DwFourmCategoryItem::get_fourm_item_idtype($v_category_item['fourm_item_idtype']);
// 		$check_flg = '';
// 		if( !empty($check_val)){
// 			$checked = explode(",",$check_val); //字符分割
// 			foreach ( $checked as $ck => $cv){
// 				if( $v_category_item['id'] == $cv){
// 					$check_flg = 'checked';
// 				}
// 			}
// 		}
// 		echo '<label> <input type="checkbox" '.$check_flg.' name="fourm_idtype_id[]" value="'.$v_category_item['id'].'" >'.$v_category_item['fourm_item_title'].'(<b>'.$T.'</b>)</label>&nbsp;&nbsp;';
// 	}
// }else{
// 	echo '暂无表单数据,请先进行表单设定';
// }



?>
<div class="assignment-index">
	<div class="row">
		<div class="form-group  col-md-5">
			<select id="list-avaliable" multiple="" size="10" class="form-control">
			<?php 
			$check_val_arr = '';
			if( !empty($item)){
				foreach ($item as $k_category_item => $v_category_item){
					$check_val_arr[$v_category_item['id']] = $v_category_item['fourm_item_title'];
	
					if( !empty($check_val)){
						if( in_array($v_category_item['id'],$check_val)	){
							continue;
						}				
					}
					echo '<option value="'.$v_category_item['id'].'">'.$v_category_item['fourm_item_title'].'</option>';
				}
			}
			?>
			</select>
		</div>
		<div class="form-group col-md-1 ">
			<div class="" style="margin-top: 10px;">
			    <a href="javascript:;" onclick="account_item_yidong('list-avaliable','list-assigned');" id="btn-assign" class="btn btn-block btn-info btn-flat">&gt;&gt;</a>
				<a href="javascript:;" onclick="account_item_yidong('list-assigned','list-avaliable');" id="btn-revoke" class="btn btn-block btn-danger btn-flat">&lt;&lt;</a>
			    <a href="javascript:;" onclick="account_item_up();" id="btn-up" class="btn btn-block btn-info btn-flat">&#8593;</a>
				<a href="javascript:;" onclick="account_item_down();" id="btn-down" class="btn btn-block btn-danger btn-flat">&#8595;</a>
			
			</div>
		</div>
		<div class="form-group  col-md-5">
			<select id="list-assigned" multiple="" size="10" class="form-control">
			<?php  
				if( !empty( $check_val_arr) && !empty($check_val)){
					foreach ($check_val as $c_k => $c_v){
						echo '<option value="'.$c_v.'">'.$check_val_arr[$c_v].'</option>';
					}
				}
				
			?>
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
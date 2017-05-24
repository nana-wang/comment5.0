<?php
$noform = Yii::t('backend','No Form');
if( !empty($category)){
	foreach ($category as $k_category_item => $v_category_item){
		echo '<option value="'.$v_category_item['id'].'">'.$v_category_item['fourm_title'].'</option>';
	}
}else{
	echo '<option value="">--'.$noform.'--</option>';
	
}
?>
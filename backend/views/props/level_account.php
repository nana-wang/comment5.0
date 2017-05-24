
<?php
	$nodates = Yii::t('backend','Nodates'); 
	if( !empty($account)){
		foreach($account as $key =>$v){
			if( $v['id'] == $check_val){
				$check = 'selected';
			}else{
				$check = '';
			}
			echo '<option '.$check.' value='.$v['id'].'>'.$v['props_category_name'].'</option>';
		}
	}else{
		echo '<option value="">'.$nodates.'</option>';
    }
?>

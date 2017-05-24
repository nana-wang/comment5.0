<?php
	$level = Yii::t('backend','Sensitive Level'); 
	$datas = Yii::t('backend','Nodates'); 
	if( !empty($account)){
		Yii::t('backend','Grade Set');
		echo '<option value="">'.$level.'</option>';
		foreach($account as $key =>$v){
			if( $v['id'] == $check_val){
				$check = 'selected';
			}else{
				$check = '';
			}
			echo '<option '.$check.' value='.$v['id'].'>'.$v['sensitive_name'].'</option>';
		}
	}else{
		echo '<option value="">'.$datas.'</option>';
    }
?>

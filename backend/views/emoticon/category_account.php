    <?php
    	$emotionclass = Yii::t('backend','Emotion Class'); 
    	$nodates = Yii::t('backend','Nodates'); 
    	if( !empty($account)){
    		echo '<option value="">'.$emotionclass.'</option>';
    		foreach($account as $key =>$v){
				if( $key== $check_val){
					$check = 'selected';
				}else{
					$check = '';
				}
				echo '<option '.$check.' value='.$key.'>'.$v.'</option>';
			}
		}else{
			echo '<option value="">'.$nodates.'</option>';
	    }
    ?>

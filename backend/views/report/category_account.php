    <?php 
    	if( !empty($account)){
    		echo '<option value="">举报分类</option>';
    		foreach($account as $key =>$v){
				if( $v['id'] == $check_val){
					$check = 'selected';
				}else{
					$check = '';
				}
				echo '<option '.$check.' value='.$v['id'].'>'.$v['report_type_title'].'</option>';
			}
		}else{
			echo '<option value="">无可用数据</option>';
	    }
    ?>

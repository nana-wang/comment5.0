<style>
.form-control {
    font-size: 14px;
    line-height: 1.42857143;
    display: block;
    width: 50%;
    height: 34px;
    padding: 6px 12px;
    -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    color: #555;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #fff;
    background-image: none;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
}
input, button, select, textarea {
    font-family: inherit;
    font-size: inherit;
    line-height: inherit;
}
textarea {
    overflow: auto;
}
button, input, optgroup, select, textarea {
    font: inherit;
    margin: 0;
    color: inherit;
    }
</style>
<select class="form-control" id='type'>
<?php 
if( !empty( $report_type)){
	foreach ($report_type as $key => $v){
		echo '<option value="'.$key.'">'.$v.'</option>';
	}
}
?>
<br/><br/>
<textarea id="commentform-report" class="form-control" name="CommentForm[comment]" rows="3"></textarea>
<input type="button" class="btn btn-success btn-flat" value="提交" onclick="doReport();">

<script type="text/javascript">
function doReport(){
	var content = $("#commentform-report").val();
	var type = document.getElementById('type').value;
	var report_comment_id='<?php echo $id;?>';
	if( content != '' && type != ''){
		$.ajax({
	        type: "POST",
	        url: "index.php?r=test/comment_report",
	        data: {'report_idtype':type,'report_content':content,'report_comment_id':report_comment_id},
	        success: function(data){
	        	data = eval('(' + data + ')'); //转为json对象
	        	if( data.flg == true){
	        		alert(data.data);
	        		window.close();
		        }else{
		        	alert(data.data);
		        }
		    }
	    })
	}else{
		alert('请填写举报内容或举报内容');
	}
}
</script>
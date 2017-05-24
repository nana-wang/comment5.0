
function show_add_loading(){
	$('#add_loading').show();
}


function list_loading(){
	$('#show_loading').show();
}

/*
 *提交
 */
$(function(){
	$("#button").click(function(){
		var class_val = $("#dwpropscategory-comment_channel_area option:selected").val();
		if(class_val == ''){
			//账户分组不能为空
			alert(accountgroupempty);
		}else{
			$("#w0").submit();
		}
	});
})

/*
 * 提交
 */
$(function(){
	$("#addbutton").click(function(){
		var account_val = $("#dwprops-props_account_id option:selected").val();
		var category_val = $("#dwprops-props_category_id option:selected").val();
		if(account_val == ''){
			//账户分组不能为空
			alert(accountnoempty);
		}else{
			if(category_val == ''){
				alert(propclassnoempty);
			}else{
				$("#upload-form").submit();
			}
		}
	});
})

// 道具分类查看
function props_category_view(id){
		
		$.ajax({
            type: "POST",
            url: "index.php?r=props/category_view",
            data: {
                'id': id
            },
            beforeSend: show_add_loading,
            success: view_success
        });
}
function view_success(data){
	$('#add_loading').hide();
	data = eval('(' + data + ')');
	if(data.flg == true ){
		$('#dwpropscategory-props_category_name').val(data.data.props_category_name);
		$('#dwpropscategory-id').val(data.data.id);
		$('#dwpropscategory-comment_channel_area').val(data.data.props_account_id);
		$('#dwpropscategory-comment_channel_area').attr('disabled','true');
		$('#button').text(editdo);
		 $('.box-default').removeClass("collapsed-box");
        $('.box-body').css('display','block');
	}else{
		alert(data.data);
	}
}

// 道具分类删除
function props_category_del(id){
	if(confirm(sure_del)){
		$.ajax({
            type: "POST",
            url: "index.php?r=props/category_del",
            data: {
                'id': id
            },
            beforeSend: list_loading,
            success: del_success
        });
	}
}

function del_success(data){
	$('#show_loading').hide();
	data = eval('(' + data + ')');
	if(data.flg == true ){
		alert(data.data);
		location.reload();  
	}else{
		alert(data.data);
	}	
}


// 道具查看
function props_view(id){
		$('#edit_loading').show();
		$.ajax({
            type: "POST",
            url: "index.php?r=props/view",
            data: {
                'id': id
            },
            success: propsview_success
        });
}

function propsview_success(data){
	$('#edit_loading').hide();
	data = eval('(' + data + ')');
	if(data.flg == true ){
		$('#upload-form3 select[id=dwprops-props_available]').val(data.data.props_available);
		$('#upload-form3 select[id=dwprops-props_category_id]').val(data.data.props_category_id);
		$('#upload-form3 input[id=dwprops-props_name]').val(data.data.props_name);
		$('#upload-form3 input[id=dwprops-props_description]').val(data.data.props_description);
		$('#upload-form3 input[id=dwprops-props_credit]').val(data.data.props_credit);
		$('#upload-form3 input[id=dwpropscategory-id]').val(data.data.id);
		$('.box-default-edit').removeClass("collapsed-box");
        $('.box-body-edit').css('display','block');
	}else{
		alert(data.data);
	}
}


// 道具删除
function props_del(id){
	if(confirm(sure_del)){	
		$('#show_loading').show();
		$.ajax({
            type: "POST",
            url: "index.php?r=props/props_del",
            data: {
                'id': id
            },
            success: propsdel_success
        });
	}
}

function propsdel_success(data){
	$('#show_loading').hide();
	data = eval('(' + data + ')');
	if(data.flg == true ){
		alert(data.data);
		location.reload();  
	}else{
		alert(data.data);
	}	
}

// 分类根据选择的账户联动
function change_add(change_area,check_val){
    var account = $("#dwprops-props_account_id").val();
 	 $("#dwprops-props_category_id").load("index.php?r=props/level_account&account_id="+account+'&change_area='+change_area+'&check_val='+check_val);
}
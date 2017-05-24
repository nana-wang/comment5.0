
function show_add_loading(){
	$('#add_loading').show();
}

function list_loading(){
	$('#show_loading').show();
}
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
		$('#button').text('编辑');
	}else{
		alert(data.data);
	}
}

// 道具分类删除
function props_category_del(id){
		
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
		
	}else{
		alert(data.data);
	}
}


// 道具删除
function props_del(id){
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

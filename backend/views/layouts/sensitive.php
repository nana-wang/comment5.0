
$(function() {
    //敏感词等级查看
    $(".view").click(function() {
        var id = $(this).attr('data');
        $.ajax({
            type: "POST",
            url: "index.php?r=sensitive/level_view",
            data: {
                'id': id
            },
            beforeSend: sensitiveloading_view,
            success: sensitiveresponse_view
        })
    });
    //敏感词等级删除
    $(".view_del").click(function() {
        if(confirm("您确定要删除此项吗？")){
	        var id = $(this).attr('data');
	        $.ajax({
	            type: "POST",
	            url: "index.php?r=sensitive/level_del",
	            data: {
	                'id': id
	            },
	            beforeSend: sensitiveloading_edit,
	            success: response_view_del
	        })
        }
    });
    
    // 敏感词删除
    $(".sensitive_del").click(function() {
        if(confirm("您确定要删除此项吗？")){
	        var id = $(this).attr('data');
	        $.ajax({
	            type: "POST",
	            url: "index.php?r=sensitive/sensitive_del",
	            data: {
	                'id': id
	            },
	            beforeSend: listLoading,
	            success: listLoading_del
	        })
        }
    });
});


//敏感词等级完成编辑
$(function() {
    $('#sensitive_edit').text(sensitive_edit_name);
    $("#sensitive_edit").click(function() {
        var name = $('#view_name').val();
        var description = $('#view_description').val();
        var id = $('#view_id').val();
        if( name=='' || description==''  ){
        	alert('内容不能为空，请填写相应的内容');
        	return false;
        }
        $(this).attr('disabled', 'disabled');
        $(this).text('处理中.....');
        $.ajax({
            type: "POST",
            url: "index.php?r=sensitive/level_edit",
            data: {
                'name': name,'description':description,'id':id
            },
            beforeSend: sensitiveloading_edit,
            success: sensitiveresponse_edit
        });
    });
});
function sensitiveloading_edit() {
    $("#show_loading").show();
}
function sensitiveresponse_edit(data) {
    var e = $('#sensitive_edit');
    e.text(sensitive_edit_name);
    e.attr('disabled', false);
     $("#show_loading").hide();
    data = eval('(' + data + ')'); 
    if( data.flg == true){
    	alert(data.data);
    	location.reload();
    }else{
    	alert(data.data);
    }
    
   
}

// 敏感词等级删除后处理
function response_view_del(data){
	data = eval('(' + data + ')'); 
    if( data.flg == false){
    	alert(data.data);
    }else{
	    alert(data.data);
	    location.reload();
    }
     $("#show_loading").hide();
}
 //敏感词等级查看后处理
function sensitiveloading_view() {
    $("#edit_loading").show();
}
function sensitiveresponse_view(data) {
    data = eval('(' + data + ')'); 
    if( data.flg == true){
    	$('#view_name').val(data.data.sensitive_name);
    	$('#view_description').val(data.data.sensitive_description);
    	$('#view_id').val(data.data.id);
    }else{
    	alert(data.data);
    }
    var e = $('#sensitive_edit');
    e.attr('disabled', false);
    $("#edit_loading").hide();
}
//敏感词等级添加
$(function() {
    $('#sensitive_add').text(sensitive_add_name);
    $("#sensitive_add").click(function() {
        var name = $('#name').val();
        var description = $('#description').val();
        if( name == '' || description==''){
        	alert('‘名称’或者‘说明’不能为空');
        	return false;
        }
        $(this).attr('disabled', 'disabled');
        $(this).text('处理中.....');
        $.ajax({
            type: "POST",
            url: "index.php?r=sensitive/level_add",
            data: {
                'name': name,'description':description
            },
            beforeSend: sensitiveloading_add,
            success: sensitiveresponse_add
        });
    });
});
function sensitiveloading_add() {
    $("#show_loading").show();
    
}
function sensitiveresponse_add(data) {
    var e = $('#sensitive_add');
     e.text(sensitive_add_name);
     e.attr('disabled', false);
    $("#show_loading").hide();
    data = eval('(' + data + ')'); 
    if( data.flg == true){
    	alert(data.data);
	    location.reload();
    }else{
    	alert(data.data);
    }
}


//敏感词添加
$(function() {
    $('#sensitive_add_manage').text(sensitive_add_name);
    $("#sensitive_add_manage").click(function() {
        var type = $('#sec').val();
        var sensitive_level_id = $('#sensitive_level_id').val();
        var sensitive_action = $('#sensitive_action').val();
        if( type == 'excel'){
        	var upload = $('#dwsensitive-file').val();
        	if( upload == ''){
        		alert('请上传扩展名为xls格式的excel文件');
        		return false;
        	}
        	var excel_name1=upload.lastIndexOf(".");  
        	var excel_name2=upload.length;
            var excel_name=upload.substring(excel_name1,excel_name2);//后缀名  
			if( excel_name != '.xls'){
        		alert('上传文件类型不正确，请使用微软excel编辑,扩展名为xls格式，并保存为完全兼容模式');
        		return false;
        	}
        	$(this).attr('disabled', 'disabled');
            $(this).text('处理中.....');
            $("#show_loading").show();
        	$("#form").attr('action','index.php?r=sensitive/add');
        	$("#form").submit();
        	return false;
        	//var data = {'type':type,'sensitive_level_id':sensitive_level_id,'sensitive_action':sensitive_action}
        }else if(type == 'han'){
       		var sensitive_name = $('#sensitive_name').val();
        	if( sensitive_name == ''){
        		alert('请填写敏感词');
        		return false;
        	}
       		var sensitive_replace = $('#sensitive_replace').val();
<!--         	if( sensitive_replace == ''){ -->
<!--         		alert('请填写敏感词替换值'); -->
<!--         		return false; -->
<!--         	} -->
       		var data = {'type':type,'sensitive_level_id':sensitive_level_id,'sensitive_action':sensitive_action,'sensitive_name':sensitive_name,'sensitive_replace':sensitive_replace}
        }else if(type == 'system'){
        	var path = $('#path').val();
        	if( path == ''){
        		alert('请填写词典生成位置');
        		return false;
        	}
       		var data = {'type':type,'path':path}
        
        }else{
        	alert('请选择上传方式');
        	return false;
        }
        
        $(this).attr('disabled', 'disabled');
        $(this).text('处理中.....');
        $.ajax({
            type: "POST",
            url: "index.php?r=sensitive/add",
            data: data,
            dataType: 'json', 
            beforeSend: loading_add,
            success: response_add
        });
    });
});
function loading_add() {
    $("#show_loading").show();
}
function response_add(data) {
   
    var e = $('#sensitive_add_manage');
     e.text(sensitive_add_name);
     e.attr('disabled', false);
     $("#show_loading").hide();
     if(data.flg == true ){
    	 var type = $('#sec').val();
    	 alert(data.data);
    	 if( type == 'system'){
    	  	$.getJSON({
	            type:"get",
	            url: data.url,
	            dataType:"jsonp",
	            error:function(XMLHttpRequest, textStatus, errorThrown) {
				}
        	});
    	 }else{
    	 	location.reload();
    	 }
    	   
     }else{
    	alert(data.data);
    }
}


//敏感词查看
function sensitive_view(id){
        $.ajax({
            type: "POST",
            url: "index.php?r=sensitive/sensitive_view",
            data: {
                'id': id
            },
            beforeSend: load_sensitive_view,
            success: response_view_manage
        });
}
function load_sensitive_view() {
    $("#edit_loading").show();
}
function response_view_manage(data) {
    data = eval('(' + data + ')'); 
    if( data.flg == true){
    	$("#sensitive_level_id_m").val(data.data.sensitive_level_id);
    	$('#sensitive_action_m').val(data.data.sensitive_action);
    	$('#sensitive_name_m').val(data.data.sensitive_name);
    	$('#sensitive_replace_m').val(data.data.sensitive_replace);
    	$('#sensitive_id_m').val(data.data.id);
    }else{
    	alert(data.data);
    }
    var e = $('#sensitive_edit');
    e.attr('disabled', false);
    $("#edit_loading").hide();
}

//完成敏感词编辑
$(function() {
    $('#sensitive_edit_m').text(sensitive_edit_name);
    $("#sensitive_edit_m").click(function() {
        var sensitive_name = $('#sensitive_name_m').val();
        var sensitive_replace = $('#sensitive_replace_m').val();
        var sensitive_level_id = $('#sensitive_level_id_m').val();
        var sensitive_action = $('#sensitive_action_m').val();
        var id = $('#sensitive_id_m').val();
        if( sensitive_name==''  || sensitive_level_id=='' || sensitive_action=='' ){
        	alert('内容不能为空，请填写相应的内容');
        	return false;
        }
        $(this).attr('disabled', 'disabled');
        $(this).text('处理中.....');
        $.ajax({
            type: "POST",
            url: "index.php?r=sensitive/sensitive_edit",
            data: {
                'id': id,'sensitive_name':sensitive_name,'sensitive_replace':sensitive_replace,'sensitive_level_id':sensitive_level_id,'sensitive_action':sensitive_action
            },
            beforeSend: loading_edit,
            success: response_edit
        });
    });
});
function loading_edit() {
    $("#show_loading").show();
}
function response_edit(data) {
    var e = $('#sensitive_edit_m');
    e.text(sensitive_edit_name);
    e.attr('disabled', false);
     $("#show_loading").hide();
    data = eval('(' + data + ')'); 
    if( data.flg == true){
    	alert(data.data);
    	location.reload();  
    }else{
    	alert(data.data);
    }
}
// 敏感词删除后处理
function listLoading(){
     $("#show_loading").show();
}
function listLoading_del(data){
	$("#show_loading").hide();
	data = eval('(' + data + ')'); 
    if( data.flg == false){
    	alert(data.data);
    }else{
    	alert(data.data);
    	location.reload(); 
    }
     
}


// 敏感词检索
$(function() {
    $('#sensitive_search_name').text(sensitive_search_name);
    $("#sensitive_search_name").click(function() {
        var sensitive_name = $('#sensitive_name_s').val();
        var sensitive_level_id = $('#sensitive_level_id_s').val();
        var sensitive_action = $('#sensitive_action_s').val();
        var sensitive_operator = $('#sensitive_operator').val();
        $("#show_loading").show();
         window.location.href='index.php?r=sensitive/manage&sensitive_name='+sensitive_name+'&sensitive_level_id='+sensitive_level_id+'&sensitive_action='+sensitive_action+'&sensitive_operator='+sensitive_operator; 
<!--          $("#list").load("index.php?r=sensitive/sensitive_search", {'sensitive_name':sensitive_name,'sensitive_level_id':sensitive_level_id,'sensitive_action':sensitive_action}, function(){ -->
<!-- 		    $("#show_loading").hide(); -->
<!-- 		 }); -->
       
    });
    
    //下拉框初始化
    sensitive();
});

function sensitive(){
	if($('#sec').val()=='excel'){
		    $('#system').hide();   
		    $('#excel_add').show(); 
		    $('#han_add').hide();   
	 }else if($('#sec').val()=='han'){
		    $('#system').hide();   
		    $('#excel_add').hide(); 
		    $('#han_add').show();   
	 }else if($('#sec').val()=='system'){
		    $('#system').show();   
		    $('#excel_add').hide(); 
		    $('#han_add').hide();   
		 }
}


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
        if(confirm(suredelete)){
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
        if(confirm(suredelete)){
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
        	alert(comnotempty);
        	return false;
        }
        $(this).attr('disabled', 'disabled');
        $(this).text(doing);
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
    
    edit_open();// 自动展开
    $("#edit_loading").hide();
}
//敏感词等级添加
$(function() {
    $('#sensitive_add').text(sensitive_add_name);
    $("#sensitive_add").click(function() {
        var name = $('#name').val();
        var description = $('#description').val();
        var account_id = $('#level_account').val();
        if( name == '' ){
        	alert(namempty);
        	return false;
        }
        if( description=='' ){
        	alert(expempty);
        	return false;
        }
        if(  account_id == ''){
        	alert(accempty);
        	return false;
        }
        $(this).attr('disabled', 'disabled');
        $(this).text(doing);
        $.ajax({
            type: "POST",
            url: "index.php?r=sensitive/level_add",
            data: {
                'name': name,'description':description,'account_id':account_id
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
        var sensitive_account = $('#sensitive_account').val();
        if( type == 'excel'){
        	var upload = $('#dwsensitive-file').val();
        	if( upload == ''){
        		alert(uploadex);
        		return false;
        	}
        	if( sensitive_account == ''){
        		alert(pleselect);
        		return false;
        	}
        	if( sensitive_level_id == ''){
        		alert(Sensitive_Level_empty);
        		return false;
        	}
        	var excel_name1=upload.lastIndexOf(".");  
        	var excel_name2=upload.length;
            var excel_name=upload.substring(excel_name1,excel_name2);//后缀名  
			if( excel_name != '.xls'){
        		alert(typenot);
        		
        		return false;
        	}
			
        	$(this).attr('disabled', 'disabled');
            $(this).text(doing);
            $("#show_loading").show();
        	$("#form").attr('action','index.php?r=sensitive/add');
        	$("#form").submit();
        	return false;
        	//var data = {'type':type,'sensitive_level_id':sensitive_level_id,'sensitive_action':sensitive_action}
        }else if(type == 'han'){
       		var sensitive_name = $('#sensitive_name').val();
        	if( sensitive_name == ''){
        		alert(fillsensitive);
        		return false;
        	}
        	if( sensitive_account == ''){
        		alert(pleselect);
        		return false;
        	}
        	if( sensitive_level_id == ''){
        		alert(Sensitive_Level_empty);
        		return false;
        	}
       		var sensitive_replace = $('#sensitive_replace').val();
<!--         	if( sensitive_replace == ''){ -->
<!--         		alert(fillvalue); -->
<!--         		return false; -->
<!--         	} -->
       		var data = {'type':type,'sensitive_level_id':sensitive_level_id,'sensitive_action':sensitive_action,'sensitive_name':sensitive_name,'sensitive_replace':sensitive_replace,'sensitive_account':sensitive_account}
        }else if(type == 'system'){
        	var path = $('#path').val();
        	if( path == ''){
        		alert(filldictionary);
        		return false;
        	}
       		var data = {'type':type,'path':path}
        
        }else{
        	alert(chosemeth);
        	return false;
        }
        
        $(this).attr('disabled', 'disabled');
        $(this).text(doing);
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
    	 //alert(data.data);
    	 if( type == 'system'){
    	  	$.getJSON({
	            type:"get",
	            url: data.url,
	            dataType:"jsonp",
	            error:function(XMLHttpRequest, textStatus, errorThrown) {
				   alert('ERROR');
	            },
    	  	    success:function(){
    	  	    	alert(data.data);
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
    	$('#sensitive_action_m').val(data.data.sensitive_action);
    	$('#sensitive_name_m').val(data.data.sensitive_name);
    	$('#sensitive_replace_m').val(data.data.sensitive_replace);
    	$('#sensitive_id_m').val(data.data.id);
    	$('#sensitive_account_m').val(data.data.sensitive_account);
    	//$("#sensitive_level_id_m").val(data.data.sensitive_level_id);
    	//change_edit('sensitive_level_id_m',data.data.sensitive_level_id);
    }else{
    	alert(data.data);
    }
    var e = $('#sensitive_edit');
    e.attr('disabled', false);
    edit_open();// 自动展开
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
        var sensitive_account = $('#sensitive_account_m').val();
        var id = $('#sensitive_id_m').val();
        
        if( sensitive_name==''  || sensitive_level_id=='' || sensitive_action=='' || sensitive_account=='' ){
        	alert(comnotempty);
        	return false;
        }
        $(this).attr('disabled', 'disabled');
        $(this).text(doing);
        $.ajax({
            type: "POST",
            url: "index.php?r=sensitive/sensitive_edit",
            data: {
                'id': id,'sensitive_name':sensitive_name,'sensitive_replace':sensitive_replace,'sensitive_level_id':sensitive_level_id,'sensitive_action':sensitive_action,'sensitive_account':sensitive_account
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
        var sensitive_account = $('#sensitive_account_s').val();
        $("#show_loading").show();
         window.location.href='index.php?r=sensitive/manage&sensitive_name='+sensitive_name+'&sensitive_level_id='+sensitive_level_id+'&sensitive_action='+sensitive_action+'&sensitive_operator='+sensitive_operator+'&sensitive_account='+sensitive_account; 
       
    });
    
    //下拉框初始化
    sensitive();
    // 敏感词添加默认等级
    change_add('');
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

function change_search(check_val){
    var account = $("#sensitive_account_s").val();
 	
 	 $("#sensitive_level_id_s").load("index.php?r=sensitive/level_account&account_id="+account+'&check_val='+check_val);
}
function change_add(check_val){
    var account = $("#sensitive_account").val();
 	 $("#sensitive_level_id").load("index.php?r=sensitive/level_account&account_id="+account+'&check_val='+check_val);
}
function change_edit(check_val){
    var account = $("#sensitive_account_m").val();
 	 $("#sensitive_level_id_m").load("index.php?r=sensitive/level_account&account_id="+account+'&check_val='+check_val);
}


function edit_open(){
	 $('.box-default-edit').removeClass("collapsed-box");
	 $('.box-body-edit').css('display','block');
	 $('.fa_edit').removeClass("fa-plus");
	 $('.fa_edit').addClass("fa-minus");
}


$(function() {
    //参数等级查看
    $(".view").click(function() {
        var id = $(this).attr('data');
        $.ajax({
            type: "POST",
            url: "index.php?r=parameter/view",
            data: {
                'id': id
            },
            beforeSend: parameterloading_view,
            success: parameterresponse_view
        })
    });
    //参数等级删除
    $(".view_del").click(function() {
        if(confirm(suredelete)){
	        var id = $(this).attr('data');
	        $.ajax({
	            type: "POST",
	            url: "index.php?r=parameter/parameter_del",
	            data: {
	                'id': id
	            },
	            beforeSend: parameterloading_edit,
	            success: response_view_del
	        })
        }
    });
    
});


//参数等级完成编辑
$(function() {
    $('#parameter_edit').text(parameter_edit_name);
    $("#parameter_edit").click(function() {
        var parameter_account_id = $('#view_name').val();
        var parameter_report_num = $('#view_parameter_report_num').val();
        var parameter_report_brush = $('#view_parameter_report_brush').val();

        var id = $('#view_id').val();
        if( parameter_report_brush=='' || parameter_report_num==''  ){
        	alert(reporttimenum);
        	return false;
        }
        
        if(!isNaN(parameter_report_brush) && !isNaN(parameter_report_num)){
        	$(this).attr('disabled', 'disabled');
            $(this).text(doing);
            $.ajax({
                type: "POST",
                url: "index.php?r=parameter/parameter_edit",
                data: {
                    'parameter_report_num': parameter_report_num,'parameter_account_id':parameter_account_id,'parameter_report_brush':parameter_report_brush,'id':id
                },
                beforeSend: parameterloading_edit,
                success: parameterresponse_edit
            });
        }else{
        	alert(is_number);
        	return false;
        }
        
        
    });
});
function parameterloading_edit() {
    $("#show_loading").show();
}
function parameterresponse_edit(data) {
    var e = $('#parameter_edit');
    e.text(parameter_edit_name);
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

// 参数等级删除后处理
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
 //参数等级查看后处理
function parameterloading_view() {
    $("#edit_loading").show();
}
function parameterresponse_view(data) {
    data = eval('(' + data + ')'); 
    if( data.flg == true){
    	$('#view_parameter_report_num').val(data.data.parameter_report_num);
    	$('#view_parameter_report_brush').val(data.data.parameter_report_brush);
        $('#view_name').val(data.data.parameter_account_id);
    	$('#view_id').val(data.data.id);
        $('.box-default-edit').removeClass("collapsed-box");
        $('.box-body-edit').css('display','block');

    }else{
    	alert(data.data);
    }
    var e = $('#parameter_edit');
    e.attr('disabled', false);
    $("#edit_loading").hide();
}
//参数添加
$(function() {
    $('#parameter_add').text(parameter_add_name);
    $("#parameter_add").click(function() {
        var parameter_report_num    = $('#parameter_report_num').val();
        var parameter_report_brush  = $('#parameter_report_brush').val();
        var parameter_account_id    = $('#dwcommentsearch-comment_channel_area').val();
        if( parameter_report_num == '' || parameter_report_brush==''|| parameter_account_id==''){
        	alert(accountreport);
        	return false;
        }
        if(!isNaN(parameter_report_brush) && !isNaN(parameter_report_num)){
        	$(this).attr('disabled', 'disabled');
            $(this).text(doing);
            $.ajax({
                type: "POST",
                url: "index.php?r=parameter/parameter_add",
                data: {
                    'parameter_report_num': parameter_report_num,'parameter_report_brush':parameter_report_brush,'parameter_account_id':parameter_account_id
                },
                beforeSend: parameterloading_add,
                success: parameterresponse_add
            });
        }else{
        	alert(is_number);
        	return false;
        }
        
        
    });
});
function parameterloading_add() {
    $("#show_loading").show();
    
}
function parameterresponse_add(data) {
    var e = $('#parameter_add');
     e.text(parameter_add_name);
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


//参数添加
$(function() {
    $('#parameter_add_manage').text(parameter_add_name);
    $("#parameter_add_manage").click(function() {
        var type = $('#sec').val();
        var parameter_level_id = $('#parameter_level_id').val();
        var parameter_action = $('#parameter_action').val();
        var parameter_account = $('#parameter_account').val();
        if( type == 'excel'){
        	var upload = $('#dwparameter-file').val();
        	if( upload == ''){
        		alert(uploadex);
        		return false;
        	}
        	
        	var excel_name1=upload.lastIndexOf(".");  
        	var excel_name2=upload.length;
            var excel_name=upload.substring(excel_name1,excel_name2);//后缀名  
			if( excel_name != '.xls'){
        		alert(typenot);
        		
        		return false;
        	}
			if( parameter_account == ''){
        		alert(pleselect);
        		return false;
        	}
        	$(this).attr('disabled', 'disabled');
            $(this).text(doing);
            $("#show_loading").show();
        	$("#form").attr('action','index.php?r=parameter/add');
        	$("#form").submit();
        	return false;
        	//var data = {'type':type,'parameter_level_id':parameter_level_id,'parameter_action':parameter_action}
        }else if(type == 'han'){
       		var parameter_name = $('#parameter_name').val();
        	if( parameter_name == ''){
        		alert(fillsensitive);
        		return false;
        	}
        	if( parameter_account == ''){
        		alert(pleselect);
        		return false;
        	}
        	
       		var parameter_replace = $('#parameter_replace').val();
<!--         	if( parameter_replace == ''){ -->
<!--         		alert(fillvalue); -->
<!--         		return false; -->
<!--         	} -->
       		var data = {'type':type,'parameter_level_id':parameter_level_id,'parameter_action':parameter_action,'parameter_name':parameter_name,'parameter_replace':parameter_replace,'parameter_account':parameter_account}
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
            url: "index.php?r=parameter/add",
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
   
    var e = $('#parameter_add_manage');
     e.text(parameter_add_name);
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


//参数查看
function parameter_view(id){
        $.ajax({
            type: "POST",
            url: "index.php?r=parameter/parameter_view",
            data: {
                'id': id
            },
            beforeSend: load_parameter_view,
            success: response_view_manage
        });
}
function load_parameter_view() {
    $("#edit_loading").show();
}
function response_view_manage(data) {
    data = eval('(' + data + ')'); 
    if( data.flg == true){
    	$("#parameter_level_id_m").val(data.data.parameter_level_id);
    	$('#parameter_action_m').val(data.data.parameter_action);
    	$('#parameter_name_m').val(data.data.parameter_name);
    	$('#parameter_replace_m').val(data.data.parameter_replace);
    	$('#parameter_id_m').val(data.data.id);
    }else{
    	alert(data.data);
    }
    var e = $('#parameter_edit');
    e.attr('disabled', false);
    $("#edit_loading").hide();
}


function loading_edit() {
    $("#show_loading").show();
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
    $('#parameter_search_name').text(parameter_search_name);
    $("#parameter_search_name").click(function() {
        var parameter_name = $('#parameter_name_s').val();
        var parameter_level_id = $('#parameter_level_id_s').val();
        var parameter_action = $('#parameter_action_s').val();
        var parameter_operator = $('#parameter_operator').val();
        $("#show_loading").show();
         window.location.href='index.php?r=parameter/manage&parameter_name='+parameter_name+'&parameter_level_id='+parameter_level_id+'&parameter_action='+parameter_action+'&parameter_operator='+parameter_operator; 
       
    });
    
    //下拉框初始化
    parameter();
});

function parameter(){
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

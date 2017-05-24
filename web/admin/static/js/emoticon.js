//*******表情包管理**********
//view2
var host_url ='http://focus.dwnews.com/'
if(issearch==1){
    $('.box-default-view').removeClass("collapsed-box");
    $('.box-body-view').css('display','block');
}
$(function() {
    $(".view").click(function() {
        var id = $(this).attr('data');
        $.ajax({
            type: "POST",
            url: "index.php?r=emoticon/emoticon_view",
            data: {
                'id': id
            },
            beforeSend: emoticonloading_view,
            success: emoticonresponse_view
        })
    });
    
     // 表情分类删除
    $(".view_del").click(function() {
        if(confirm(suredelete)){
	        var id = $(this).attr('data');
	        $.ajax({
	            type: "POST",
	            url: "index.php?r=emoticon/category_del",
	            data: {
	                'id': id
	            },
	            beforeSend: emoticonloading_list,
	            success: response_view_del
	        })
        }
    });
});

$(function(){
    $("#upsubmit").click(function(){
        var account_val = $("#dwemoticon-emoticon_account_id option:selected").val();
        var class_val = $("#dwemoticon-emoticon_cate_id option:selected").val();
        if(account_val == ''){
            //账户不能为空
            alert(accountempty);
        }else{
            if(class_val == ''){
                alert(expressionempty);
            }else{
                $("#upload-form").submit();
            }
        }
    });
})

function emoticonloading_list(){
 	$("#category_show_loading").show();
}

// 删除后处理
function response_view_del(data){
	data = eval('(' + data + ')'); 
    if( data.flg == false){
    	alert(data.data);
    }
     $("#category_show_loading").hide();
}
function emoticonloading_view() {
    $("#edit_loading").show();
}
function emoticonresponse_view(data) {
	data = eval('(' + data + ')'); 
    if( data.flg == true){
    	$('#emo_preview').attr('src',host_url+"/upload/"+data.data.emoticon_url);
    	$("#emoticon_preview").show();
    	$('#emoticonName').val(data.data.emoticon_name);
    	$('#emoticon_cate').val(data.data.emoticon_cate_id);
    	$('#emoticon_id').val(data.data.id);
        $('.box-default-edit').removeClass("collapsed-box");
        $('.box-body-edit').css('display','block');
    }else{
    	alert(data.data);
    }
    var e = $('#emoticon_edit');
    e.attr('disabled', false);
    e.text(emoticon_edit_name);
    $("#edit_loading").hide();
}
//编辑
$(function() {
    $('#emoticon_edit').text(emoticon_edit_name);
    $("#emoticon_edit").click(function() {
    	var name = $('#emoticonName').val();
        var cateid = $('#emoticon_cate').val();
        var id = $('#emoticon_id').val();
        if( name == ''){
    		alert(emotionempty);
    		return false;
    	}
         if( id == ''){
            alert('未获取要修改的表情ID,请选择表情后再修改');
            return false;
        }
        $(this).attr('disabled', 'disabled');
        $(this).text(doing);
        $.ajax({
            type: "POST",
            url: "index.php?r=emoticon/emoticon_edit",
            data: {
                'name': name,'cateid':cateid,'id':id
            },
            beforeSend: emoticonloading_edit,
            success: emoticonresponse_edit
        });
    });
});
function emoticonloading_edit() {
    $("#show_loading").show();
}
function emoticonresponse_edit(data) {
    var e = $('#emoticon_edit');
    e.attr('disabled', false);
    e.text(emoticon_edit_name);
    $("#show_loading").hide();
    
    data = eval('(' + data + ')'); 
    if( data.flg == true){
    	alert(savesuccess);
    }else{
    	alert(data.data);
    }
}
//*******表情分类**********
//view
$(function() {
    $(".view_category").click(function() {
        var id = $(this).attr('data');
        $.ajax({
            type: "POST",
            url: "index.php?r=emoticon/category_view",
            data: {
                'id': id
            },
            beforeSend: emoticoncategoryloading_view,
            success: emoticoncategoryresponse_view
        })
    });
});
function emoticoncategoryloading_view() {
    $("#category_edit_loading").show();
}
function emoticoncategoryresponse_view(data) {
	data = eval('(' + data + ')'); 
    if( data.flg == true){
    	$('#category_view_name').val(data.data.emoticon_category_name);
    	$('#category_view_id').val(data.data.id);
    }else{
    	alert(data.data);
    }
    var e = $('#category_emoticon_edit');
    e.attr('disabled', false);
    e.text(category_emoticon_edit_name);
    $("#category_edit_loading").hide();
    $('.box-default-edit').removeClass("collapsed-box");
    $('.box-body-edit').css('display','block');
}
//添加
$(function() {
    $('#category_emoticon_add').text(category_emoticon_add_name);
    $("#category_emoticon_add").click(function() {
    	var account = $('#emoticon_account').val();
    	if( account == '' ){
        	alert(accountempty);
        	return false;
        }
    	var name = $('#emoticon_category_name').val();
    	if( name == '' ){
        	alert(categoryempty);
        	return false;
        }
        $(this).attr('disabled', 'disabled');
        $(this).text(doing);
        $.ajax({
            type: "POST",
            url: "index.php?r=emoticon/category_add",
            data: {
                'name': name,'account':account
            },
            beforeSend: emoticoncategoryloading_add,
            success: emoticoncategoryresponse_add
        });
    });
});
function emoticoncategoryloading_add() {
    $("#category_show_loading").show();
}
function emoticoncategoryresponse_add(data) {
    var e = $('#category_emoticon_add');
    e.attr('disabled', false);
    e.text(category_emoticon_add_name);
    $("#category_show_loading").hide();   
    
    data = eval('(' + data + ')'); 
    if( data.flg == true){
    	alert(addsucess);
    }else{
    	alert(data.data);
    }
}
//编辑
$(function() {
    $('#category_emoticon_edit').text(category_emoticon_edit_name);
    $("#category_emoticon_edit").click(function() {
    	var name = $('#category_view_name').val();
    	if( name == ''){
    		alert(categoryempty);
    		return false;
    	}
        var id = $('#category_view_id').val();
        $(this).attr('disabled', 'disabled');
        $(this).text(doing);
        $.ajax({
            type: "POST",
            url: "index.php?r=emoticon/category_edit",
            data: {
                'name': name,'id':id
            },
            beforeSend: emoticoncategoryloading_edit,
            success: emoticoncategoryresponse_edit
        });
    });
});
function emoticoncategoryloading_edit() {
    $("#category_show_loading").show();
}
function emoticoncategoryresponse_edit(data) {
    var e = $('#category_emoticon_edit');
    e.attr('disabled', false);
    e.text(category_emoticon_edit_name);
    $("#category_show_loading").hide();
    
    data = eval('(' + data + ')'); 
    if( data.flg == true){
    	alert(savesuccess);
    }else{
    	alert(data.data);
    }
}


// 检索
$("#emoticon_search").click(function() {
    var account = $("#account_search").val();
    var cate_id = $("#emoticon_cate_id_search").val();
    $("#show_loading").show();
    window.location.href='index.php?r=emoticon/index&account='+account+'&cate_id='+cate_id;
});

function change(check_val){
    var account = $("#dwemoticon-emoticon_account_id").val();
 	 $("#dwemoticon-emoticon_cate_id").load("index.php?r=emoticon/category_account&account_id="+account+'&check_val='+check_val);
}
function change_search(check_val){
    var account = $("#account_search").val();
 	 $("#emoticon_cate_id_search").load("index.php?r=emoticon/category_account&account_id="+account+'&check_val='+check_val);
}
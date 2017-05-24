//*******表情包管理**********
//view2
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
        if(confirm("您确定要删除此项吗？")){
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
    	$('#emo_preview').attr('src',"<?php echo Yii::$app->urlManager->hostInfo.'/upload/';?>"+data.data.emoticon_url);
    	$("#emoticon_preview").show();
    	$('#emoticonName').val(data.data.emoticon_name);
    	$('#emoticon_cate').val(data.data.emoticon_cate_id);
    	$('#emoticon_id').val(data.data.id);
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
        $(this).attr('disabled', 'disabled');
        $(this).text('处理中.....');
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
    	alert('保存成功');
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
}
//添加
$(function() {
    $('#category_emoticon_add').text(category_emoticon_add_name);
    $("#category_emoticon_add").click(function() {
    	var name = $('#emoticon_category_name').val();
    	if( name == '' ){
        	alert('‘分类名称’不能为空');
        	return false;
        }
        $(this).attr('disabled', 'disabled');
        $(this).text('处理中.....');
        $.ajax({
            type: "POST",
            url: "index.php?r=emoticon/category_add",
            data: {
                'name': name
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
    	alert('添加成功');
    }else{
    	alert(data.data);
    }
}
//编辑
$(function() {
    $('#category_emoticon_edit').text(category_emoticon_edit_name);
    $("#category_emoticon_edit").click(function() {
    	var name = $('#category_view_name').val();
        var id = $('#category_view_id').val();
        $(this).attr('disabled', 'disabled');
        $(this).text('处理中.....');
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
    	alert('保存成功');
    }else{
    	alert(data.data);
    }
}
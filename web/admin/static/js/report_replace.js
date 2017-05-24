/*if(issearch==1){
    $('.box-default-view').removeClass("collapsed-box");
    $('.box-body-view').css('display','block');
}*/
//查看
$(function() {

    // 添加分类
    $('#replace_add').text(category_add_name);
    $("#replace_add").click(function() {
        var name = $('#name').val();
        var account_report_cate = $('#account_report_cate').val();
        if( name == ''){
            alert(nameempty);
            return false;
        }
        if( account_report_cate == ''){
            alert(accountempty);
            return false;
        }
        $(this).attr('disabled', 'disabled');
        $(this).text(doing);
        $.ajax({
            type: "POST",
            url: "index.php?r=report/replace_add",
            data: {
                'name': name,'account':account_report_cate
            },
            beforeSend: categoryloading_add,
            success: replaceresponse_add
        });
    });

    //编辑分类
    $('#replace_edit').text(category_edit_name);
     $("#replace_edit").click(function() {
        var name = $('#re_replace_content').val();
        var id = $('#view_id').val();
        if(name==''){
            alert(comnotempty);
            return false;
        }
        $(this).attr('disabled', 'disabled');
        $(this).text(doing);
        $.ajax({
            type: "POST",
            url: "index.php?r=report/replaceedit",
            data: {
                'name':name,'id':id
            },
            beforeSend: categoryloading_edit,
            success: replaceresponse_edit
        });
    });

    // 查看举报分类
    $(".rview").click(function() {
     var id = $(this).attr('data');
     $.ajax({
     type: "POST",
     url: "index.php?r=report/replaceview",
     data: {
        'id': id
     },
     beforeSend: reportloading_view,
     success: replaceresponse_view
     })
     });

    //分类删除
    $(".view_del").click(function() {
     var id = $(this).attr('data');
     $.ajax({
     type: "POST",
     url: "index.php?r=report/replacedel",
     data: {
     'id': id
     },
     beforeSend: categoryloading_add,
     success: replaceresponse_del
     })
     });
    
    // 举报检索
    $('#report_add').text(report_add_name);
    $("#report_add").click(function() {
        var report_status = $.trim($("#report_status").val());
        var report_idtype = $.trim($("#report_idtype").val());
        var report_from_uid = $.trim($("#report_from_uid").val());
        var report_uid = $.trim($("#report_uid").val());
        var start_time = $.trim($("#start_time").val());
        var end_time = $.trim($("#end_time").val());
        var report_content = $.trim($("#report_content").val());
        var report_title = $.trim($("#report_title").val());
        var report_account = $.trim($("#report_account").val());
        $("#repot_loading").show();
        window.location.href='index.php?r=report/index&report_account='+report_account+'&report_status='+report_status+'&report_idtype='+report_idtype+'&report_from_uid='+report_from_uid+'&report_uid='+report_uid+'&start_time='+start_time+'&end_time='+end_time+'&report_content='+report_content+'&report_title='+report_title;
    });
    
});


//添加
function replaceresponse_add(data){
    var e = $('#replace_add');
    e.text(category_add_name);
    e.attr('disabled', false);
    $("#show_loading").hide();
    data = eval('(' + data + ')');
    if( data.flg == true){
        alert(addsucess);
    }else{
        alert(data.data);
    }
}

//查看
function replaceresponse_view(data) {
 data = eval('(' + data + ')');
 if( data.flg == true){
 $('#re_replace_content').val(data.data.report_replace_content);
 $('#view_id').val(data.data.id);
 }else{
 alert(data.data);
 }
 var e = $('#replace_edit');
 e.attr('disabled', false);
 $("#edit_loading").hide();
 $('.box-default-edit').removeClass("collapsed-box");
 $('.box-body-edit').css('display','block');
 }

//编辑
function replaceresponse_edit(data) {
 var e = $('#replace_edit');
 e.text(category_edit_name);
 e.attr('disabled', false);
 $("#show_loading").hide();
 data = eval('(' + data + ')');
 if( data.flg == true){
 alert(modsuccess);
 }else{
 alert(data.data);
 }
 }

// 删除后处理
function replaceresponse_del(data){
 data = eval('(' + data + ')');
 if( data.flg == false){
 alert(data.data);
 }
 $("#show_loading").hide();
 }


function categoryloading_add() {
    $("#show_loading").show();
}
function reportloading_view() {
 $("#edit_loading").show();
}
function categoryloading_edit() {
 $("#show_loading").show();
}


/*
function change(check_val){
    var account = $("#report_account").val();
 	 $("#report_idtype").load("index.php?r=report/category_account&account_id="+account+'&check_val='+check_val);
}
*/

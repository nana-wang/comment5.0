//查看
$(function() {
    // 举报审核
    $(".pass").click(function() {
        if(confirm(surereview)){
            var id = $(this).attr('data');
            $.ajax({
                type: "POST",
                url: "index.php?r=report/reedit",
                data: {
                    'report_status':2,'id':id
                },
                beforeSend: function(){
                    $("#repot_loading").show();
                },
                success: function(data){
                    $("#repot_loading").hide();
                    data = eval('(' + data + ')');
                    if( data.flg == true){
                        alert(data.data);
                        location.reload();
                    }else{
                        alert(data.data);
                    }
                }
            });
        }

    });

    // 添加分类
    $('#category_add').text(category_add_name);
    $("#category_add").click(function() {
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
            url: "index.php?r=report/category_add",
            data: {
                'name': name,'account':account_report_cate
            },
            beforeSend: categoryloading_add,
            success: categoryresponse_add
        });
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

    // 查看举报分类
    $(".cview").click(function() {
        var id = $(this).attr('data');
        $.ajax({
            type: "POST",
            url: "index.php?r=report/cateview",
            data: {
                'id': id
            },
            beforeSend: reportloading_view,
            success: reportresponse_view
        })
    });

    //分类删除
    $(".state").click(function() {
        var id = $(this).attr('data');
        $.ajax({
            type: "POST",
            url: "index.php?r=report/state",
            data: {
                'id': id
            },
            beforeSend: categoryloading_add,
            success: response_view_del
        })
    });


    //编辑分类
    $('#category_edit').text(category_edit_name);
    $("#category_edit").click(function() {
        var name = $('#re_type_title').val();
        var id = $('#view_id').val();
        if(name==''){
            alert(comnotempty);
            return false;
        }
        $(this).attr('disabled', 'disabled');
        $(this).text(doing);
        $.ajax({
            type: "POST",
            url: "index.php?r=report/catedit",
            data: {
                'name':name,'id':id
            },
            beforeSend: categoryloading_edit,
            success: categoryresponse_edit
        });
    });

});

function reportresponse_add(data) {
    data = eval('(' + data + ')');
    if( data.flg == true){
        //ajax请求页面
        $("#report_data").html();
    }else{
        alert(data.data);
    }
    var e = $('#report_add');
    e.attr('disabled', false);
    e.text(report_add_name);
    $("#show_loading").hide();
}



function categoryloading_add() {
    $("#show_loading").show();

}
function categoryresponse_add(data) {
    var e = $('#category_add');
    e.text(category_add_name);
    e.attr('disabled', false);
    $("#show_loading").hide();

    alert(data);

    /*data = eval('(' + data + ')');
    if( data.flg == true){
        alert(addsucess);
    }else{
        alert(data.data);
    }*/
}




// 删除后处理
function response_view_del(data){
    data = eval('(' + data + ')');
    if( data.flg == false){
        alert(data.data);
    }
    $("#show_loading").hide();
}

function reportloading_view() {
    $("#edit_loading").show();
}
function reportresponse_view(data) {

    data = eval('(' + data + ')');
    if( data.flg == true){
        $('#re_type_title').val(data.data.report_type_title);
        $('#view_id').val(data.data.id);
    }else{
        alert(data.data);
    }
    var e = $('#report_edit');
    e.attr('disabled', false);
    $("#edit_loading").hide();
    $('.box-default-edit').removeClass("collapsed-box");
    $('.box-body-edit').css('display','block');
}

function categoryloading_edit() {
    $("#show_loading").show();
}
function categoryresponse_edit(data) {
    var e = $('#category_edit');
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


function change(check_val){
    var account = $("#report_account").val();
    $("#report_idtype").load("index.php?r=report/category_account&account_id="+account+'&check_val='+check_val);
}

//查看
$(function() {
    <!--     // 举报审核 -->
    $(".pass").click(function() {
         if(confirm("您确定要审核通过吗？")){
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
    
    <!--     // 点击查看  （暂时无用） -->
<!--     $(".view").click(function() { -->
<!--         var id = $(this).attr('data'); -->
<!--         $.ajax({ -->
<!--             type: "POST", -->
<!--             url: "index.php?r=report/repview", -->
<!--             data: { -->
<!--                 'id': id -->
<!--             }, -->
<!--             beforeSend: reportloading_view, -->
<!--             success: reportresponse_views -->
<!--         }) -->
<!--     }); -->
});
<!-- function reportloading_view() { -->
<!--     $("#edit_loading").show(); -->
<!-- } -->
<!-- function reportresponse_views(data) { -->
<!--     data = eval('(' + data + ')'); -->
<!--     if( data.flg == true){ -->
<!--         $('#re_status').val(data.data.report_status); -->
<!--         $('#re_idtype').val(data.data.report_idtype); -->
<!--         $('#re_uid').val(data.data.report_uid); -->
<!--         $('#re_from_uid').val(data.data.report_from_uid); -->
<!--         $('#content_t').val(data.data.report_content); -->
<!--         $('#view_id').val(data.data.id); -->
<!--     }else{ -->
<!--         alert(data.data); -->
<!--     } -->
<!--     var e = $('#report_edit'); -->
<!--     e.attr('disabled', false); -->
<!--     $("#edit_loading").hide(); -->
<!-- } -->
//完成审核
<!-- $(function() { -->
<!--     $('#report_edit').text(report_edit_name); -->
<!--     $("#report_edit").click(function() { -->
<!--         var report_status = $('#re_status').val(); -->
<!--         var report_idtype = $('#re_idtype').val(); -->
<!--         var report_uid = $('#re_uid').val(); -->
<!--         var report_from_uid = $('#re_from_uid').val(); -->
<!--         var id = $('#view_id').val(); -->
<!--         if( report_status==''  ){ -->
<!--             alert('内容不能为空，请填写相应的内容'); -->
<!--             return false; -->
<!--         } -->
<!--         $(this).attr('disabled', 'disabled'); -->
<!--         $(this).text('处理中.....'); -->
<!--         $.ajax({ -->
<!--             type: "POST", -->
<!--             url: "index.php?r=report/reedit", -->
<!--             data: { -->
<!--                 'report_status':report_status,'id':id -->
<!--             }, -->
<!--             beforeSend: reportloading_edit, -->
<!--             success: reportresponse_edit -->
<!--         }); -->
<!--     }); -->
<!-- }); -->
<!-- function reportloading_edit() { -->
<!--     $("#repot_loading").show(); -->
<!-- } -->
<!-- function reportresponse_edit(data) { -->
<!--     $("#show_view_edit").show();  -->
<!--     $("#box-default").removeClass().addClass("box box-default");  -->
<!--     var e = $('#report_edit'); -->
<!--     e.text(report_edit_name); -->
<!--     e.attr('disabled', false); -->
<!--     $("#repot_loading").hide(); -->
<!--     data = eval('(' + data + ')');  -->
<!--     if( data.flg == true){ -->
<!--         alert('审核成功'); -->
<!--     }else{ -->
<!--         alert(data.data); -->
<!--     } -->
<!-- } -->
//检索
$(function() {
    $('#report_add').text(report_add_name);
    $("#report_add").click(function() {
        var report_status = $.trim($("#report_status").val());
        var report_idtype = $.trim($("#report_idtype").val());
        var report_from_uid = $.trim($("#report_from_uid").val());
        var report_uid = $.trim($("#report_uid").val());
        var start_time = $.trim($("#start_time").val());
        var end_time = $.trim($("#end_time").val());
        var report_content = $.trim($("#report_content").val());
        var report_account = $.trim($("#report_account").val());
        $("#repot_loading").show();
        window.location.href='index.php?r=report/index&report_account='+report_account+'&report_status='+report_status+'&report_idtype='+report_idtype+'&report_from_uid='+report_from_uid+'&report_uid='+report_uid+'&start_time='+start_time+'&end_time='+end_time+'&report_content='+report_content;
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



//添加分类
$(function() {
    $('#category_add').text(category_add_name);
    $("#category_add").click(function() {
        var name = $('#name').val();
        if( name == ''){
            alert('‘名称’不能为空');
            return false;
        }
        $(this).attr('disabled', 'disabled');
        $(this).text('处理中.....');
        $.ajax({
            type: "POST",
            url: "index.php?r=report/category_add",
            data: {
                'name': name,
            },
            beforeSend: categoryloading_add,
            success: categoryresponse_add
        });
    });
});
function categoryloading_add() {
    $("#show_loading").show();
    
}
function categoryresponse_add(data) {
    var e = $('#category_add');
    e.text(category_add_name);
    e.attr('disabled', false);
    $("#show_loading").hide();
    data = eval('(' + data + ')'); 
    if( data.flg == true){
        alert('添加成功');
    }else{
        alert(data.data);
    }
}

//查看分类
$(function() {
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
});

//分类删除
$(function() {
    $(".view_del").click(function() {
        if(confirm("您确定要删除此项吗？")){
            var id = $(this).attr('data');
            $.ajax({
                type: "POST",
                url: "index.php?r=report/catedel",
                data: {
                    'id': id
                },
                beforeSend: categoryloading_add,
                success: response_view_del
            })
        }
    });
});
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
}

//编辑分类
$(function() {
    $('#category_edit').text(category_edit_name);
    $("#category_edit").click(function() {
        var name = $('#re_type_title').val();
        var id = $('#view_id').val();
        if(name==''){
            alert('内容不能为空，请填写相应的内容');
            return false;
        }
        $(this).attr('disabled', 'disabled');
        $(this).text('处理中.....');
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
        alert('修改成功');
    }else{
        alert(data.data);
        
    }
}
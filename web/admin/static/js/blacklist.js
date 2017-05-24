//黑名单添加
if(issearch==1){
    $('.box-default-view').removeClass("collapsed-box");
    $('.box-body-view').css('display','block');
}
$(function() {
    $('#blacklist_add_manage').text(blacklist_add_name);
    $("#blacklist_add_manage").click(function() {
        var blacklist_uid = $('#blacklist_uid').val();
        var username = $('#blacklist_uid option:selected').text();
        if( blacklist_uid=='' || blacklist_uid==0 ){
            alert(choseuser);
            return false;
        }
        $(this).attr('disabled', 'disabled');
        $(this).text(doing);
        $.ajax({
            type: "POST",
            url: "index.php?r=blacklist/black_add",
            data: {
                'blacklist_uid': blacklist_uid,'username':username
            },
            beforeSend: blacklistloading_add,
            success: blacklistresponse_add
        });
    });
});

function blacklistloading_add() {
    $("#show_loading").show();
}
function blacklistresponse_add(data) {
    var e = $('#blacklist_add_manage');
    e.text(blacklist_add_name);
    e.attr('disabled', false);
     $("#show_loading").hide();
    data = eval('(' + data + ')'); 
    if( data.flg == true){
        alert(addsucess);
    }else{
        alert(data.data);
    }  
}


//移除用户
$(function() {
    $(".view_del").click(function() {
        if(confirm(sureremove)){
            var id = $(this).attr('data');
            $.ajax({
                type: "POST",
                url: "index.php?r=blacklist/blackdel",
                data: {
                    'id': id
                },
                beforeSend: blacklistloading_del,
                success: blacklistresponse_view_del
            })
        }
    });
});

// 删除后处理
function blacklistloading_del() {
    $("#show_loading").show();
}
function blacklistresponse_view_del(data) {
    data = eval('(' + data + ')');
     $("#show_loading").hide();
    if( data.flg == true){
        alert(data.data);
        location.reload(); 
    }else{
    	alert(data.data);
    }
   
}
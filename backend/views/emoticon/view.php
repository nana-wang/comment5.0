//view
$(function() {
    $(".view").click(function() {
        var id = $(this).attr('data');
        $.ajax({
            type: "POST",
            url: "demo.php",
            data: {
                'id': id
            },
            beforeSend: emoticonloading_view,
            success: emoticonresponse_view
        })
    });
});
function emoticonloading_view() {
    $("#edit_loading").show();
}
function emoticonresponse_view(data) {
    var e = $('#emoticon_edit');
    e.attr('disabled', false);
    e.text(emoticon_edit_name);
    $("#edit_loading").hide();
}
//添加
$(function() {
    $('#emoticon_add').text(emoticon_add_name);
    $("#emoticon_add").click(function() {
        $(this).attr('disabled', 'disabled');
        $(this).text('处理中.....');
        $.ajax({
            type: "POST",
            url: "demo.php",
            data: {
                'dw_data': 'test'
            },
            beforeSend: emoticonloading_add,
            success: emoticonresponse_add
        });
    });
});
function emoticonloading_add() {
    $("#show_loading").show();
}
function emoticonresponse_add(data) {
    var e = $('#emoticon_add');
    e.attr('disabled', false);
    e.text(emoticon_add_name);
    $("#show_loading").hide();
}
//编辑
$(function() {
    $('#emoticon_edit').text(emoticon_edit_name);
    $("#emoticon_edit").click(function() {
        $(this).attr('disabled', 'disabled');
        $(this).text('处理中.....');
        $.ajax({
            type: "POST",
            url: "demo.php",
            data: {
                'dw_data': 'test'
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
}
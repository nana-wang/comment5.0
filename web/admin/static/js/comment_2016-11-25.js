
$(function() {
	
<!-- 	//表单设定切换  -->
	$('input[name="fourm_item_idtype"]').click(function() {
	     var actype = $(this).val();
	     if( actype == 2){
	     	show_img();
	     }else if(actype == 1){
	     	show_word();
	     }else if(actype == 3){
	    	 show_tag();   
	   	 }else{
	   	 	hide_all();
	   	 }
	});

	// 表单设定添加
	 $('#submit_button').click(function() {
	 	hide_message();
	 	add();
	 });
	 // 表单设定清除
	 $('#submit_button_clear').click(function() {
	 	hide_message();
	 	category_item_clear();
	 });
	 // 表单设定编辑
	 $('#submit_button_edit').click(function() {
	 	hide_message();
	 	edit();
	 });
	 // 表单类型 获取对应的表单设定值
	 account_change('');
	 // 表单类型添加
	 $('#formtype_button_add').click(function() {
	 	hide_message();
	 	formtype_add();
	 });
	 // 表单类型编辑
	 $('#formtype_button_edit').click(function() {
	 	hide_message();
	 	formtype_edit();
	 });
<!-- 	 // 表单类型编辑查看 -->
<!-- 	$(".formtype_view").click(function() { -->
<!--         hide_message(); -->
<!--         var id = $(this).attr('data'); -->
<!--         formTypeEdit_view(id); -->
<!--     }); -->
    
    // 表单生成
	 $('#makeing_button').click(function() {
	 	hide_message();
	 	makeing_form();
	 });
	 
	 // 使用区域
	 $('#usearea_button_add').click(function() {
	 	hide_message();
	 	usearea_add();
	 });
	 // 使用区域编辑
	 $('#usearea_button_edit').click(function() {
	 	hide_message();
	 	usearea_edit();
	 });
	 
	 
});


<!-- // 表单设定切换 -->
function show_img(){
	$('#img').show();
    $('#word').hide();
    $('#tag').hide();
    $("#add_tags").hide();
}
function show_word(){
	$('#word').show();
	$('#img').hide();
	$('#tag').hide();
	$("#add_tags").hide();
}
function show_tag(){
	$('#tag').show();
	$('#img').hide();
	$('#word').hide();	
	$("#add_tags").show();
}
function hide_all(){
	$('#tag').hide();
	$("#add_tags").hide();
	$('#img').hide();
	$('#word').hide();
		
}
<!-- // 图片类型 表情贴纸切换 -->
function show_props(id){
	if( id == 1){
		$('.checkbox_1').show();
		$('.checkbox_2').hide();
	}else if (id == 2){
		$('.checkbox_1').hide();
		$('.checkbox_2').show();
	}
}
<!-- // 提示信息隐藏 -->
function hide_message(){
	$('#formtype_ok').hide();
	$('#formtype_error').hide();
	$('#ok').hide();
	$('#error').hide();
	$('#makeing_ok').hide();
	$('#makeing_error').hide();
	$('#userarea_ok').hide();
	$('#userarea_error').hide();
}


<!-- // 表单设定      添加                             表单设定  添加                -->
function add(){
 		var fourm_item_account =$("#fourm_item_account").val();
 		if( fourm_item_account == ''){
 			alert('请选择所属账户组');return false;
 		}
 		
 		var fourm_item_title =$("input[name='fourm_item_title']").val();
 		var fourm_item_idtype =$(":radio[name='fourm_item_idtype']:checked").val();
 		var fourm_item_is_ver = $(":radio[name='fourm_item_is_ver']:checked").val();
 		var id =$("input[name='id']").val();
 		var item_ext_delid = $('#item_ext_delid').val();
 		
 		if(fourm_item_idtype == 3){
 			 var fourm_item_tag = []; // 标签类型存放为空，图片类型时存放图片中表情的id
 			  // 标签
 			 var tag = document.getElementsByName("tags[]");
 			 for(var n=0;n < tag.length;n++){
				var a = tag[n].value;
				var b = document.getElementsByName("tags[]")[n].getAttribute("item_ext_id");
				fourm_item_tag.push(a+'#'+b);
		    }
		    var tag_content_online =$("input[name='tag_content_online']").val();
 		    var fourm_item_tag_type =$(":radio[name='fourm_item_tags_type']:checked").val();
		   
		    var param = {id:id,fourm_item_account:fourm_item_account,fourm_item_title:fourm_item_title,
		    fourm_item_idtype:fourm_item_idtype,fourm_item_is_ver:fourm_item_is_ver,item_ext_delid:item_ext_delid,
		    fourm_item_tag:fourm_item_tag,tag_content_online:tag_content_online,fourm_item_tag_type:fourm_item_tag_type};
 		
 		 }else if(fourm_item_idtype == 2){ // 图片表情贴纸
 			  var img_content_online =$("input[name='img_content_online']").val();
 			var img_content_path =$("input[name='img_content_path']").val();
 			var img_content_type =$(":radio[name='img_content_type']:checked").val();
 			 var fourm_item_tag = []; // 标签类型存放为空，图片类型时存放图片中表情的id
 			 var checkbox = document.getElementsByName("img_emotion_type"+img_content_type);
 			 for(var n=0;n < checkbox.length;n++){
				if(checkbox[n].checked == true) {
				fourm_item_tag.push(checkbox[n].value);
				}
		    }
		      
		    var param = {id:id,fourm_item_account:fourm_item_account,fourm_item_title:fourm_item_title,fourm_item_idtype:fourm_item_idtype,
		    fourm_item_is_ver:fourm_item_is_ver,item_ext_delid:item_ext_delid,
		    img_content_online:img_content_online,img_content_path:img_content_path, img_content_type:img_content_type,fourm_item_tag:fourm_item_tag};
 		
 		 }else if(fourm_item_idtype == 1){ // 文字
 		    var word_content_prompt =$("input[name='word_content_prompt']").val();
 		    var word_content_online =$("input[name='word_content_online']").val();
		    var param = {id:id,fourm_item_account:fourm_item_account,fourm_item_title:fourm_item_title,fourm_item_idtype:fourm_item_idtype,
		    fourm_item_is_ver:fourm_item_is_ver,item_ext_delid:item_ext_delid,
		    word_content_prompt:word_content_prompt,word_content_online:word_content_online};
 		 }
 		
 		$.ajax({
            type: "POST",
            url: "index.php?r=comment/fourm_category_item_add",
            data: param,
            beforeSend: commentAddloading,
            success: commentFormresponse_add
        })
}
function commentAddloading(){
	$('#form_add_loading').show();
}
function commentFormresponse_add(data){
	$('#form_add_loading').hide();
	data = eval('(' + data + ')');
	if(data.flg == true ){
		$('#ok').show();
 		 location.reload();  
	}else{
		$('#error').show();
	}
}

<!-- // 表单设定      清除                             表单设定  清除               -->
function category_item_clear(){
	    $("input[name='id']").val('');
	    $("input[name='item_ext_delid']").val('');
	    $("input[name='fourm_item_title']").val('');
	    $("#fourm_item_account").val('');
	    $(":radio").attr('disabled',false);
 			$("input[name='word_content_prompt']").val('');
			$("input[name='word_content_online']").val('');
 			$("input[name='img_content_online']").val('');
			$("input[name='img_content_path']").val('');
 			$("input[name='tag_content_online']").val('');
 			$("input[name='tag_content_online']").val('');
 		document.getElementById("append_form").innerHTML = "";
 		$("#submit_button").text('新增');
}
<!-- // 表单设定      删除                             表单设定 删除               -->
function category_item_del(id){
		if(confirm("您确定要停用此项吗？")){
			$.ajax({
	            type: "POST",
	            url: "index.php?r=comment/fourm_category_item_del",
	            data: {id:id},
	            beforeSend: Listloading,
	            success: Listloading_del
	        })
        }
}
function Listloading(){
$("#type_loading").show();
}
function Listloading_del(data){
	$("#type_loading").hide();
	data = eval('(' + data + ')');
	if(data.flg == true ){
		$('#ok').show();
 		 location.reload();  
		 
	}else{
		$('#error').show();
	}
}

<!-- // 表单设定      查看                             表单设定 查看             -->
function edit_view(id){
		hide_message();
		$.ajax({
            type: "POST",
            url: "index.php?r=comment/fourm_category_item_view",
            data: {
                'id': id,
            },
            beforeSend: commentEditViewloading,
            success: commentEditViewloading_view
        })
}
function commentEditViewloading(){
	$('#form_add_loading').show();
}
function commentEditViewloading_view(data){
	$('#form_add_loading').hide();
	data = eval('(' + data + ')');
	if(data.flg == true ){
		$("#fourm_item_account").val(data.data.fourm_item_account);
		$("input[name='fourm_item_title']").val(data.data.fourm_item_title);
 		var inputtype = document.getElementsByName("fourm_item_idtype");
		for(var n=0;n < inputtype.length;n++){
			inputtype[n].disabled = true;
			if(data.data.fourm_item_idtype == inputtype[n].value){
				inputtype[n].checked = true; 
			}
	    }
 		var inputList = document.getElementsByName("fourm_item_is_ver");
		for(var k=0;k < inputList.length;k++){
			if(data.data.fourm_item_is_ver == inputList[k].value){
				inputList[k].checked = true; 
			}
	    }
		var fourm_item_content = eval('(' + data.data.fourm_item_content + ')');
 		if(data.data.fourm_item_idtype == 1){
 			show_word();
 			$("input[name='word_content_prompt']").val(fourm_item_content.word_content_prompt);
			$("input[name='word_content_online']").val(fourm_item_content.word_content_online);
 		}else if(data.data.fourm_item_idtype == 2){ //img
 			show_img();
 			$("input[name='img_content_online']").val(fourm_item_content.img_content_online);
			$("input[name='img_content_path']").val(fourm_item_content.img_content_path);
			var img_radio = document.getElementsByName("img_content_type");
			for(var m=0;m < img_radio.length;m++){
				if(fourm_item_content.img_content_type == img_radio[m].value){
					img_radio[m].checked = true; 
				}
		    }
		    // 图片类型显示
		    show_props(fourm_item_content.img_content_type);
		    var checkbox = document.getElementsByName("img_emotion_type"+fourm_item_content.img_content_type);
 			var img_emotion_len = fourm_item_content.img_emotion_type.length;
 			 for(var p=0;p < checkbox.length;p++){
				   for(var m=0;m < img_emotion_len;m++){
				    if(fourm_item_content.img_emotion_type[m] == checkbox[p].value){
						checkbox[p].checked = true; 
					}
				   }
		    }
 		}else if(data.data.fourm_item_idtype == 3){//tag
 			show_tag();
 			var tag_radio = document.getElementsByName("fourm_item_tags_type");
			for(var m=0;m < tag_radio.length;m++){
				if(data.data.fourm_item_tag_type == tag_radio[m].value){
					tag_radio[m].checked = true; 
				}
		    }
 			$("input[name='tag_content_online']").val(fourm_item_content.tag_content_online);
 			$(".tags_input").remove();
 			if( data.data.fourmCategoryItemExt !== null){
 			var fourm_item_tag =  data.data.fourmCategoryItemExt;
			for(var m=0;m < fourm_item_tag.length;m++){
				var html = tag_html(fourm_item_tag[m]);
				$(html).appendTo("#append_form");
		    }
		    }
 			
 		}
 		$("input[name='id']").val(data.data.id);
 		$('#item_ext_delid').val('');
 		$("#submit_button").text('编辑');
	}else{
		$("#error").show();
	}
}

<!-- // 表单类型     添加                           表单类型     添加                -->
function formtype_add(){
 		var fourm_account =$("#fourm_item_account").val();
 		var fourm_title =$("input[name='fourm_title']").val();
 		var fourm_idtype_id = '';
 		var select = document.getElementById("list-assigned");  
 		for(var m=0;m < select.length;m++){
				fourm_idtype_id += ','+select.options[m].value;
		    }
 		var fourm_order =$("select[name='fourm_order']").val();
 		var fourm_meth =$("select[name='fourm_meth']").val();
 		var fourm_pess =$("select[name='fourm_pess']").val();
 		var fourm_number =$("select[name='fourm_number']").val();
 		var fourm_reply =$("select[name='fourm_reply']").val();
 		var fourm_anonymous =$("select[name='fourm_anonymous']").val();
 		var id= $("input[name='category_id']").val();
 		var param ={id:id,fourm_account,fourm_account,fourm_title:fourm_title,fourm_idtype_id:fourm_idtype_id,fourm_order:fourm_order,fourm_meth:fourm_meth,
 		fourm_pess:fourm_pess,fourm_number:fourm_number,fourm_reply:fourm_reply,fourm_anonymous:fourm_anonymous};
 		$.ajax({
            type: "POST",
            url: "index.php?r=comment/fourm_category_add",
            data: param,
            beforeSend: formtypeAddloading,
            success: formtypeAddresponse_add
        })
}
function formtypeAddloading(){
	$('#formtype_add_loading').show();
}
function formtypeAddresponse_add(data){
	$('#formtype_add_loading').hide();
	data = eval('(' + data + ')');
	if(data.flg == true ){
		$('#formtype_ok').show();
 		location.reload();  
		$("ul.nav-tabs li").eq(1).find("a").click();
	}else{
		$('#formtype_error').show();
	}
}
<!-- // 账户组对应的表单联动 -->
function account_change(check_val){
 	var account = $("#fourm_item_account").val();
 	if( account == ''){
	 	$("#list-avaliable").text('');
	 	$("#list-assigned").text('');
	 	return false;
 	}
 	 $(".checkbox").load("index.php?r=comment/fourm_item_account&id="+account+'&check_val='+check_val);
}
<!-- 表单类型中图片设定移动删除-->
function account_item_yidong(action_remove_id,action_add_id){
	var item = $('#'+action_remove_id).val();
	if( item==null ){
		return false;
	}
	var inform = document.getElementById(action_remove_id); 
 	for(var i=0;i < inform.length;i++){
    	for(var m=0;m < item.length;m++){
    		if( inform.options[i].value == item[m]){
    		 $('<option>').val(inform.options[i].value).text(inform.options[i].text).appendTo('#'+action_add_id);
			inform[i].remove(); 
    		}
    	}
 	
 	
    }
 	
}
<!-- 表单类型中图片上下排序-->
function account_item_up(){
	var item = $('#list-assigned').val();
	if( item==null ){
		return false;
	}else if(item.length > 1){
		alert('您一次只能选择一项');
		return false;
	}
	var inform = document.getElementById('list-assigned'); 
	var selected_eq = inform.selectedIndex;
	var selected_eq_val = inform.options[selected_eq];
	var change_eq='';
	if( selected_eq == 0){
		return false;
	}else if(selected_eq == 1){
		change_eq = 0
		var  selected_up_val = inform.options[change_eq];
		$(selected_eq_val).insertBefore(selected_up_val);
	}else{
	   change_eq = selected_eq-2;
	   var  selected_up_val = inform.options[change_eq];
		$(selected_eq_val).insertAfter(selected_up_val);
	}
	
	
 	
}
<!-- 表单类型中图片上下排序-->
function account_item_down(){
	var item = $('#list-assigned').val();
	if( item==null ){
		return false;
	}else if(item.length > 1){
		alert('您一次只能选择一项');
		return false;
	}
	
	var inform = document.getElementById('list-assigned'); 
	var selected_eq = inform.selectedIndex;
	var selected_eq_val = inform.options[selected_eq];
	var change_eq='';
	
	if( selected_eq == inform.length-1){
		return false;
	}else{
	   change_eq = selected_eq+1;
	}
	var  selected_up_val = inform.options[change_eq];
	$(selected_eq_val).insertAfter(selected_up_val);
 	
}

<!-- // 表单类型     编辑                          表单类型     编辑               -->
function formtype_edit(){
 		$.ajax({
            type: "POST",
            url: "index.php?r=comment/test",
            data: {
                'id': 1,
            },
            beforeSend: formtypeEditloading,
            success: formtypeEditresponse
        })
}
function formtypeEditloading(){
	$('#formtype_edit_loading').show();
}
function formtypeEditresponse(data){
	$('#formtype_edit_loading').hide();
	data = eval('(' + data + ')');
	if(data.flg == true ){
		$('#formtype_ok').show();
	}else{
		$('#formtype_error').show();
	}
}
<!-- // 表单类型    删除                          表单类型     删除               -->
function category_del(id){
		if(confirm("您确定要删除此项吗？")){
			$.ajax({
	            type: "POST",
	            url: "index.php?r=comment/fourm_category_del",
	            data: {id:id},
	            beforeSend: loading2,
	            success: loading2_del
	        })
        }
}
function loading2(){
$("#formtype_loading").show();
}
function loading2_del(data){
	$("#formtype_loading").hide();
	data = eval('(' + data + ')');
	if(data.flg == true ){
		 $('#formtype_ok').show();
 		 location.reload();  
	}else{
		$('#formtype_error').show();
	}
}

<!-- // 表单类型    查看 -->
function formTypeEdit_view(id){
		hide_message();
		$.ajax({
            type: "POST",
            url: "index.php?r=comment/fourm_category_view",
            data: {
                'id': id,
            },
            beforeSend: formtypeAddloading,
            success: formtypeEditView_success
        })
}

function formtypeEditView_success(data){
	$('#formtype_add_loading').hide();
	data = eval('(' + data + ')');
	if(data.flg == true ){
	    $("#fourm_item_account").val(data.data.fourm_account);
	    $("#fourm_item_account").attr('disabled',true);
	    $("input[name='fourm_title']").val(data.data.fourm_title);
	    $("[name='fourm_order']").val(data.data.fourm_order);
	    $("[name='fourm_meth']").val(data.data.fourm_meth);
	    $("[name='fourm_pess']").val(data.data.fourm_pess);
	    $("[name='fourm_number']").val(data.data.fourm_number);
	    $("[name='fourm_reply']").val(data.data.fourm_reply);
	    $("[name='fourm_anonymous']").val(data.data.fourm_anonymous);
	    $("input[name='category_id']").val(data.data.id);
	     account_change(data.data.fourm_idtype_id);
	     
<!-- 	    var fourm_idtype_id = data.data.fourm_idtype_id -->
<!-- 	    var strs= new Array(); //定义一数组  -->
<!-- 	    var html_iteminput = document.getElementsByName("fourm_idtype_id[]"); -->
<!-- 	    strs=fourm_idtype_id.split(","); //字符分割 -->
<!-- 	    $("[name='fourm_idtype_id[]']").removeAttr('checked'); -->
<!-- 		for (i=0;i < strs.length ;i++ ) -->
<!-- 		{  -->
<!-- 			for(k=0;k < html_iteminput.length;k++){ -->
<!-- 				alert(strs[i]+html_iteminput[k].value); -->
<!-- 				if( strs[i] == html_iteminput[k].value){ -->
<!-- 					alert(html_iteminput[k].value); -->
<!-- 					html_iteminput[k].checked = true;  -->
<!-- 				} -->
<!-- 		    } -->
<!-- 		}  -->
	     $("#formtype_button_add").text('编辑');
	    
	}else{
		$('#formtype_error').show();
	}
}

<!-- // 表单类型      清除                            表单类型  清除               -->
function category_clear(){
	    $("input[name='fourm_title']").val('');
 		$("[name='fourm_order']").val('');
	    $("[name='fourm_meth']").val('');
	    $("[name='fourm_pess']").val('');
	    $("[name='fourm_number']").val('');
	    $("[name='fourm_reply']").val('');
	    $("[name='fourm_anonymous']").val('');
	    $("input[name='category_id']").val('');
 		$("input[name='id']").val('');
 		$("#fourm_item_account").attr('disabled',false);
 		$("#formtype_button_add").text('新增');
}

<!-- // 使用区域新加                          使用区域新加-->
function usearea_add(){
		var fourm_area =  $("input[name='fourm_area']").val();
		var id =  $("input[name='area_id']").val();
		$.ajax({
            type: "POST",
            url: "index.php?r=comment/fourm_area_add",
            data: {
                'fourm_area': fourm_area,id:id
            },
            beforeSend: usearea_loading,
            success: usearea_loading_success
        })
}
function usearea_loading(){
	$('#usearea_add_loading').show();
}
function usearea_loading_success(data){
	$('#usearea_add_loading').hide();
	data = eval('(' + data + ')');
	if(data.flg == true ){
		$('#userarea_ok').show();
 		location.reload(); 
	}else{
		$('#userarea_error').show();
	}
}
<!-- // 使用区域    删除                        使用区域     删除               -->
function area_del(id){
		if(confirm("您确定要删除此项吗？")){
			$.ajax({
	            type: "POST",
	            url: "index.php?r=comment/fourm_area_del",
	            data: {id:id},
	            beforeSend: loading3,
	            success: loading3_del
	        })
        }
}
function loading3(){
$("#usearea_loading").show();
}
function loading3_del(data){
	$("#usearea_loading").hide();
	data = eval('(' + data + ')');
	if(data.flg == true ){
		 $('#userarea_ok').show();
 		 location.reload();  
	}else{
		$('#userarea_error').show();
	}
}
<!-- // 使用区域  编辑 查看 -->
function usearea_view(id){
		hide_message();
		$.ajax({
            type: "POST",
            url: "index.php?r=comment/fourm_area_view",
            data: {
                'id': id,
            },
            beforeSend: usearea_loading,
            success: usearea_view_success
        })
}

function usearea_view_success(data){
	$('#usearea_add_loading').hide();
	data = eval('(' + data + ')');
	if(data.flg == true ){
		$("input[name='fourm_area']").val(data.data.fourm_area);
		$("input[name='area_id']").val(data.data.id);
		
		$("#usearea_button_add").text('编辑');
	}else{
		$('#userarea_error').show();
	}
}

<!-- // 使用区域编辑 -->
function usearea_edit(){
		$.ajax({
            type: "POST",
            url: "index.php?r=comment/test",
            data: {
                'id': 1,
            },
            beforeSend: usearea_edit_loading,
            success: usearea_edit_success
        })
}

function usearea_edit_loading(data){
	$('#usearea_edit_loading').show();
}
function usearea_edit_success(data){
	$('#usearea_edit_loading').hide();
	data = eval('(' + data + ')');
	if(data.flg == true ){
		$('#userarea_ok').show();
	}else{ 
		$('#userarea_error').show();
	}
}

function tag_del(e){
	$(e).parents().eq(2).remove();
	var id = $(e).attr('item_ext_id');
	if( id !== ''){
		var item_ext_delid = $('#item_ext_delid').val();
		$('#item_ext_delid').val(item_ext_delid+','+id);
	}
}
function tag_html(val){
		var html ='<div class="form-group tags_input" >';
 		if( val == ''){
		   html +='<label class="col-sm-2 control-label" for="firstname"><small class="label pull-bottom bg-red"><span class="item_delete" item_ext_id="" onclick="tag_del(this);" style="cursor:pointer;">删除</span></small></label>';
		}else{
			html +='<label class="col-sm-2 control-label" for="firstname"><small class="label pull-bottom bg-red"><span class="item_delete" item_ext_id="'+ +val['id']+'" onclick="tag_del(this);" style="cursor:pointer;">删除</span></small></label>';
		}
		html +='<div class="col-sm-5">';
		if( val == ''){
		  html +='<input type="text" placeholder="标签#分值#排序" value="'+val+'"  item_ext_id=""  name="tags[]" class="form-control">&nbsp;'; 
		}else{
			html +='<input type="text" placeholder="标签#分值#排序" value="'+val['item_tag_name']+'#'+val['item_tag_score']+'#'+val['item_tag_sort']+'" item_ext_id="'+ +val['id']+'" name="tags[]" class="form-control">&nbsp;'; 
		}
		html +='</div>';
		html +='</div>'; 
		return html;
}
$("#add_tags").click(function(event){
	var html = tag_html('');
	if($("input[name='tags[]']").length >= 4){
	if($('#tag_content_online').val()==5){
		alert('星型评分最多5条!');
		$('#add_tags').hide();
	}
		}
	$(html).appendTo("#append_form"); 
	//$("#form_tags").clone(true).appendTo("#append_form");
});
<!-- //tag新加标签 -->	
function show_tags_btn(id){
if(id==3){
	$("#add_tags").show();
}else{
	 $("#add_tags").hide();
}
}


<!-- // 生成管理中账户组对应的表单类型联动 -->
function account_change_makeing(){
	var account = $("#fourm_item_account").val();
	if( account == ''){
		$("#fourm_category").text('');
		return false;
	}
	$("#fourm_category").load("index.php?r=comment/fourm_category_account&id="+account);
}
<!-- // 表单生成 -->
function makeing_form(){
		var fourm_generate_area = $('#fourm_item_account').val();
		var fourm_category_id = $('#fourm_category').val();
		if(fourm_category_id == '' ){
			alert('请选择表单类型');
			return false;
		}
		$.ajax({
            type: "POST",
            url: "index.php?r=comment/fourm_making",
            data: {
                'fourm_generate_area': fourm_generate_area,'fourm_category_id':fourm_category_id
            },
            beforeSend: makeing_formloading,
            success: makeing_form_success
        })
}
function makeing_formloading(){
	$('#make_loading').show();
}
function makeing_form_success(data){
	$('#make_loading').hide();
	data = eval('(' + data + ')');
	if(data.flg == true ){
		$('#makeing_ok').show();
	}else{
		$('#makeing_error').show();
	}
}
function idtype_setting(id){
	if(id==1){
		$('#tag_content_online').attr('disabled',true);
		$('#tag_content_online').val(5);
	}else{
		 $('#tag_content_online').attr('disabled',false);
	}
	
}
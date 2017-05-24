<?php 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<style>
    a{color:#999;font-size:12px;}
	.floor{border: 1;border-bottom:1px solid #D5D5D5;}
	#editor{margin-top: 20px;padding:0;margin:20px 0;width:100%;height:auto;border: 1;}
	#emoji{background-color:#D5D5D5;margin-top: 20px;padding:0;margin:10px 0;width:100%;height:150px;border: 1;display:none}
	img{margin:1px 1px 1px 1px}
	
	ul,li,div {padding:0;margin:0;}
	.tab ul li {float:left;width:100px;height:30px;line-height:30px;text-align:center;background-color:#fff;border:1px #bbb solid;border-bottom:none;}
	ul li.fli {background-color:#ccc;color:red;}
	ul {overflow:hidden;zoom:1;list-style-type:none;}
	#tab_con {width:100%;height:200px;}
	#tab_con div {width:100%;height:120px;display:none;border:1px #D5D5D5 solid;border-top:none;}
	#tab_con div.fdiv {display:block;background-color:#D5D5D5;}
</style>
<div id="comment">
	<textarea id="commentform-comment" class="form-control" name="CommentForm[comment]" rows="3"></textarea>
	<input type="button" value="表情" class="btn btn-success" onclick="showFace();">
	<input type="button" class="btn btn-success btn-flat" value="提交" onclick="doComment();">   
	
	<div class='tab' onclick = 'selectTab()'>
		<div id="emoji" class="col-sm-1 col-md-12"><?php echo $img;?></div>
	</div>
</div>	
	<div id='commentbox'>
		<?php  
			if(!empty($main_comment)){
				echo '<hr>';
				foreach ($main_comment as $m_k =>$m_v){
echo '<b style="color:red;">作者：' . $m_v['comment_user_id'] .'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. date('Y-m-d H:i',$m_v['comment_created_at']).'</b><a href="#none" onclick="reply('.$m_v['comment_user_id'].','.$m_v['id'].','.$m_v['id'].')">&nbsp;回复</a>&nbsp; <span id="reply_'.$m_v['id'].'"></span><br/>';
						echo $comment_content[$m_v['id']]['comment_content'] . '<br/>'; 
						echo '<span style="color:#999;font-size:12px"><a href="#none;" onclick="comment_report('.$m_v['id'].')">举报</a>&nbsp;|&nbsp;<a href="#none;" onclick="comment_up_down('.$m_v['id'].',\'up\')">顶（'.$m_v['comment_up'].'）</a>&nbsp;|&nbsp;<a href="#none;" onclick="comment_up_down('.$m_v['id'].',\'down\')">踩（'.$m_v['comment_up'].'）</a>&nbsp;|&nbsp;<a href="#none;" onclick="comment_del('.$m_v['id'].')">删除</a>&nbsp;</span><br/>';
						if( !empty($m_v['reply'])){
							foreach ($m_v['reply'] as $reply_k =>$reply_v){
									echo '<b style="color:blue;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $reply_v['comment_user_id'].' 回复 '. $reply_v['comment_to_user_id'].'&nbsp;&nbsp;&nbsp;&nbsp;'. date('Y-m-d H:i',$reply_v['comment_created_at']).'</b><a href="#none" onclick="reply('.$reply_v['comment_user_id'].','.$m_v['id'].','.$reply_v['id'].')">&nbsp;回复</a><span id="reply_'.$reply_v['id'].'"></span><br/>';
									echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$comment_content[$reply_v['id']]['comment_content']. '<br/>';
									echo '<span style="color:#999;font-size:12px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#none;" onclick="comment_report('.$reply_v['id'].')">举报</a>&nbsp;|&nbsp;<a href="#none;" onclick="comment_up_down('.$reply_v['id'].',\'up\')">顶（'.$reply_v['comment_up'].'）</a>&nbsp;|&nbsp;<a href="#none;" onclick="comment_up_down('.$reply_v['id'].',\'down\')">踩（'.$reply_v['comment_down'].'）</a>&nbsp;|&nbsp;<a href="#none;" onclick="comment_del('.$reply_v['id'].')">删除</a>&nbsp;</span><br/>';
									
							}
						}
						echo '<hr>';
				}
			}
		?>
	</div>
<?php 
use yii\widgets\LinkPager;
echo LinkPager::widget([
		'pagination' => $pagination,
		]);

?>
<script>
/********删除**********/
function comment_del(id){
    if(confirm("确认要删除？")){
    	if(!isNaN(id)){
    		$.ajax({
    			type : "POST",
    			url : 'http://focus.dwnews.com/index.php?r=test/comment_del',
    			data : {'id':id},
    			success : function(res) {
    				res = eval('(' + res + ')'); //转为json对象
    				alert(res.data);
    			},
    			complete : function(XMLHttpRequest, textStatus) {
    			},
    			error : function() {
    				//请求出错处理
    			}
    		});
    	}else{
			alert('参数错误');
        }
		
    }
}	
/********顶踩**********/
function comment_up_down(id,type){
	$.ajax({
		type : "POST",
		url : 'http://focus.dwnews.com/index.php?r=test/comment_up_down',
		data : {'id':id,'type':type},
		success : function(res) {
			res = eval('(' + res + ')'); //转为json对象
				alert(res.data);
		},
		complete : function(XMLHttpRequest, textStatus) {
		},
		error : function() {
			//请求出错处理
		}
	});
}	
/********举报**********/
function comment_report(id){
	$.ajax({
		type : "POST",
		url : 'http://focus.dwnews.com/index.php?r=test/comment_report',
		data : {'id':id},
		success : function(res) {
			res = eval('(' + res + ')'); //转为json对象
				alert(res.data);
		},
		complete : function(XMLHttpRequest, textStatus) {
		},
		error : function() {
			//请求出错处理
		}
	});
}		
/********回复框显示**********/
function reply(to_userid,pid,id){
	$("span[id^='reply']").text('');
	$('<input type="hidden" value="'+pid+'" id="pid"><input type="hidden" value="'+to_userid+'" id="to_userid"><textarea id="reply-comment" class="form-control"  rows="3"></textarea><input type="button" class="btn btn-success btn-flat" value="回复" onclick="doReply();">').appendTo("#reply_"+id);
}
/********评论提交**********/
function doComment(){
    var comment=$("#commentform-comment").val();
    if( comment == '' ){
              alert('请填写评论内容');
              return false;
    }
	$.ajax({
		type : "POST",
		url : 'http://focus.dwnews.com/index.php?r=test/save',
		data : {'comment':comment},
		datatype : "jsonp",//"xml", "html", "script", "json", "jsonp", "text".
		jsonp : 'jsonp_callback',
		jsonpCallback : "success_jsonpCallback",
		beforeSend : function() {
		},
		success : function(res) {
			res = eval('(' + res + ')'); //转为json对象
				alert(res.data);
				location.reload(); 
		},
		complete : function(XMLHttpRequest, textStatus) {
			//alert(XMLHttpRequest.responseText);
			//alert(textStatus);
			//HideLoading();
		},
		error : function() {
			//请求出错处理
		}
	});
}
/********回复提交**********/
function doReply(){
    var comment=$("#reply-comment").val();
    var pid=$("#pid").val();
    var to_userid=$("#to_userid").val();
	$.ajax({
		type : "POST",
		url : 'http://focus.dwnews.com/index.php?r=test/save',
		data : {'comment':comment,'pid':pid,'to_userid':to_userid},
		datatype : "jsonp",//"xml", "html", "script", "json", "jsonp", "text".
		jsonp : 'jsonp_callback',
		jsonpCallback : "success_jsonpCallback",
		beforeSend : function() {
		},
		success : function(res) {
			res = eval('(' + res + ')'); //转为json对象
				alert(res.data);
				location.reload(); 
		},
		complete : function(XMLHttpRequest, textStatus) {
			//alert(XMLHttpRequest.responseText);
			//alert(textStatus);
			//HideLoading();
		},
		error : function() {
			//请求出错处理
		}
	});
}
/********表情显示**********/
function showFace(){
	if(document.getElementById('emoji').style.display == 'block') {
		document.getElementById('emoji').style.display = 'none';
	}else{
		document.getElementById('emoji').style.display = 'block';
	}
}
/********tab切换**********/
function selectTab() {
	var tabs=document.getElementById("tab").getElementsByTagName("li");
	var divs=document.getElementById("tab_con").getElementsByTagName("div");
	
	for(var i=0;i<tabs.length;i++){
		tabs[i].onclick=function(){changea(this);}
	}
	function changea(obj){
		for(var i=0;i<tabs.length;i++){
			if(tabs[i]==obj){
				tabs[i].className="fli";
				divs[i].className="fdiv";
			}
			else{
				tabs[i].className="";
				divs[i].className="";
			}
		}
	}
}
/********tab切换end**********/
function insertsmiley(icon) {
	document.getElementById('commentform-comment').value+= ':'+icon+':';   
}
</script>


#!/opt/webroot/php/bin/php -q
<?php
// 生成敏感词词典
ini_set('memory_limit', '512M');
date_default_timezone_set('Asia/Shanghai');
require_once('FilterHelper.php');
error_reporting(0);
// http服务绑定的ip及端口
$serv = new swoole_http_server("focus.dwnews.com", 9503);
/**
 * 处理请求
 */
$serv->on('Request', function($request, $response) {

    $url = isset($request->get['url']) ? $request->get['url']: '';
    $type = isset($request->get['type']) ? $request->get['type']: '';
    $callback = isset($request->get['jsoncallback']) ? $request->get['jsoncallback']: '';
    $url_arr = explode(',',$url);
    foreach($url_arr as $key => $v){
	   	 //$url_path='/opt/webroot/htdocs/focus/web/sensitive/'.$v.'.txt';
	   	 $url_path='/Library/WebServer/Documents/comment/web/sensitive/'.$v.'.txt';
	   	 $blackword_tree = $v.'.tree';
	   	 // 读取敏感词字典库
		$handle = fopen($url_path, 'r');// 敏感词txt文档所在位置 /focus/web/sensitive/sensitive.txt
		$resTrie = trie_filter_new();
		while(! @feof($handle)) {
		    $item = trim(@fgets($handle));
		    if (empty($item)) {
		        continue;
		    }
		    trie_filter_store($resTrie, $item);
		}
		trie_filter_save($resTrie, $blackword_tree);
    }
	
	$res = array('flg'=>true,'url'=>$url);
	$res = $callback . "(" . json_encode($res) . ")";
	$response->end($res);
});
$serv->start();

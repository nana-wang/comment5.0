#!/opt/webroot/php/bin/php -q
<?php
ini_set('memory_limit', '512M');
date_default_timezone_set('Asia/Shanghai');
require_once('FilterHelper.php');
// http服务绑定的ip及端口
$serv = new swoole_http_server("focus.dwnews.com", 9502);
/**
 * 处理请求
 */
$serv->on('Request', function($request, $response) {

    $content = isset($request->post['content']) ? $request->post['content']: '';
    $tree_file = isset($request->post['type']) ? $request->post['type']: '';
    
    $tree_file_arr = explode(',',$tree_file);
   
    $result = array();
    if (!empty($content)) {
        clearstatcache();
         foreach($tree_file_arr as $key => $v){
          	 $tree_file = $v.'.tree';
          	 $new_mtime = filemtime($tree_file);
          	 $resTrie = FilterHelper::getResTrie($tree_file, $new_mtime);
          	 // 执行过滤
       		 $arrRet = trie_filter_search_all($resTrie, $content);
       		 // 提取过滤出的敏感词
       		 //$result[] = FilterHelper::getFilterWords($content, $arrRet);
       		 $a_data = FilterHelper::getFilterWords($content, $arrRet);
       		 $result = array_merge($result,$a_data);
         }
      	
    }
 	      $response->cookie("User", "Frank");
	      $response->end(json_encode($result));
});
$serv->start();


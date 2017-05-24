#!/opt/webroot/php/bin/php -q 
<?php
ini_set('memory_limit', '128M');
// 读取敏感词字典库
$type = $argv[1];
$handle = fopen($type.'_dict.txt', 'r');
$resTrie = trie_filter_new();

while(! feof($handle)) {
    $item = trim(fgets($handle));

    if (empty($item)) {
        continue;
    }
    trie_filter_store($resTrie, $item);
}
$blackword_tree = $type.'.tree';
trie_filter_save($resTrie, $blackword_tree);
?>

<?php 
namespace emoticon\plus;
class Emotionplus
{
	public function __construct()
	{
	}
	/**
	 * 获取表情
	 * type:1 表情框显示
	 * type:2 评论列表显示
	 * type:3 表情框、评论列表全部返回
	 */
	public function getEmoticon($data, $type = 0)
	{
		if($type == 0) {
			$img = "<img src='".$data['emoticon_url']."' style=\"cursor:pointer\" alt='".$data['emoticon_name']."' title='".$data['emoticon_name']."'height='30' width='30' onclick='insertsmiley(".$data['id'].")'/>";
		}else if($type == 1) {
			$img = "<img src='".$data['emoticon_url']."' alt='".$data['face_name']."' title='".$data['emoticon_name']."'/>";
		}else if($type == 2) {
			$img[] = "<img src='http://focus.dwnews.com/upload/".$data['emoticon_url']."' style=\"cursor:pointer\" alt='".$data['emoticon_name']."' title='".$data['emoticon_name']."'height='30' width='30' onclick='insertsmiley(".$data['id'].")'/>";
			$img[] = "<img src='http://focus.dwnews.com/upload/".$data['emoticon_url']."' alt='".$data['emoticon_name']."' title='".$data['emoticon_name']."'/>";
		}
		return $img;
	}

	/*
	 * 匹配表情码
	* */
	public function matchCode($comment){
		preg_match_all("/[:](.+?)[:]/ies", $comment, $matches);
		return $matches;
	}
	/**
	 * 评论列表表情码转换
	 * string $content 评论内容
	 * json $emotion 表情缓存
	 */
	public function displaysmiley($content,$emotion)
	{
		preg_match_all("/[:](.+?)[:]/ies", $content, $matches);
		if( !empty( $matches)){
			// 表情图片缓存
			$emotion = json_decode($emotion,true);
			
			foreach($matches[0] as $matches_k => $matches_v) {
				$img_id = $emotion[$matches[1][$matches_k]];
				
				$imgArr = self::getEmoticon($img_id, 2);
				$content = str_replace($matches_v,$imgArr[1],$content);
			}
		}
		return $content;
// 		$comment = array();
// 		foreach($commentData as $k => $v) {
// 			foreach($v['data'] as $v_k => $v_v) {
// 				//     			preg_match_all("/[:](.+?)[:]/ies", $v_v['comment'], $matches);
// 				$matches = $this->matchCode($v_v['comment']);
// 				if(!empty($matches[0])) {
// 					foreach($matches[0] as $matches_k => $matches_v) {
// 						$imgArr[] = $imgData[$matches[1][$matches_k]];
// 					}
// 					$commentData[$k]['data'][$v_k]['comment'] =   str_replace($matches[0], $imgArr, $v_v['comment']);
// 					unset($imgArr);
// 				}
// 			}
// 			//     		echo "</div>";
// 		}
// 		return  $commentData;
			
	}
}
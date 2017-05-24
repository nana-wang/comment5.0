<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DwComment;
use backend\models\DwAuthAccount;

/**
 * DwCommentSearch represents the model behind the search form about `frontend\models\DwComment`.
 */
class DwCommentSearch extends DwComment
{   public $comment_ip_type;
	public $key;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment_user_nickname', 'comment_user_id','comment_ip','comment_created_at','key','comment_status','comment_is_report','comment_parent_id','comment_ip_type','comment_channel_area'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params,$uid)
    {  
        //$query = DwComment::find();
        
        $query = DwComment::find()->joinWith('commentExp')->select("dw_comment.*,dw_comment_exp.comment_content");
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        	'sort' => [
        		 'defaultOrder' => [
        		  'id' => SORT_DESC
        		  ]
        	]
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
		// 权限控制
        if( !empty( $this->comment_channel_area)){
//         	$comment_channel_area = $this->getAccountByAccountId($uid,$this->comment_channel_area);
        	$comment_channel_area = DwAuthAccount::getCurrentAccount(1,$this->comment_channel_area);
        	if( !empty($comment_channel_area)){
        		$query->andWhere('comment_channel_area in ('.$comment_channel_area.')');
        	}
        }else{
//         	$comment_channel_area = $this->getAccountByUid($uid);
        	$comment_channel_area = DwAuthAccount::getCurrentAccount(1);
        	if( !empty($comment_channel_area)){
        		$query->andWhere('comment_channel_area in ('.$comment_channel_area.')');
        	}
        }
        
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'comment_is_report' => $this->comment_is_report,
        ]);
        // 状态
        if(!empty($this->comment_status)){
        	$query->andFilterWhere([
        			'comment_status' => $this->comment_status,
        	]);
        }else{
         	$query->andWhere('comment_status in (1,3,7)');
         	// 敏感词、举报隐藏 和 表单审核
        	//$query->andFilterWhere(['or',['comment_status'=>1,'comment_is_hide'=>0],['comment_status'=>3,'comment_is_hide'=>0],['comment_status'=>1,'comment_is_hide'=>3],['comment_status'=>1,'comment_is_hide'=>4]]);
        	 
        }
        
        
        // 发布还是回复
		if( $this->comment_parent_id == 1){// 发布
			$query->andFilterWhere(['comment_parent_id'=>0]);
		}else if($this->comment_parent_id == 2){
			$query->andFilterWhere(['>', 'comment_parent_id', 0]);
		}
		$query->andFilterWhere(['or',['comment_user_id'=>$this->comment_user_id],['comment_to_user_id'=>$this->comment_user_id]]);
        $query->andFilterWhere(['or',['like','comment_title',$this->key],['like','dw_comment_exp.comment_content',$this->key]]);
        
        if($this->comment_ip_type == 1 && !empty($this->comment_ip)){ // ip
        	$query->andFilterWhere(['comment_ip'=>$this->comment_ip]);
        }elseif($this->comment_ip_type == 2 && !empty($this->comment_ip)){ // ip段
        	//$query->andFilterWhere(['like','comment_ip',$this->comment_ip]);
        	$count = substr_count($this->comment_ip,'.') + 1;
        	$query->andWhere('substring_index(comment_ip,".",'.$count.') in (substring_index("'.$this->comment_ip.'",".",'.$count.'))');
        	
        }else{ // ip
        	$query->andFilterWhere(['comment_ip'=>$this->comment_ip]);
        }
        
        if( !empty($this->comment_created_at)){
        	$sear_time = strtotime($this->comment_created_at);
        	$query->andFilterWhere(['>', 'comment_created_at', $sear_time]);
        	$query->andFilterWhere(['<', 'comment_created_at', $sear_time+86400]);
        }
        return $dataProvider;
    }
    
    /**
     * 待审批清单检索
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function approval_search($params,$uid)
    {
    	//$query = DwComment::find();
    	$query = DwComment::find()->joinWith('commentExp')->select("dw_comment.*,dw_comment_exp.comment_content");
    	// add conditions that should always apply here
    
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    			'sort' => [
    			'defaultOrder' => [
    			'id' => SORT_DESC
    			]
    			]
    			]);
    
    	$this->load($params);
    
    	if (!$this->validate()) {
    		// uncomment the following line if you do not want to return any records when validation fails
    		// $query->where('0=1');
    		return $dataProvider;
    	}
    	
    	// 权限控制
        if( !empty( $this->comment_channel_area)){
        	$comment_channel_area = DwAuthAccount::getCurrentAccount(1,$this->comment_channel_area);
        	if( !empty($comment_channel_area)){
        		$query->andWhere('comment_channel_area in ('.$comment_channel_area.')');
        	}
        }else{
        	$comment_channel_area = DwAuthAccount::getCurrentAccount(1);
        	if( !empty($comment_channel_area)){
        		$query->andWhere('comment_channel_area in ('.$comment_channel_area.')');
        	}
        }
    	
    	// grid filtering conditions
    	$query->andFilterWhere([
    			'id' => $this->id,
    			]);
    	if(!empty($this->comment_status)){
    		$query->andFilterWhere([
    				'comment_status' => $this->comment_status,
    				]);
    	}else{
    		$query->andWhere('comment_status in (2,4,5,6)');
    		// 敏感词、举报隐藏 和 表单审核
    		//$query->andFilterWhere(['or',['comment_status'=>1,'comment_is_hide'=>0],['comment_status'=>3,'comment_is_hide'=>0],['comment_status'=>1,'comment_is_hide'=>3],['comment_status'=>1,'comment_is_hide'=>4]]);
    	
    	}
    	
    	if( $this->comment_parent_id == 1){// 发布
    		$query->andFilterWhere(['comment_parent_id'=>0]);
    	}else if($this->comment_parent_id == 2){
    		$query->andFilterWhere(['>', 'comment_parent_id', 0]);
    	}
    	$query->andFilterWhere(['or',['comment_user_id'=>$this->comment_user_id],['comment_to_user_id'=>$this->comment_user_id]]);
    	$query->andFilterWhere(['or',['like','comment_title',$this->key],['like','dw_comment_exp.comment_content',$this->key]]);
    
    	if($this->comment_ip_type == 1 && !empty($this->comment_ip)){ // ip
    		$query->andFilterWhere(['comment_ip'=>$this->comment_ip]);
    	}elseif($this->comment_ip_type == 2 && !empty($this->comment_ip)){ // ip段
    		//$query->andFilterWhere(['like','comment_ip',$this->comment_ip]);
    		$count = substr_count($this->comment_ip,'.') + 1;
    		$query->andWhere('substring_index(comment_ip,".",'.$count.') in (substring_index("'.$this->comment_ip.'",".",'.$count.'))');
    		
    	}else{ // ip
    		$query->andFilterWhere(['comment_ip'=>$this->comment_ip]);
    	}
    	
    	if( !empty($this->comment_created_at)){
    		$sear_time = strtotime($this->comment_created_at);
    		$query->andFilterWhere(['>', 'comment_created_at', $sear_time]);
    		$query->andFilterWhere(['<', 'comment_created_at', $sear_time+86400]);
    	}
    	return $dataProvider;
    }
    
//     /**
//      * 获取当前登录用户的所有账户
//      *
//      * @param array $params
//      *
//      * @return ActiveDataProvider
//      */
//     public static function getAccountByUid($uid){
//     	$str = '';
//     	if( !empty($uid)){
    		
//     		$account =json_decode(Yii::$app->redis->get(md5('parentToSubID_'.$uid)),true);
// 	    	if( !empty( $account) && is_array($account)){
// 	    		foreach ( $account as $key=>$v){
// 	    			$str .=','.implode(',',$v);
// 	    		}
// 	    		$str = trim($str,',');
// 	    	}else{
// 	    		$str = $account;
// 	    	}
//     	}
//     	return $str;
//     }
    /**
     * 获取当前登录用户 某账户下的 有权限的账户id
     *
     * @param $id int  所属账户组id
     *
     * @return ActiveDataProvider
     */
    public static function getAccountByAccountId($uid,$id){
    	$account_id = '';
    	if( !empty($uid)){
    		$account =json_decode(Yii::$app->redis->get(md5('parentToSubID_'.$uid)),true);
    		if( !empty( $account) && isset($account[$id])){
    			$account_id = implode(',',$account[$id]);
    		}else{
    			$account_id = $id;
    		}
    	}
    	return $account_id;
    	
    }
    
//     /**
//      * 获取当前登录账户的所有父类id
//      *
//      * @param array $params
//      *
//      * @return ActiveDataProvider
//      */
//     public static function getAccountPidByUid($uid){
//     	$account =json_decode(Yii::$app->redis->get(md5('parentToSubID_'.$uid)),true);
//         $all_account =json_decode(Yii::$app->redis->get(md5('all_parentToSubID_'.$uid)),true);
//         $parent_account =$parent_account_no=$parent_account_yes=$zi_account=[];
//         if( is_array($account) && !empty($account)){
//         	foreach ( $account as $key=>$v){
//         		foreach ( $v as $v_key=>$v_v){
//         			if(  $all_account[$v_v]['pid'] == 0 ){
//         				// 有权限的父账户
//         				$parent_account[] = $all_account[$v_v]['id'];
//         				$parent_account_yes[] = $all_account[$v_v]['id'];
//         			}else {
//         				// 有权限的子账户
//         				$parent_account[] = $all_account[$v_v]['pid'];// 子账户的父账户
//         				if( !isset($account[$all_account[$v_v]['pid']])){
//         					// 此子账户的父id没有授权
//         					$parent_account_no[] =  $all_account[$v_v]['pid'];
//         				}
        				
//         				$zi_account[] = $v_v;
        				
//         			}
//         		}
        			 
//         	}
//         $parent_account = array_unique($parent_account);
//         $zi_account = array_unique($zi_account);
//         $parent_account_yes = array_unique($parent_account_yes);
//         $parent_account_no = array_unique($parent_account_no);
//     	}
//     	$str = '';
//     	// 获取当前登录用户所属账户父类id下的所有敏感词
//     	if( !empty($parent_account)){
//     		$str .=implode(',',$parent_account);
//     	}
//     	// 获取当前登录用户所属账户父类下子账户对应自己的敏感词
//     	if(!empty($zi_account)){
//     		$zi_a = implode(',',$zi_account);
//     		$str .=','. $zi_a;
//     	}
    	
//     	$return_data['parent_account'] = $parent_account;
//     	$return_data['parent_account_yes'] = $parent_account_yes;
//     	$return_data['parent_account_no'] = $parent_account_no;
//     	// 有权限的子账户数据
//     	$return_data['zi_account_yes'] = $zi_account;
//     	// 子账户，继承父类账户，的检索数据（包含它的父类id，与当前账户的id）
//     	$return_data['parent_zi_account'] = trim($str,',');
//     	return $return_data;
    	
//     }
    
    /**
     * 判断某账户是否有权限
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public static function getPowerByaccountid($account_id,$uid=null){
//     	if( empty( $uid)){
//     		$uid =  Yii::$app->user->id;
//     	}
//     	$account_my = DwCommentSearch::getAccountByUid($uid);
    	
//     	$account_my = explode(',',$account_my);
//     	if( in_array($account_id,$account_my)){
//     		return true;
//     	}else{
//     		return false;
//     	}

    	// 默认切换账户id
    	$current_account_cookies = Yii::$app->request->cookies;
    	$current_account_id = $current_account_cookies->getValue('account_stat_id');
        if( $account_id == $current_account_id){
        	return true;
        }else{
        	return false;
        }
    	
    }
   
}

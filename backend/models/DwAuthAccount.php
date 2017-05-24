<?php

namespace backend\models;

use Yii;
use Qiniu\json_decode;
use GuzzleHttp\json_encode;

/**
 * This is the model class for table "dw_auth_account".
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property integer $pid
 * @property integer $created_at
 * @property integer $updated_at
 */
class DwAuthAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dw_auth_account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['pid', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', '账号'),
            'description' => Yii::t('app', '账号描述'),
            'pid' => Yii::t('app', '父节点'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     * @return DwAuthAccountQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DwAuthAccountQuery(get_called_class());
    }
    
    /**
     * @inheritdoc
     * @return DwAuthAccountQuery the active query used by this AR class.
     */
    public static function getAccountById($id)
    {
    	//$type= DwAuthAccount::find()->where(['id'=>$id])->asArray()->one();
    	$data = DwAuthAccount::getAccountRedis();
    	if( isset($data[$id])){
    	return $data[$id];
    	}else{
    		return '';
    	}
    }
    

    /**
     * 获取某账号的父id
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public static function getAccountPidByAccountid($account_id){
    	//$account= DwAuthAccount::find()->where(['id'=>$account_id])->asArray()->one();
    	$account = DwAuthAccount::getAccountRedis();
    	if( !empty($account)){
    		if( isset($account[$account_id])){
    			if( !empty($account[$account_id]['pid'])){
    				return $account[$account_id]['pid'];
    			}else{
    				return 0;
    			}
    			
    		}else{
    			return false;
    		}
    	}else{
    		return false;
    	}
    }
    
    /**
     * 账户数据缓存
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public static function getAccountRedis(){
    	$redis_data = Yii::$app->redis->get(md5('account'));
    	if( !empty($redis_data)){
    		return json_decode($redis_data,true);
    	}else{
    		$account= DwAuthAccount::find()->asArray()->all();
    		$redis_data = '';
    		if( !empty($account)){
    			foreach ($account as $k => $v){
    				$redis_data[$v['id']] = $v;
    			}
    		}
    		Yii::$app->redis->set(md5('account'),json_encode($redis_data));
    		return $redis_data;
    	}
    }
    
    /**
     * 账户数据缓存
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public static function updateAccountRedis(){
    		$account= DwAuthAccount::find()->asArray()->all();
    		$redis_data = '';
    		if( !empty($account)){
    			foreach ($account as $k => $v){
    				$redis_data[$v['id']] = $v;
    			}
    		}
    		Yii::$app->redis->set(md5('account'),json_encode($redis_data));
    		return $redis_data;
    }
    
    
    /**
     * 主账户区分，子账户不区分的下拉框权限数据
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public static function getAccountSelectRedis(){
    	$user_id =  Yii::$app->user->id;
    	$redis_data = Yii::$app->redis->get(md5('select_parentToSubID_'.$user_id));
    	if( !empty($redis_data)){
    		return $redis_data;
    	}else{
    		return '';
    	}
    }
    
    /**
     * 主账户下拉框权限数据
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public static function getMainAccountRedis(){
    	$user_id =  Yii::$app->user->id;
    	$redis_data = Yii::$app->redis->get(md5('main_parentToSubID_'.$user_id));
    	$option = '';
    	
    	if( !empty($redis_data)){
    		$redis_data = json_decode($redis_data,true);
    		foreach ($redis_data as $key => $value){
    			$option .= '<option value="' . $key . '" >' .
    					$value . '</option>';
    		}
    		
    	}
    	return $option;
    }
    
    
    /**
     * 当前切换用户数据
     *
     * @param array $type 1:子账户区分 2：子账户不区分
     * 
     * $search_account  账户检索的数据
     *
     * @return ActiveDataProvider
     */
    public static function getCurrentAccount($type=1,$search_account = null){
    	
    	if(!empty($search_account)){
    		// 检索账户id
    		$current_account_id = $search_account;
    	}else{
    		// 默认切换账户id
    		$current_account_cookies = Yii::$app->request->cookies;
    		$current_account_id = $current_account_cookies->getValue('account_stat_id');
    	}
       	if($type == 1){
    			// 说明当前切换账户是主账户，获取当前切换账户主账户下有权限的子账户id
    			$account_id = '';
    			$user_id =  Yii::$app->user->id;
		    	if( !empty($user_id)){
		    		$account =json_decode(Yii::$app->redis->get(md5('parentToSubID_'.$user_id)),true);
		    		if( !empty( $account) && isset($account[$current_account_id])){
		    			$account_id = implode(',',$account[$current_account_id]);
		    		}else{
		    			$account_id = $current_account_id;
		    		}
		    	}
    			return $account_id;
    	}elseif ($type == 2){
    		// 子账户不区分,获取当前切换账户主账户下的全部数据
    		$pid = self::getAccountPidByAccountid($current_account_id);
    		if( $pid != 0  ){
    			return $pid; // 说明当前切换账户是子账户
    		}else{
    			return $current_account_id;// 说明当前切换账户是主账户
    		}
    	}
    }
    /**
     * 检索账户数据
     *
     * @param array $type 1:子账户区分 2：子账户不区分
     *
     * @return ActiveDataProvider
     */
    public static function getCurrentAccountSensitive($search_account=null){
    	if(!empty($search_account)){
    		// 检索账户id
    		$current_account_id = $search_account;
    	}else{
    		// 默认切换账户id
    		$current_account_cookies = Yii::$app->request->cookies;
    		$current_account_id = $current_account_cookies->getValue('account_stat_id');
    	}
    	

    	$pid = self::getAccountPidByAccountid($current_account_id);
    	if( $pid != 0  ){
    		return $pid.','.$current_account_id; // 说明当前切换账户是子账户,应该返回主账户id+子账户id
    	}else{
    		$user_id =  Yii::$app->user->id;
    		$account =json_decode(Yii::$app->redis->get(md5('parentToSubID_'.$user_id)),true);
    		if( !empty( $account) && isset($account[$current_account_id])){
    			$account_id = implode(',',$account[$current_account_id]);
    		}else{
    			$account_id = $current_account_id;
    		}
    		return $account_id;// 说明当前切换账户是主账户，应该返回此主账户下有权限的子账户id+主账户id
    	}
    }
    
    /**
     * 获取用户所有有权限的账户
     *
     * @param int $uid 后台用户id
     *
     * @return ActiveDataProvider
     */
    public static function getAccountByUid($uid){
    	    	$str = '';
    	    	if( !empty($uid)){
    
    	    		$account =json_decode(Yii::$app->redis->get(md5('parentToSubID_'.$uid)),true);
    		    	if( !empty( $account) && is_array($account)){
    		    		foreach ( $account as $key=>$v){
    		    			$str .=','.implode(',',$v);
    		    		}
    		    		$str = trim($str,',');
    		    	}else{
    		    		$str = $account;
    		    	}
    	    	}
    	    	return $str;
    	    }

    
   
    
}


/**
 * This is the ActiveQuery class for [[DwAuthAccount]].
 *
 * @see DwAuthAccount
 */
class DwAuthAccountQuery extends \yii\db\ActiveQuery
{
	/*public function active()
	 {
	return $this->andWhere('[[status]]=1');
	}*/

	/**
	 * @inheritdoc
	 * @return DwAuthAccount[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return DwAuthAccount|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}

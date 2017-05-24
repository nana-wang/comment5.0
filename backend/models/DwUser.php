<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dw_props".
 *
 * @property integer $id
 * @property integer $props_available
 * @property integer $props_category_id
 * @property string $props_name
 * @property string $props_description
 * @property integer $props_credit
 */
class DwUser extends \yii\db\ActiveRecord
{   
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dw_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
       
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
        
        ];
    }

    /**
     * 获取缓存
     * @return DwCQuery the active query used by this AR class.
     */
    public static function get_user_redis_name($userid = null){

    	if( !empty($userid)){
	    	$p_redis_name = md5('all_backend_user');
	        $p_redis = Yii::$app->redis->get($p_redis_name);
	        $redis = '';
	        if(!empty($p_redis)){
	            $redis = json_decode($p_redis,true);
	        }else{
	            $redisdata = DwUser::find()
	            ->asArray()->all();
	            if( !empty($redisdata)){
	                foreach ( $redisdata as $s_key2 =>$s_v2){
	                    $a3[$s_v2['id']] = $s_v2;
	                }
	                $redisdata = @json_encode($a3);
	                $redis = $a3;
	            }else{
	                $redisdata = '';
	            }
	            Yii::$app->redis->set($p_redis_name,$redisdata);
	        }
	        if( isset($redis[$userid]) ){
	        	return $redis[$userid]['username'];
	        }else{
	        	return '无此管理员';
	        }
	        
    	}else{
    		return false;
    	}
    }
    
    
    
    /**
     * 根据昵称获取用户id
     * @return DwAuthAccountQuery the active query used by this AR class.
     */
    public static function getUserIdByName($nickname)
    {
    	$data = DwUser::getUserRedis();
    	$return_data = 0;
    	if( !empty($data)){
    		foreach ($data as $key => $v){
    			if($v['username'] == $nickname){
    				$return_data = $v['id'];
    				break;
    			}
    		}
    	}
    	return $return_data;
    }
    /**
     * 跟新缓存
     * @return DwCQuery the active query used by this AR class.
     */
    public static function update_user_redis(){
    
    	$p_redis_name=md5('all_backend_user');
    	$redisdata = DwUser::find()	->asArray()->all();
    	if( !empty($redisdata)){
    		foreach ( $redisdata as $s_key2 =>$s_v2){
    			$a3[$s_v2['id']] = $s_v2;
    		}
    		$redisdata = @json_encode($a3);
    	}else{
    		$redisdata = '';
    	}
    	Yii::$app->redis->set($p_redis_name,$redisdata);
    	return true;
    }
    
    /**
     * 数据缓存
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public static function getUserRedis(){
    	$p_redis_name = md5('all_backend_user');
	    $p_redis = Yii::$app->redis->get($p_redis_name);
	    $redis = '';
        if(!empty($p_redis)){
            $redis = json_decode($p_redis,true);
        }else{
            $redisdata = DwUser::find()
            ->asArray()->all();
            if( !empty($redisdata)){
                foreach ( $redisdata as $s_key2 =>$s_v2){
                    $a3[$s_v2['id']] = $s_v2;
                }
                $redisdata = @json_encode($a3);
                $redis = $a3;
            }else{
                $redisdata = '';
            }
            Yii::$app->redis->set($p_redis_name,$redisdata);
        }
	    return $redis;
    }
    
    

    /**
     * @inheritdoc
     * @return DwPropsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DwUserQuery(get_called_class());
    }
    
   
}



/**
 * This is the ActiveQuery class for [[DwProps]].
 *
 * @see DwProps
 */
class DwUserQuery extends \yii\db\ActiveQuery
{

	/**
	 * @inheritdoc
	 * @return DwProps[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return DwProps|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}

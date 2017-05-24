<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%dw_sensitive}}".
 *
 * @property string $id
 * @property integer $sensitive_level_id
 * @property string $sensitive_name
 * @property string $sensitive_replace
 * @property integer $sensitive_action
 * @property integer $sensitive_time
 */
class Blacklist extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%blacklist}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['blacklist_uid','blacklist_account_id'], 'required'],
            [['blacklist_create', 'blacklist_update','blacklist_action_uid','blacklist_level'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'blacklist_uid' => Yii::t('backend', 'Blick User'),
            'blacklist_action_uid' => Yii::t('backend', 'Operator'),
            'blacklist_create' => Yii::t('backend', 'Creat Time'),
            'blacklist_update' => Yii::t('backend', 'Update Time'),
            'blacklist_account_id' => Yii::t('backend', 'Account Group'),
            'blacklist_account_pid' => Yii::t('backend', 'Accountpid'),
            'blacklist_level' => Yii::t('backend', 'Grade'),

        ];
    }

    /**
     * @inheritdoc
     * @return DwSensitiveQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BlacklistQuery(get_called_class());
    }
    
    /**
     * 跟新缓存
     * @return DwCQuery the active query used by this AR class.
     */
    public static function update_blacklist_redis(){
    	$redis = Blacklist::find()->select('blacklist_uid')->asArray()->all();
    	if(  !empty($redis)){
    		foreach ( $redis as $s_key =>$s_v){
    			$key = md5($s_v['blacklist_uid']);
    			$a2[$key] = $s_v['blacklist_uid'];
    		}
    		$value = @json_encode($a2);
    		Yii::$app->redis->set('blacklist',$value);
    		return $a2;
    	}else{
    		$value='';
    		Yii::$app->redis->set('blacklist','');
    		return $value;
    	}
    	
    }
    
    /**
     * 获取缓存
     * @return DwCQuery the active query used by this AR class.
     */
    public static function get_blacklist_redis(){
    	$redis = Yii::$app->redis->get('blacklist');
    	if(!empty($redis)){
    		$redis = json_decode($redis,true);
    		return $redis;
    	}else{
	    	$redis = Blacklist::find()->select('blacklist_uid')->asArray()->all();
	    	if(  !empty($redis)){
	    		foreach ( $redis as $s_key =>$s_v){
	    			$key = md5($s_v['blacklist_uid']);
	    			$a2[$key] = $s_v['blacklist_uid'];
	    		}
	    		$value = @json_encode($a2);
	    		Yii::$app->redis->set('blacklist',$value);
	    		return $a2;
	    	}else{
	    		$value='';
	    		Yii::$app->redis->set('blacklist','');
	    		return $value;
	    	}
    	}
    }
    
    
    /**
     * 判断是否在黑名单
     *
     * @param unknown $comment_user_id
     * @return unknown[]
     */
    public static function check_blacklist($comment_user_id){
    	 
    	// 检测黑名单
    	$redis_blacklist = Blacklist::get_blacklist_redis();
    	$b_key = md5($comment_user_id);
    	if (!empty($comment_user_id) && isset($redis_blacklist[$b_key])) {
    		return true; // 说明此用户已经在黑名单内
    	}else{
    		return false;
    	}
    }
}
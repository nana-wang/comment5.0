<?php

namespace backend\models;

use Yii;
use Qiniu\json_decode;

/**
 * This is the model class for table "{{%dw_sensitive_level}}".
 *
 * @property string $id
 * @property string $sensitive_name
 * @property string $sensitive_description
 * @property integer $sensitive_time
 */
class DwSensitiveLevel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sensitive_level}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sensitive_name'], 'required'],
            [['sensitive_time'], 'integer'],
            [['sensitive_name'], 'string', 'max' => 50],
            [['sensitive_description'], 'string', 'max' => 400],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'Sensitive Level'),
            'sensitive_name' => Yii::t('backend', 'Class Name'),
            'sensitive_description' => Yii::t('backend', 'Class Description'),
            'sensitive_account_id' => Yii::t('backend', 'The Account'),
            'sensitive_account_id_pid' => Yii::t('backend', 'The Account'),
            'sensitive_time' => Yii::t('backend', 'Operation Time'),
        ];
    }

    /**
     * @inheritdoc
     * @return DwSensitiveLevelQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DwSensitiveLevelQuery(get_called_class());
    }
    
    /**
     * 获取某个敏感词的等级
     * $account_id pid
     */
    public static  function  get_level_redisname($account_id,$id){
    	$data = DwSensitiveLevel::get_senstive_level_redis($account_id);
        $msg = Yii::t('backend', 'Non Existent');
    	if(isset($data[$id])){
    		return $data[$id]['sensitive_name'];
    	}else{
    		return $msg;
    	}
    }
    
    /**
     * 获取缓存
     * $account_id 主账户数据
     * @return DwCQuery the active query used by this AR class.
     */
    public static function get_senstive_level_redis($account_id){
    	$account_pid = DwAuthAccount::getAccountPidByAccountid($account_id);
    	if( $account_pid == 0){
    		$account_pid = $account_id;
    	}
    	$redis = Yii::$app->redis->get(md5('senstive_level_'.$account_pid));
    	if(!empty($redis)){
    		$redis = json_decode($redis,true);
    		return $redis;
    	}else{
    		$data = DwSensitiveLevel::find()
    		->where(['or',['sensitive_account_id'=>$account_pid],['sensitive_account_id_pid'=>$account_pid]])
    		->asArray()->all();
    		if( !empty($data)) {
    			foreach ( $data as $s_key =>$s_v){
	    			$a2[$s_v['id']] = $s_v;
	    		}
	    		$value = @json_encode($a2);
	    		$redis = Yii::$app->redis->set(md5('senstive_level_'.$account_pid),$value);
    			return $a2;
    		}else{
    			$value='';
    			$redis = Yii::$app->redis->set(md5('senstive_level_'.$account_pid),'');
    			return $value;
    		}
    
    	}
    }
}

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
class DwSensitive extends \yii\db\ActiveRecord
{   public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sensitive}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//             [['sensitive_level_id', 'sensitive_name'], 'required'],
//             [['sensitive_level_id', 'sensitive_action', 'sensitive_time'], 'integer'],
//             [['sensitive_name'], 'string', 'max' => 50],
//             [['sensitive_replace'], 'string', 'max' => 100],
        //[['file'],'file','skipOnEmpty' => false,'extensions' => 'xls'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'Sensitive Word'),
            'sensitive_level_id' => Yii::t('backend', 'Sensitive Level'),
            'sensitive_name' => Yii::t('backend', 'Sensitive Word'),
            'sensitive_replace' => Yii::t('backend', 'Sensitive Replace'),
            'sensitive_action' => Yii::t('backend', 'Sensitive Operation'),
            'sensitive_time' => Yii::t('backend', 'Operation Time'),
            'sensitive_operator'=>Yii::t('backend', 'Operator'),
            'sensitive_account'=>Yii::t('backend', 'The Account'),
            'sensitive_account_pid'=>Yii::t('backend', 'Parent Account'),
            'file'=>Yii::t('backend', 'Plice Upload Extension'),
        ];
    }

    /**
     * @inheritdoc
     * @return DwSensitiveQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DwSensitiveQuery(get_called_class());
    }
    
    /**
     * 获取缓存
     * @return DwCQuery the active query used by this AR class.
     * $pid 主账户id
     * $account_id 所属账户id
     */
    public static function get_senstive_redis($pid,$account_id){
//     	$redis = Yii::$app->redis->get('senstive');
//     	if(!empty($redis)){
//     		$redis = json_decode($redis,true);
//     		return $redis;
//     	}else{
//     		$data = DwSensitive::find()->asArray()->all();
//     		if( !empty($data)) {
// 	    		foreach ( $data as $s_key =>$s_v){
// 	    			$a2[$s_v['sensitive_name']] = $s_v;
// 	    		}
// 	    		$value = @json_encode($a2);
// 	    		$redis = Yii::$app->redis->set('senstive',$value);
// 	    		return $a2;
//     		}else{
//     			$value='';
//     			$redis = Yii::$app->redis->set('fourm_category','');
//     			return $value;
//     		}
    		
//     	}
		if( $pid == 0){
			$pid = $account_id;
		}
	
		$return_data = [];
		$return_data['account'] = '';
		$return_data['account_p'] = '';
    	// 父类缓存
		$p_redis = Yii::$app->redis->get('0_'.$pid.'_senstive');
    	if(!empty($p_redis)){
    		$redis_p = json_decode($p_redis,true);
    		$return_data['account_p'] = $redis_p;
    		// 子类缓存
    		if($account_id != $pid){
    			$zi_account = Yii::$app->redis->get(md5($pid.'_'.$account_id.'_senstive'));
    			if( !empty($zi_account)){
    				$return_data['account'] = json_decode($zi_account,true);
    			}else{
    				$return_data['account'] = DwSensitive::update_zi_senstive_redis($pid,$account_id);
    			}
    		}
    	}else{
    		// 父类缓存
    		$return_data['account_p'] = DwSensitive::update_zhu_senstive_redis($pid);
    		if($account_id != $pid){
    			$redis_name = $pid.'_'.$account_id.'_senstive';
    			$zi_account_redis = Yii::$app->redis->get(md5($redis_name));
    			if( !empty($zi_account_redis)){
    				$return_data['account'] = json_decode($zi_account_redis,true);
    			}else{
    				$return_data['account'] = DwSensitive::update_zi_senstive_redis($pid,$account_id);
    			}
    		}
    		
    	
    	}
    	return $return_data;
    }
    
    /* 跟新子账户缓存
    * @return DwCQuery the active query used by this AR class.
    * $pid 主账户id 不能为0，必须为主账户id
    * $account_id 所属账户id
    */
    public static function update_zi_senstive_redis($pid,$account_id){
    	// 此账户下的敏感词缓存
    	$return = '';
    	if( $pid > 0){
	    	$redisdata = DwSensitive::find()
	    	->andFilterWhere(['sensitive_account'=>$account_id])
	    	->asArray()->all();
	    	if( !empty($redisdata)){
	    		foreach ( $redisdata as $s_key2 =>$s_v2){
	    			$a3[md5($s_v2['sensitive_name'])] = $s_v2;
	    		}
	    		$return  = $a3;
	    		$redisdata = @json_encode($a3);
	    	}else{
	    		$redisdata = '';
	    	}
	    	$redis = Yii::$app->redis->set(md5( $pid.'_'.$account_id.'_senstive'),$redisdata);
	    	}
    	return  $return;
    }
    /** 跟新主账户缓存
     * @return DwCQuery the active query used by this AR class.
     * $pid 主账户id 不能为0，必须为主账户id
     */
    public static function update_zhu_senstive_redis($pid){
    	// 此账户下的敏感词缓存
    	$return = '';
    	if( $pid > 0){
    		$redisdata = DwSensitive::find()
    		->andFilterWhere(['sensitive_account'=>$pid])
    		->asArray()->all();
    		if( !empty($redisdata)){
    			foreach ( $redisdata as $s_key2 =>$s_v2){
    				$a3[md5($s_v2['sensitive_name'])] = $s_v2;
    			}
    			$return  = $a3;
    			$redisdata = @json_encode($a3);
    		}else{
    			$redisdata = '';
    		}
    		$redis = Yii::$app->redis->set(md5( '0_'.$pid.'_senstive'),$redisdata);
    	}
    	return  $return;
    }
    
}

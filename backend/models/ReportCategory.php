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
class ReportCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%repost_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['report_type_title'], 'required'],
            [['report_type_create'], 'integer'],
            [['report_type_title'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'Class TypeID'),
            'report_type_title' => Yii::t('backend', 'Category Name'),
            'report_type_create' => Yii::t('backend', 'Creat Time'),
            'report_account_id' => Yii::t('backend', 'The Account'),
            'report_account_pid' => Yii::t('backend', 'Main Account'),
			'report_type_state' => Yii::t('backend', 'Current state'),
        ];
    }

    /**
     * @inheritdoc
     * @return DwSensitiveQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ReportCategoryQuery(get_called_class());
    }

    /**
     * 获取举报类型
     */
//     public static  function  get_report_title($id=null,$all=null){

//     	$type= ReportCategory::find()->where(['id'=>$id])->asArray()->one();
//         if($all == ''){
//             return $type;
//         }else{
//             return $type['report_type_title'];
//         }
         
//     }
    
    /**
     * 获取举报类型缓存
     */
    
    public static  function  get_report_redis_buyid($id=null,$account_id,$type_name_flg = false){
    	$report_redis = ReportCategory::get_report_type_redis($account_id);
    	if( isset($report_redis[$id])){
    		if( $type_name_flg ){
    			return $report_redis[$id]['report_type_title'];
    		}else{
    			return $report_redis[$id];
    		}
    		
    	}
    }
    
    /**
     * 更新缓存
     * $account_id 更新的数据所属账户
     */
    public static  function  update_report_type_redis($account_id){
    	$pid = DwAuthAccount::getAccountPidByAccountid($account_id);
    	if( $pid == 0){
    		$pid = $account_id;
    	}
    	$data = ReportCategory::find()
    			->orwhere(['report_account_pid'=>$pid])
    			->orwhere(['report_account_id'=>$pid])
    			->asArray()->all();
    	if(  !empty($data)){
    		foreach ( $data as $s_key =>$s_v){
    			$a2[$s_v['id']] = $s_v;
    		}
    		$value = @json_encode($a2);
    		Yii::$app->redis->set(md5('report_type_'.$pid),$value);
    		return $a2;
    	}else {
    		$value='';
    		Yii::$app->redis->set(md5('report_type_'.$pid),$value);
    		return $value;
    	}
    	 
    }
    
    /**
     * 更新缓存
     * $account_id 所属账户id
     */
    public static  function  get_report_type_redis($account_id ){
    	$pid = DwAuthAccount::getAccountPidByAccountid($account_id);
    	if( $pid == 0){
    		$pid = $account_id;
    	}
    	$redis = Yii::$app->redis->get(md5('report_type_'.$pid));
    	if( empty($redis)){
    		$redis = ReportCategory::update_report_type_redis($account_id);
    		return $redis;
    	}else{
    		return json_decode($redis,true);
    	}
    
    }
}
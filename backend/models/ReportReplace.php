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
class ReportReplace extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%report_replace}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['report_replace_content'], 'required'],
            [['report_replace_create'], 'integer'],
            [['report_replace_content'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'report_replace_content' => Yii::t('backend', 'Replace Text'),
            'report_replace_create' => Yii::t('backend', 'Creat Time'),
            'report_account_id' => Yii::t('backend', 'The Account'),
            'report_account_pid' => Yii::t('backend', 'Main Account'),
        ];
    }

    /**
     * @inheritdoc
     * @return DwSensitiveQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ReportReplaceQuery(get_called_class());
    }
    
    /**
     * 更新缓存
     * $account_id 更新的数据所属账户
     */
    public static  function  update_report_replace_redis(){
    	$data = ReportReplace::find()->asArray()->all();
    	if( !empty($data)){
    		foreach ( $data as $s_key =>$s_v){
    			$a2[$s_v['id']] = $s_v;
    		}
    		$value = @json_encode($a2);
    		Yii::$app->redis->set(md5('report_replace'),$value);
    		return $a2;
    	}else {
    		$value='';
    		Yii::$app->redis->set(md5('report_replace'),$value);
    		return $value;
    	}
    	 
    }
    
    /**
     * 更新缓存
     * $account_id 所属账户id
     */
    public static  function  get_report_replace_redis(){
    	$redis = Yii::$app->redis->get(md5('report_replace'));
    	if( empty($redis)){
    		$redis = ReportReplace::update_report_replace_redis();
    		return $redis;
    	}else{
    		return json_decode($redis,true);
    	}
    
    }
}
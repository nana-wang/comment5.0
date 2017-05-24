<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "face_category".
 *
 * @property integer $id
 * @property string $emoticon_category_name
 * @property integer $emoticon_category_create_time
 * @property integer $emoticon_category_update_time
 */
class DwemoticonCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dw_emoticon_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['emoticon_category_name'], 'required'],
            [['emoticon_category_create_time', 'emoticon_category_update_time'], 'integer'],
            [['emoticon_category_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'emoticon_category_name' => Yii::t('backend', 'Category Name'),
            'emoticon_category_create_time' => Yii::t('backend', 'Creat Time'),
            'emoticon_account_id' => Yii::t('backend', 'The Account'),
            'emoticon_account_pid' => Yii::t('backend', 'Accountpid'),
            'emoticon_category_update_time' => Yii::t('backend', 'Update Time'),
        ];
    }
    
    public static function getCate_redis()
    {
    	//     	$newcate = array();
    	//     	$cate = DwemoticonCategory::find()->select(['id','emoticon_category_name'])->where(['emoticon_category_status'=>1])->asArray()->all();
    	//     	foreach($cate as $k => $v) {
    	//     		$newcate[$v['id']] = $v['emoticon_category_name'];
    	//     	}
    	$redis = Yii::$app->redis->get('emoticon_category');
    	$value = '';
    	if( $redis ){
    		$value = json_decode($redis,true);
    	}
    	return $value;
    }
    
    /**
     * 根据账户id获取主账户数据
     * $account_id 主账户id
     */
    public static function get_category_redis_byaccountid($account_id)
    {   
        $pid = DwAuthAccount::getAccountPidByAccountid($account_id);
        if( $pid == 0){
        	$pid = $account_id;
        }
    	$redis = Yii::$app->redis->get('emoticon_category_'.$pid);
    	$value = '';
    	if( $redis ){
    		$value = json_decode($redis,true);
    	}else{
    		$value = DwemoticonCategory::update_emotion_category_redis($account_id,$pid);
    	}
    	return $value;
    }
    /**
     * 更新表情分类缓存
     * $account_id 所属账户id
     * $pid 所属账户pid
     */
    public static function update_emotion_category_redis($account_id,$pid='')
    {
    	if( empty( $pid)){
    		$pid = DwAuthAccount::getAccountPidByAccountid($account_id);
    		if( $pid == 0){
    			$pid = $account_id;
    		}
    	}
    	$data = DwemoticonCategory::find()
    			->where(['emoticon_category_status'=>1])
    			->andWhere(['or',['emoticon_account_id'=>$pid],['emoticon_account_pid'=>$pid]])
    			->asArray()->all();
    	if(  !empty($data)){
    		foreach ( $data as $s_key =>$s_v){
    			$a2[$s_v['id']] = $s_v['emoticon_category_name'];
    		}
    		$value = @json_encode($a2);
    		Yii::$app->redis->set(md5('emoticon_category_'.$pid),$value);
    		return $a2;
    	}else {
    		$value='';
    		Yii::$app->redis->set(md5('emoticon_category_'.$pid),$value);
    		return $value;
    	}
    }
}

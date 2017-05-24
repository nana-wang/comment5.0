<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dw_emoticon".
 *
 * @property integer $id
 * @property integer $emoticon_cate_id
 * @property string $emoticon_name
 * @property string $emoticon_url
 * @property integer $emoticon_create_time
 * @property integer $emoticon_update_time
 */
class DwEmoticon extends \yii\db\ActiveRecord
{
	public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dw_emoticon';
    }
    
    public function relations()
    {
    	return array(
    			'FaceCategorys'=>array(self::BELONGS_TO, 'DwemoticonCategory', 'emoticon_cate_id'),
    	);
    }
    
    public function getCatename()
    {
    	/**
    	 * 第一个参数为要关联的字表模型类名称，
    	 *第二个参数指定 通过子表的 customer_id 去关联主表的 id 字段
    	 */
    	return $this->hasOne(DwemoticonCategory::className(), ['id' => 'emoticon_cate_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['emoticon_name', 'emoticon_cate_id','emoticon_account_id'], 'required'],
            [['emoticon_cate_id', 'emoticon_create_time'], 'integer'],
            [['emoticon_name'], 'string', 'max' => 200],
            [['emoticon_url'], 'string', 'max' => 200],
        	[['file'],'file','skipOnEmpty' => false,'extensions' => 'zip'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'emoticon_cate_id' => Yii::t('backend', 'The Category'),
            'emoticon_name' => Yii::t('backend', 'Emotio Name'),
            'emoticon_url' => Yii::t('backend', 'Emotion Path'),
            'emoticon_account_id' => Yii::t('backend', 'The Account'),
            'emoticon_account_pid' => Yii::t('backend', 'Accountpid'),
            'emoticon_create_time' => Yii::t('backend', 'Creat Time'),
            'emoticon_update_time' => Yii::t('backend', 'Update Time'),
            'file'=>Yii::t('backend', 'Zip Type')
        ];
    }
    
    /**
     * 更新缓存
     * $account_id 所属账户id
     * $pid 所属账户pid
     */
    public static  function  update_emotion_redis( $account_id,$pid='' ){
    	if( empty($pid)){
	    	$pid = DwAuthAccount::getAccountPidByAccountid($account_id);
	    	if( $pid == 0){
	    		$pid = $account_id;
	    	}
    	}
    	$data = DwEmoticon::find()
    		->select('id,emoticon_cate_id,emoticon_name,emoticon_url')
    		->where(['emoticon_status'=>1])
    		->andWhere(['or',['emoticon_account_id'=>$pid],['emoticon_account_pid'=>$pid]])
    		->asArray()->all();
    	if(  !empty($data)){
    		foreach ( $data as $s_key =>$s_v){
    			$a2[$s_v['id']]['id'] = $s_v['id'];
    			$a2[$s_v['id']]['emoticon_cate_id'] = $s_v['emoticon_cate_id'];
    			$a2[$s_v['id']]['emoticon_name'] = $s_v['emoticon_name'];
    			$a2[$s_v['id']]['emoticon_url'] = $s_v['emoticon_url'];
    		}
    		$value = @json_encode($a2);
    		Yii::$app->redis->set(md5('emoticon_'.$pid),$value);
    		return $a2;
    	}else {
    		$value='';
    		Yii::$app->redis->set(md5('emoticon_'.$pid),$value);
    		return $value;
    	}
    
    }
}

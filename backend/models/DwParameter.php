<?php

namespace backend\models;

use Yii;
use Qiniu\json_decode;

/**
 */
class DwParameter extends \yii\db\ActiveRecord
{   
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dw_parameter';
    }
    
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parameter_report_num', 'parameter_report_brush', 'parameter_account_id'], 'required'],
            [['parameter_report_num', 'parameter_report_brush', 'parameter_account_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'parameter_report_num' => Yii::t('backend', 'Report Snum'),
            'parameter_report_brush' => Yii::t('backend', 'Report Interval Num'),
            'parameter_account_id' => Yii::t('backend', 'The Account'),
            'parameter_operation_id' => Yii::t('backend', 'Operatorid'),
            'parameter_time' => Yii::t('backend', 'Creat Time'),
        ];
    }

    /**
     * @inheritdoc
     * @return DwCommentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DwParameterQuery(get_called_class());
    }


     /**
     * 账户参数设置缓存
     * $pid 主账户数据
     * @return DwCQuery the active query used by this AR class.
     */
    public static function get_category_redis($pid){
        $redis = Yii::$app->redis->get($pid.'parameter');
        if(!empty($redis)){
            $redis = json_decode($redis,true);
            return $redis;
        }else{
            $redis = Dwparameter::find()
                    ->andFilterWhere(['parameter_account_pid'=>$pid])
                    ->asArray()->all();
            $redis_name = $pid.'parameter';
            if( !empty($redis)){
                foreach ( $redis as $s_key =>$s_v){
                    $a2[$s_v['parameter_account_id']]    = $s_v;  
                }
                $value = @json_encode($a2);
                Yii::$app->redis->set(md5($redis_name),$value);
            }else{
                $value='';
                Yii::$app->redis->set(md5($redis_name),'');
            }

            return $a2;

        }
    }
    
}

/**
 * This is the ActiveQuery class for [[DwComment]].
 *
 * @see DwComment
 */
class DwParameterQuery extends \yii\db\ActiveQuery
{

	/**
	 * @inheritdoc
	 * @return DwComment[]|array
	 */
	public function all($db = null)
	{ 
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return DwComment|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}


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
class DwProps extends \yii\db\ActiveRecord
{   public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dw_props';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['props_available', 'props_category_id', 'props_credit'], 'integer'],
            [['props_name'], 'string', 'max' => 20],
            [['props_description','props_img'], 'string', 'max' => 200],
            [['props_name', 'props_description','props_credit','props_account_id','props_img','props_category_id'], 'required'],
            ['props_name', 'unique'],
            [['file'],'image','skipOnEmpty' => false,'extensions' => ['png', 'jpg', 'gif'],'minWidth' => 10,'maxWidth' => 100,'minHeight' =>10,'maxHeight' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'props_available' => Yii::t('backend', 'States'),
            'props_category_id' => Yii::t('backend', 'Props Class'),
            'props_name' => Yii::t('backend', 'Item Name'),
            'props_description' => Yii::t('backend', 'Item Description'),
            'props_credit' => Yii::t('backend', 'Use Points'),
            'props_img' => Yii::t('backend', 'Props Icon'),
            'props_account_id' => Yii::t('backend', 'Account Group'),
            'props_account_pid' => Yii::t('backend', 'Account Groupid')

            //'file' => Yii::t('app', '道具图标'),
        ];
    }

    /**
     * 获取缓存
     * @return DwCQuery the active query used by this AR class.
     */
    public static function get_props_redis($pid){

        $p_redis_name = md5($pid.'props');
        $return_data =[];
       
        // 父类缓存
        $p_redis = Yii::$app->redis->get($p_redis_name);
        if(!empty($p_redis)){
            $redis_p = json_decode($p_redis,true);
        }else{
            $redisdata = DwProps::find()
            ->andFilterWhere(['props_account_pid'=>$pid])
            ->asArray()->all();
            if( !empty($redisdata)){
                foreach ( $redisdata as $s_key2 =>$s_v2){
                    $a3[$s_v2['props_name']] = $s_v2;
                }
                $redisdata = @json_encode($a3);
            }else{
                $redisdata = '';
            }
            $redis_name = $pid.'props';
            $p_redis = Yii::$app->redis->set(md5($redis_name),$redisdata);
        }
           
          
        return $p_redis;
    }


    /**
     * @inheritdoc
     * @return DwPropsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DwPropsQuery(get_called_class());
    }
    /**
     * 获取道具缓存
     * @return DwPropsQuery the active query used by this AR class.
     */
    // public  static function get_props_redis()
    // {
    // 	$redis = Yii::$app->redis->get('props');
    // 	$value = '';
    // 	if( $redis ){
    // 		$value = json_decode($redis,true);
    // 	}
    // 	return $value;
    // }
   
}



/**
 * This is the ActiveQuery class for [[DwProps]].
 *
 * @see DwProps
 */
class DwPropsQuery extends \yii\db\ActiveQuery
{
	/*public function active()
	 {
	return $this->andWhere('[[status]]=1');
	}*/

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

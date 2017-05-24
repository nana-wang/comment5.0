<?php

namespace backend\models;

use Yii;
use Qiniu\json_decode;

/**
 * This is the model class for table "dw_props_category".
 *
 * @property integer $id
 * @property string $props_category_name
 * @property integer $props_category_stats
 */
class DwPropsCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dw_props_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
	        [['props_category_name','props_account_id'], 'required'],
	        //['props_category_name', 'unique'],
            [['props_category_name'], 'unique'],
            [['props_category_name'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'props_category_name' => Yii::t('backend', 'Category Name'),
            'props_account_id' => Yii::t('backend', 'Account Group'),
            'props_account_pid' => Yii::t('backend', 'Account Patent Group'),
        ];
    }

    /**
     * @inheritdoc
     * @return DwPropsCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DwPropsCategoryQuery(get_called_class());
    }
    
    /**
     * 获取道具分类缓存
     * $pid 主账户数据
     * @return DwCQuery the active query used by this AR class.
     */
    public static function get_category_redis($pid){
        $redis = Yii::$app->redis->get(md5('props_category_'.$pid));
        if(!empty($redis)){
            $redis = json_decode($redis,true);
            return $redis;
        }else{
           $data = DwPropsCategory::find()->where(['props_account_pid'=>$pid])->asArray()->all();

           if( !empty($data)){
                foreach ( $data as $s_key =>$s_v){
                    $a2[$s_v['id']] = $s_v;
                }
                $value = @json_encode($a2);
                $redis = Yii::$app->redis->set(md5('props_category_'.$pid),$value);
                return $a2;
           }else{
                $value='';
                $redis = Yii::$app->redis->set(md5('props_category_'.$pid),'');
                return $value;
           }
        }
    }

   
    /**
     * 获取某个道具的分类
     * $account_id pid
     */
    public static  function  get_ropsname($pid,$id){
        $data = DwPropsCategory::get_category_redis($pid);
        $msg = Yii::t('backend', 'Non Existent');
        if(isset($data[$id])){
            return $data[$id]['props_category_name'];
        }else{
            return $msg;
        }
    }

    /**
     * 获取道具缓存
     * @return DwPropsQuery the active query used by this AR class.
     */
    public  static function get_category_redis_all()
    {
    	$redis = Yii::$app->redis->get('props_category');
    	$value = '';
    	if( $redis ){
    		$value = json_decode($redis,true);
    	}
    	return $value;
    }
}




/**
 * This is the ActiveQuery class for [[DwPropsCategory]].
 *
 * @see DwPropsCategory
 */
class DwPropsCategoryQuery extends \yii\db\ActiveQuery
{
	/*public function active()
	 {
	return $this->andWhere('[[status]]=1');
	}*/

	/**
	 * @inheritdoc
	 * @return DwPropsCategory[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return DwPropsCategory|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}


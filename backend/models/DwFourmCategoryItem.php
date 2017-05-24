<?php

namespace backend\models;

use Yii;
use GuzzleHttp\json_encode;

/**
 * This is the model class for table "dw_fourm_category_item".
 *
 * @property string $id
 * @property string $fourm_item_title
 * @property integer $fourm_item_idtype
 * @property string $fourm_item_content
 * @property integer $fourm_item_is_ver
 */
class DwFourmCategoryItem extends \yii\db\ActiveRecord
{
	//public $fourm_item_stats = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dw_fourm_category_item';
    }
    
    public function getFourmCategoryItemExt()
    {
    	/**
    	 * 第一个参数为要关联的字表模型类名称，
    	 *第二个参数指定 通过子表的 customer_id 去关联主表的 id 字段
    	 */
    	return $this->hasMany(DwFourmCategoryItemExt::className(), ['item_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fourm_item_idtype', 'fourm_item_is_ver'], 'integer'],
            [['fourm_item_content'], 'string'],
            [['fourm_item_tag_type'], 'safe'],
            [['fourm_item_title'], 'string', 'max' => 200],
            [['fourm_item_title', 'fourm_item_is_ver', 'fourm_item_idtype', 'fourm_item_content', 'fourm_item_account'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'fourm_item_title' => Yii::t('backend', 'Names'),
            'fourm_item_idtype' => Yii::t('backend', 'Form Type'),
            'fourm_item_content' => Yii::t('backend', 'Parameter Content'),
            'fourm_item_is_ver' => Yii::t('backend', 'Ifrequired'),
            'fourm_item_tag' => Yii::t('backend', 'Tagcombin'),
            'fourm_item_stats' => Yii::t('backend', 'States'),
            'fourm_item_tag_type' => Yii::t('backend', 'Tag Score Type'),
            
        ];
    }

    /**
     * @inheritdoc
     * @return DwCQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DwFourmCategoryItemQuery(get_called_class());
    }
    
    /**
     * 表单设定 中的类型
     * @return DwCQuery the active query used by this AR class.
     */
    public static function get_fourm_item_idtype($id)
    {
        $words = Yii::t('backend', 'Words');
        $pic = Yii::t('backend', 'Pic');
        $tags = Yii::t('backend', 'Tags');
        $nots = Yii::t('backend', 'Nots');
    	if($id == 1){
    		return $words;
    	}elseif($id == 2){
    		return $pic;
    	}elseif($id == 3){
    		return $tags;
    	}else{
    		return $nots;
    	}
    	
    }
    /**
     * 获取单个表单设定的名称
     * @return DwCQuery the active query used by this AR class.
     */
    public static function get_fourm_item_name($ids)
    {
        $unuseful = Yii::t('backend', 'Unuseful');
    	if( !empty($ids)){
//     		$redis = Yii::$app->redis->get('fourm_item');
//     		$redis = json_decode($redis,true);
    		$redis = DwFourmCategoryItem::get_fourm_item_redis();
    		$id_array = explode(',',$ids);
    		$str='';
    		foreach ($id_array as $key=>$v){
    			if(isset($redis[$v]) ){
    				$str .= $redis[$v]['fourm_item_title'];
    				
    				if(($redis[$v]['fourm_item_stats'] ==  1)){
    					$str .= '('.$unuseful.')';
    				}
    				$str .='<br>';
    			}else{
    				$str .='('.$v.''.$unuseful.')<br>';
    			}
    		}
    		return $str;
    	}
    	 
    }
    /**
     * 获取单个表单设定的名称
     * @return DwCQuery the active query used by this AR class.
     */
    public static function get_fourm_item_redis_byid($id)
    {
    	if( !empty($id)){
//     		$redis = Yii::$app->redis->get('fourm_item');
//     		$redis = json_decode($redis,true);
    		$redis = DwFourmCategoryItem::get_fourm_item_redis();
    		if( isset($redis[$id])){
    			return $redis[$id];
    		}else{
    			$redis_id = DwFourmCategoryItem::find()->where(['id'=>$id])->asArray()->one();
    			if( $redis_id['fourm_item_idtype'] == 3 ){
    				$ext = DwFourmCategoryItemExt::find()->where(['item_id'=>$redis_id['id']])->asArray()->all();
    				$redis_id['fourmCategoryItemExt'] = $ext;
    			}else{
    				$redis_id['fourmCategoryItemExt'] = [];
    			}
    			$redis[$id] = $redis_id;
    			Yii::$app->redis->set('fourm_item',json_encode($redis));
    			return $redis_id;
    		}
    		
    	}
    
    }
    
    
    /**
     * 获取表单设定项缓存
     * @return DwCQuery the active query used by this AR class.
     */
    public static function get_fourm_item_redis(){
    	$redis = Yii::$app->redis->get('fourm_item');
    	if(!empty($redis)){
    		$redis = json_decode($redis,true);
    		return $redis;
    	}else{
    		$redis = DwFourmCategoryItem::find()->joinWith('fourmCategoryItemExt')->orderBy('fourm_item_idtype')->asArray()->all();
    		if(  !empty($redis)){
    			foreach ( $redis as $s_key =>$s_v){
    				$a2[$s_v['id']]['id'] = $s_v['id'];
    				$a2[$s_v['id']]['fourm_item_title'] = $s_v['fourm_item_title'];
    				$a2[$s_v['id']]['fourm_item_tag_type'] = $s_v['fourm_item_tag_type'];
    				$a2[$s_v['id']]['fourm_item_idtype'] = $s_v['fourm_item_idtype'];
    				$a2[$s_v['id']]['fourm_item_content'] = $s_v['fourm_item_content'];
    				$a2[$s_v['id']]['fourm_item_is_ver'] = $s_v['fourm_item_is_ver'];
    				$a2[$s_v['id']]['fourmCategoryItemExt'] = $s_v['fourmCategoryItemExt'];
    				 
    			}
    			$value = @json_encode($a2);
    			Yii::$app->redis->set('fourm_item',$value);
    			return $a2;
    		}else{
    			$value='';
    			Yii::$app->redis->set('fourm_item','');
    			return $value;
    		}
    	}
    	
    }
}

/**
 * This is the ActiveQuery class for [[DwFourmCategoryItem]].
 *
 * @see DwFourmCategoryItem
 */
class DwFourmCategoryItemQuery extends \yii\db\ActiveQuery
{
	/*public function active()
	 {
	return $this->andWhere('[[status]]=1');
	}*/

	/**
	 * @inheritdoc
	 * @return DwFourmCategoryItem[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return DwFourmCategoryItem|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}

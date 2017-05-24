<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dw_fourm_category".
 *
 * @property string $id
 * @property string $fourm_title
 * @property integer $fourm_idtype_id
 * @property integer $fourm_order
 * @property integer $fourm_meth
 * @property integer $fourm_pess
 * @property integer $fourm_number
 * @property integer $fourm_reply
 * @property integer $fourm_anonymous
 * @property integer $fourm_dateline
 * @property integer $fourm_actions_uid
 * @property string $fourm_actions_ip
 */
class DwFourmCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dw_fourm_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fourm_order', 'fourm_meth', 'fourm_pess', 'fourm_number', 'fourm_reply', 'fourm_anonymous', 'fourm_dateline', 'fourm_actions_uid'], 'integer'],
            [['fourm_title', 'fourm_actions_ip'], 'string', 'max' => 200],
            [['fourm_title', 'fourm_idtype_id', 'fourm_order', 'fourm_meth', 'fourm_pess', 'fourm_number', 'fourm_reply', 'fourm_anonymous','fourm_account'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'fourm_title' => Yii::t('backend', 'Comment Form Name'),
            'fourm_idtype_id' => Yii::t('backend', 'Form Type'),
            'fourm_order' => Yii::t('backend', 'Sort '),
            'fourm_meth' => Yii::t('backend', 'Release Method'),
            'fourm_pess' => Yii::t('backend', 'Modify Permission '),
            'fourm_number' => Yii::t('backend', 'User Comment Set'),
            'fourm_reply' => Yii::t('backend', 'Comment Replyly '),
            'fourm_anonymous' => Yii::t('backend', 'Whether Anonymous'),
            'fourm_dateline' => Yii::t('backend', 'Times'),
            'fourm_actions_uid' => Yii::t('backend', 'Operator'),
            'fourm_actions_ip' => Yii::t('backend', 'Operator IP'),
        ];
    }

    /**
     * @inheritdoc
     * @return DwFourmCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DwFourmCategoryQuery(get_called_class());
    }
    
    /**
     * @inheritdoc
     * @return DwCommentQuery the active query used by this AR class.
     */
    public static function get_category($id)
    {   $data = '';
// 	    $redis = Yii::$app->redis->get('fourm_category');
	   $redis = DwFourmCategory::get_fourm_category_redis();
	    if( !empty($redis) ){
	    	if( isset($redis[$id])){
	    		$data = $redis[$id]['fourm_title'];
	    	}
	    }
	    return $data;
    }
    
    /**
     * 获取表单类型缓存
     * @return DwCQuery the active query used by this AR class.
     */
    public static function get_fourm_category_redis(){
    	$redis = Yii::$app->redis->get('fourm_category');
    	if(!empty($redis)){
    		$redis = json_decode($redis,true);
    		return $redis;
    	}else{
    		$redis = DwFourmCategory::find()->asArray()->all();
    		if(  !empty($redis)){
    			foreach ( $redis as $s_key =>$s_v){
    				$a2[$s_v['id']]['id'] = $s_v['id'];
    				$a2[$s_v['id']]['fourm_title'] = $s_v['fourm_title'];
    				$a2[$s_v['id']]['fourm_idtype_id'] = $s_v['fourm_idtype_id'];
    				$a2[$s_v['id']]['fourm_order'] = $s_v['fourm_order'];
    				$a2[$s_v['id']]['fourm_meth'] = $s_v['fourm_meth'];
    				$a2[$s_v['id']]['fourm_pess'] = $s_v['fourm_pess'];
    				$a2[$s_v['id']]['fourm_number'] = $s_v['fourm_number'];
    				$a2[$s_v['id']]['fourm_reply'] = $s_v['fourm_reply'];
    				$a2[$s_v['id']]['fourm_anonymous'] = $s_v['fourm_anonymous'];
    				$a2[$s_v['id']]['fourm_account'] = $s_v['fourm_account'];
    			}
    			$value = @json_encode($a2);
    			$redis = Yii::$app->redis->set('fourm_category',$value);
    			return $a2;
    		}else{
    			$value='';
    			$redis = Yii::$app->redis->set('fourm_category','');
    			return $value;
    		}
    		
    	}
    }
}



/**
 * This is the ActiveQuery class for [[DwFourmCategory]].
 *
 * @see DwFourmCategory
 */
class DwFourmCategoryQuery extends \yii\db\ActiveQuery
{
	/*public function active()
	 {
	return $this->andWhere('[[status]]=1');
	}*/

	/**
	 * @inheritdoc
	 * @return DwFourmCategory[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return DwFourmCategory|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
	
	
}

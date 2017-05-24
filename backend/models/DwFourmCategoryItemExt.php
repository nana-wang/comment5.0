<?php

namespace backend\models;
use Yii;

/**
 * This is the model class for table "dw_fourm_category_item_ext".
 *
 * @property integer $id
 * @property integer $item_id
 * @property integer $item_idtype
 * @property integer $item_tag_type
 * @property string $item_tag_name
 */
class DwFourmCategoryItemExt extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'dw_fourm_category_item_ext';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
// 		[['id', 'item_id', 'item_idtype', 'item_tag_type'], 'integer'],
// 		[['item_tag_name'], 'string', 'max' => 255],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
		'id' => Yii::t('backend', 'ID'),
		'item_id' => Yii::t('backend','Fourm Setid'),
		'item_idtype' => Yii::t('backend','Fourm Set Type'),
		'item_tag_type' => Yii::t('backend','Tags Set Type'),
		'item_tag_name' => Yii::t('backend','Tags Name'),
		];
	}

	/**
	 * @inheritdoc
	 * @return DwFourmCategoryItemExtQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new DwFourmCategoryItemExtQuery(get_called_class());
	}
	
	/**
	 * 获取具体标签选项
	 * @return DwFourmCategoryItemExtQuery the active query used by this AR class.
	 */
	public static function getTagRedisByid($id)
	{
		$redis = Yii::$app->redis->get('fourm_item');
    	$redis = json_decode($redis,true);
    	$ext_tag = '';
    	if( isset($redis[$id])){
    		foreach ($redis[$id]['fourmCategoryItemExt'] as $key => $v ){
    			$ext_tag .=$v['item_tag_name'] . ',';
    		}
    	}else{
    		$msg = Yii::t('backend', 'Non Existent');
    		$ext_tag = $msg;
    	}
    	return $ext_tag;
	}
}

class DwFourmCategoryItemExtQuery extends \yii\db\ActiveQuery
{
	/*public function active()
	 {
	return $this->andWhere('[[status]]=1');
	}*/

	/**
	 * @inheritdoc
	 * @return DwFourmCategoryItemExt[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return DwFourmCategoryItemExt|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}

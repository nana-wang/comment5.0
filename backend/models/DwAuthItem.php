<?php

namespace backend\models;

use Yii;
use Qiniu\json_decode;
use GuzzleHttp\json_encode;

/**
 * This is the model class for table "dw_auth_account".
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property integer $pid
 * @property integer $created_at
 * @property integer $updated_at
 */
class DwAuthItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dw_auth_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'name'),
            'type' => Yii::t('app', 'type'),
            'description' => Yii::t('app', 'description'),
            'rule_name' => Yii::t('app', 'rule_name'),
            'data' => Yii::t('app', 'data'),
            'created_at' => Yii::t('app', 'created_at'),
            'updated_at' => Yii::t('app', 'updated_at'),
        ];
    }

    /**
     * @inheritdoc
     * @return DwAuthAccountQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DwAuthItemQuery(get_called_class());
    }
    
}


/**
 * This is the ActiveQuery class for [[DwAuthAccount]].
 *
 * @see DwAuthAccount
 */
class DwAuthItemQuery extends \yii\db\ActiveQuery
{
	/*public function active()
	 {
	return $this->andWhere('[[status]]=1');
	}*/

	/**
	 * @inheritdoc
	 * @return DwAuthAccount[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return DwAuthAccount|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}

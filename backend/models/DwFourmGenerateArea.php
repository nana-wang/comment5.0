<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dw_fourm_generate_area".
 *
 * @property string $id
 * @property string $fourm_area
 * @property integer $fourm_actions_uid
 * @property integer $fourm_dateline
 */
class DwFourmGenerateArea extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dw_fourm_generate_area';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fourm_actions_uid', 'fourm_dateline'], 'integer'],
            [['fourm_area'], 'string', 'max' => 200],
            [['fourm_area'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'fourm_area' => Yii::t('backend', 'Use Area'),
            'fourm_actions_uid' => Yii::t('backend', 'Operator'),
            'fourm_dateline' => Yii::t('backend', 'Times'),
        ];
    }

    /**
     * @inheritdoc
     * @return DwFourmGenerateAreaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DwFourmGenerateAreaQuery(get_called_class());
    }
}


/**
 * This is the ActiveQuery class for [[DwFourmGenerateArea]].
 *
 * @see DwFourmGenerateArea
 */
class DwFourmGenerateAreaQuery extends \yii\db\ActiveQuery
{
	/*public function active()
	 {
	return $this->andWhere('[[status]]=1');
	}*/

	/**
	 * @inheritdoc
	 * @return DwFourmGenerateArea[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return DwFourmGenerateArea|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}

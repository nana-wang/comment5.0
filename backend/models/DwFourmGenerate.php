<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dw_fourm_generate".
 *
 * @property string $id
 * @property integer $fourm_generate_area
 * @property integer $fourm_category_id
 * @property string $fourm_css
 * @property string $fourm_template
 * @property string $fourm_code
 * @property integer $fourm_dateline
 * @property integer $fourm_actions_uid
 * @property string $fourm_generate_path
 */
class DwFourmGenerate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dw_fourm_generate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//             [['fourm_generate_area', 'fourm_category_id', 'fourm_dateline', 'fourm_actions_uid'], 'integer'],
//             [['fourm_css', 'fourm_template', 'fourm_code'], 'string'],
//             [['fourm_generate_path'], 'string', 'max' => 300],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'fourm_generate_area' => Yii::t('backend', 'Account Group'),
            'fourm_category_id' => Yii::t('backend', 'Form ID'),
            'fourm_css' => Yii::t('backend', 'Form Style'),
            'fourm_template' => Yii::t('backend', 'Form Template'),
            'fourm_code' => Yii::t('backend', 'JS Call Code'),
            'fourm_dateline' => Yii::t('backend', 'Times'),
            'fourm_actions_uid' => Yii::t('backend', 'Operator UID'),
            'fourm_generate_path' => Yii::t('backend', 'Generate Path'),
        ];
    }

    /**
     * @inheritdoc
     * @return DwQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DwFourmGenerateQuery(get_called_class());
    }
}


/**
 * This is the ActiveQuery class for [[DwFourmGenerate]].
 *
 * @see DwFourmGenerate
 */
class DwFourmGenerateQuery extends \yii\db\ActiveQuery
{
	/*public function active()
	 {
	return $this->andWhere('[[status]]=1');
	}*/

	/**
	 * @inheritdoc
	 * @return DwFourmGenerate[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return DwFourmGenerate|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}

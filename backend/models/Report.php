<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%dw_sensitive}}".
 *
 * @property string $id
 * @property integer $sensitive_level_id
 * @property string $sensitive_name
 * @property string $sensitive_replace
 * @property integer $sensitive_action
 * @property integer $sensitive_time
 */
class Report extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%report}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//             [['report_idtype', 'report_uid'], 'required'],
//             [['report_from_uid', 'report_create', 'report_status'], 'integer'],
//             [['report_content_title'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'report_idtype' => Yii::t('backend', 'Report Type'),
            'report_uid' => Yii::t('backend', 'Beinformants'),
            'report_from_uid' => Yii::t('backend', 'Informants'),
            'report_url' => Yii::t('backend', 'Url'),
            'report_content_title' => Yii::t('backend', 'Titles'),
            'report_content' => Yii::t('backend', 'Content'),
            'report_create' => Yii::t('backend', 'Times'),
            'report_status' => Yii::t('backend', 'States'),
            'report_account' => Yii::t('backend', 'The Account'),
            
        ];
    }
    
    public function getCommentExp()
    {
    	/**
    	 * 第一个参数为要关联的字表模型类名称，
    	 *第二个参数指定 通过子表的 customer_id 去关联主表的 id 字段
    	 */
    	return $this->hasOne(DwCommentExp::className(), ['id' => 'report_comment_id']);
    }
    

    /**
     * @inheritdoc
     * @return DwSensitiveQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ReportQuery(get_called_class());
    }
}

/**
 * This is the ActiveQuery class for [[DwSensitive]].
 *
 * @see DwSensitive
 */
class ReportQuery extends \yii\db\ActiveQuery
{
	/*public function active()
	 {
	return $this->andWhere('[[status]]=1');
	}*/

	/**
	 * @inheritdoc
	 * @return DwSensitive[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return DwSensitive|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
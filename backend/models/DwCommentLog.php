<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dw_comment_exp".
 *
 * @property string $id
 * @property string $comment_content
 * @property integer $comment_dateline
 */
class DwCommentLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dw_comment_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//             [['comment_content'], 'string'],
//             [['comment_dateline'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'comment_id' => Yii::t('app', 'comment_id'),
            'comment_status' => Yii::t('app', 'comment_status'),
            'operation_reason' => Yii::t('app', 'operation_reason'),
            'operation_id' => Yii::t('app', 'operation_id'),
            'operation_time' => Yii::t('app', 'operation_time'),
        ];
    }

    /**
     * @inheritdoc
     * @return DwCommentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DwCommentLogQuery(get_called_class());
    }
}



/**
 * This is the ActiveQuery class for [[DwCommentExp]].
 *
 * @see DwCommentExp
 */
class DwCommentLogQuery extends \yii\db\ActiveQuery
{

	/**
	 * @inheritdoc
	 * @return DwCommentExp[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return DwCommentExp|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}


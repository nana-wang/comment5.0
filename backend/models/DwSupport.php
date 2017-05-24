<?php

namespace backend\models;

use Yii;
use Qiniu\json_decode;

/**
 * This is the model class for table "dw_comment".
 *
 */
class DwSupport extends \yii\db\ActiveRecord
{   
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dw_comment_support';
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
            'id' => Yii::t('app', 'ID'),
            'comment_id' => Yii::t('app', 'comment_id '),
            'user_id' => Yii::t('app', 'user_id'),
            'form_user_id' => Yii::t('app', 'form_user_id'),
            'dateline' => Yii::t('app', 'dateline'),
            'ip' => Yii::t('app', 'ip'),
        ];
    }

    /**
     * @inheritdoc
     * @return DwCommentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DwSupportQuery(get_called_class());
    }
    
    
}

/**
 * This is the ActiveQuery class for [[DwComment]].
 *
 * @see DwComment
 */
class DwSupportQuery extends \yii\db\ActiveQuery
{

	/**
	 * @inheritdoc
	 * @return DwComment[]|array
	 */
	public function all($db = null)
	{ 
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return DwComment|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}


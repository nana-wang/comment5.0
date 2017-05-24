<?php

namespace backend\models;

use Yii;


/**
 * This is the model class for table "dw_comment".
 *
 */
class DwNotifyCategory extends \yii\db\ActiveRecord
{  
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dw_notify_category';
    }
    
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'name' => 'name',
            'title' => 'title',
            'content' => 'content',
        ];
    }

    /**
     * @inheritdoc
     * @return DwCommentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DwNotifyCategoryQuery(get_called_class());
    }
    
    
}

/**
 * This is the ActiveQuery class for [[DwComment]].
 *
 * @see DwComment
 */
class DwNotifyCategoryQuery extends \yii\db\ActiveQuery
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


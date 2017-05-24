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
class DwCommentExp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dw_comment_exp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment_content'], 'string'],
            [['comment_dateline'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'comment_content' => Yii::t('app', 'Comment Content'),
            'comment_dateline' => Yii::t('app', 'Comment Dateline'),
        ];
    }

    /**
     * @inheritdoc
     * @return DwCommentQuery the active query used by this AR class.
     */
    public static function findCommentContent($id)
    {
    	$content = DwCommentExp::findOne($id);
    	if( !empty($content)){
    		return $content['comment_content'];
    	}else{
    		return Yii::t('backend','No The data');
    	}
    }
    /**
     * @inheritdoc
     * @return DwCommentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DwCommentExpQuery(get_called_class());
    }
}



/**
 * This is the ActiveQuery class for [[DwCommentExp]].
 *
 * @see DwCommentExp
 */
class DwCommentExpQuery extends \yii\db\ActiveQuery
{
	/*public function active()
	 {
	return $this->andWhere('[[status]]=1');
	}*/

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


<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dw_comment_score".
 *
 * @property string $id
 * @property integer $uid
 * @property string $comment_url
 * @property integer $form_id
 * @property integer $item_id
 * @property integer $item_ext_id
 * @property integer $comment_score
 * @property integer $comment_id
 * @property integer $comment_time
 * @property string $comment_ip
 */
class DwCommentScore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dw_comment_score';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'form_id', 'item_id', 'item_ext_id','item_ext_tag_type','comment_score', 'comment_id', 'comment_time'], 'integer'],
            [['comment_url'], 'string', 'max' => 255],
            [['comment_ip'], 'string', 'max' => 200],
            [['uid'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'uid' => Yii::t('backend', 'Comment Uid'),
            'comment_url' => Yii::t('backend', 'Comment Url'),
            'form_id' => Yii::t('backend', 'Comment Form Id'),
            'item_id' => Yii::t('backend', 'Form Tag Id'),
            'item_ext_id' => Yii::t('backend', 'Extended Tag ID'),
            'item_ext_tag_type' => Yii::t('backend', 'Extended Tag ID'),
            'comment_score' => Yii::t('backend', 'Comment Score'),
            'comment_id' => Yii::t('backend', 'Comment ID'),
            'comment_time' => Yii::t('backend', 'Comment Time'),
            'comment_ip' => Yii::t('backend', 'Comment Ip'),
        ];
    }

    /**
     * @inheritdoc
     * @return DwCommentScQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DwCommentScoreQuery(get_called_class());
    }
    
    /**
     * @获取某天评论的评分
     * @return DwCommentQuery the active query used by this AR class.
     */
    public static function get_comment_score($comment_id)
    {   try {
	    	$score = DwCommentScore::find()->where(['comment_id'=>$comment_id])->asArray()->all();
	    	return $score;
	    } catch (\Exception $e) {
	    	return false;
	    }
    	
    }
}



/**
 * This is the ActiveQuery class for [[DwCommentScore]].
 *
 * @see DwCommentScore
 */
class DwCommentScoreQuery extends \yii\db\ActiveQuery
{
	/*public function active()
	 {
	return $this->andWhere('[[status]]=1');
	}*/

	/**
	 * @inheritdoc
	 * @return DwCommentScore[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * @inheritdoc
	 * @return DwCommentScore|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}


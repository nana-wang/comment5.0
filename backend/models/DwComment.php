<?php

namespace backend\models;

use Yii;
use Qiniu\json_decode;
use GuzzleHttp\json_encode;

/**
 * This is the model class for table "dw_comment".
 *
 * @property integer $id
 * @property integer $comment_user_id
 * @property integer $comment_to_user_id
 * @property string $comment_user_nickname
 * @property string $comment_title
 * @property string $comment_url
 * @property integer $comment_parent_id
 * @property integer $comment_up
 * @property integer $comment_down
 * @property integer $comment_channel_area
 * @property integer $comment_user_type
 * @property integer $comment_created_at
 * @property integer $comment_updated_at
 * @property integer $comment_examine_at
 * @property integer $comment_status
 * @property integer $comment_device
 * @property integer $comment_is_lock
 * @property integer $comment_is_hide
 * @property integer $comment_is_report
 * @property string $comment_ip
 */
class DwComment extends \yii\db\ActiveRecord
{   public $comment_ip_type;
	public $key;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dw_comment';
    }
    
    public function getCommentExp()
    {
    	/**
    	 * 第一个参数为要关联的字表模型类名称，
    	 *第二个参数指定 通过子表的 customer_id 去关联主表的 id 字段
    	 */
    	return $this->hasOne(DwCommentExp::className(), ['id' => 'id']);
    }
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment_user_id', 'comment_channel_area', 'comment_created_at', 'comment_updated_at'], 'required'],
            [['comment_parent_id', 'comment_up', 'comment_down', 'comment_channel_area', 'comment_user_type', 'comment_created_at', 'comment_updated_at', 'comment_examine_at', 'comment_status', 'comment_device', 'comment_is_lock', 'comment_is_report'], 'integer'],
            [['comment_user_nickname'], 'string', 'max' => 50],
            [['comment_user_id', 'comment_to_user_id',], 'string', 'max' => 20],
            [['comment_title', 'comment_url'], 'string', 'max' => 255],
            [['comment_ip'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'comment_user_id' => Yii::t('backend', 'Comment Person'),
            'comment_to_user_id' => Yii::t('backend', 'Byid'),
            'comment_user_nickname' => Yii::t('backend', 'Comment Name'),
            'comment_title' => Yii::t('backend', 'Comment Title'),
            'comment_url' => Yii::t('backend', 'Comment Title Url'),
            'comment_parent_id' => Yii::t('backend', 'Commentpid'),
            'comment_up' => Yii::t('backend', 'Reviewtop'),
            'comment_down' => Yii::t('backend', 'Comment Step'),
            'comment_channel_area' => Yii::t('backend', 'Account Group'),
            'comment_user_type' => Yii::t('backend', 'Form Types'),
            'comment_created_at' => Yii::t('backend', 'Comment Time'),
            'comment_updated_at' => Yii::t('backend', 'Modiaudit Time'),
            'comment_examine_at' => Yii::t('backend', 'Audit Time'),
            'comment_status' => Yii::t('backend', 'Hk Activities'),
            'comment_device' => Yii::t('backend', '1 pc  1 mobile'),
            'comment_is_lock' => Yii::t('backend', '0 default 1 lock'),
            'comment_is_hide' => Yii::t('backend', '0 show 1 hide'),
            'comment_is_report' => Yii::t('backend', '0 no 1yes'),
            'comment_ip' => Yii::t('backend', 'Commentip'),
        ];
    }

    /**
     * @inheritdoc
     * @return DwCommentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DwCommentQuery(get_called_class());
    }
    
    /**
     * @inheritdoc
     * @return DwCommentQuery the active query used by this AR class.
     */
    public static function get_area($id)
    {   $array = [Yii::t('backend', 'Hk Activities'),Yii::t('backend', 'Philosophy'),Yii::t('backend', 'Market'),Yii::t('backend', 'History')];
    	if(!isset($array[$id])){
    		return $id;
    	}else{
        return $id;
    	}
    }
    /** 获取评论的操作状态（暂无用）
     * @$comment_is_hide a 默认 b:一审 c:二审 d:锁定 e:冻结 f:敏感词 g:举报
     * @return DwCommentQuery the active query used by this AR class.
     */
    public static function comment_stat_hide($comment_is_hide)
    {   $return = '';
        $comment_stat_hide = ['a'=>Yii::t('backend', 'Normal'),'b'=>Yii::t('backend', 'First Instance'),'c'=>Yii::t('backend', 'Second Instance'),'d'=>Yii::t('backend', 'Locking'),'e'=>Yii::t('backend', 'Frozen'),'f'=>Yii::t('backend', 'Sensiwords'),'g'=>Yii::t('backend', 'Report')];
    	if( !empty($comment_is_hide )){
    		$comment_is_hide_array = explode(',',$comment_is_hide);
    		foreach ($comment_is_hide_array as $key => $v){
    			$return .=','. $comment_stat_hide[$v];
    		}
    	}
    	return $return;
    }
    
    /** 获取评论的操作状态
     * @return DwCommentQuery the active query used by this AR class.
     */
    public static function comment_stat($comment_stats)
    {  
    	$return = $comment_stats;                                                                                                                                                      
    
    $comment_stat_all = [1=>Yii::t('backend', 'Release'),2=>Yii::t('backend', 'To Examine'),3=>Yii::t('backend', 'Hidden'),4=>Yii::t('backend', 'Auto Inspection'),5=>Yii::t('backend', 'Sensitive Inspection'),6=>Yii::t('backend', 'Report Inspection'),7=>Yii::t('backend', 'User Frozen')];
   
    //$comment_stat_all = [1=>'发布',2=>'审核',3=>'隐藏',4=>'自动送审',5=>'敏感词送审',6=>'举报送审',7=>'用户冻结',8=>'用户锁定'];
	    if( !empty($comment_stats )){
	    	if( isset($comment_stat_all[$comment_stats])){
	    		$return = $comment_stat_all[$comment_stats];
	    	}
	    }
   		return $return;
    }
    
    /** 举报送审状态
     * @return DwCommentQuery the active query used by this AR class.
     */
    public static function comment_report_stat()
    {
    	return 6;
    }
    /** 敏感词送审状态
     * @return DwCommentQuery the active query used by this AR class.
     */
    public static function comment_sensitive_stat()
    {
    	return 5;
    }
    /** 所有的评论状态
     * @return DwCommentQuery the active query used by this AR class.
     */
    public static function comment_stat_all()
    {
    	//$comment_stat_all=[1=>'发布',2=>'审核',3=>'隐藏',4=>'自动送审',5=>'敏感词送审',6=>'举报送审',7=>'用户冻结'
    	$comment_stat = [1=>Yii::t('backend', 'Release'),2=>Yii::t('backend', 'To Examine'),3=>Yii::t('backend', 'Hidden'),4=>Yii::t('backend', 'Auto Inspection'),5=>Yii::t('backend', 'Sensitive Inspection'),6=>Yii::t('backend', 'Report Inspection'),7=>Yii::t('backend', 'User Frozen')];
   		return $comment_stat;
    }
    
    /** 获取评论的操作状态（审批清单编辑发布操作）
     * @return DwCommentQuery the active query used by this AR class.
     */
    public static function comment_stat_approval()
    {
    	//$comment_stat=[1=>'发布',2=>'审核',3=>'隐藏'];
    	$comment_stat = [1=>Yii::t('backend', 'Release'),2=>Yii::t('backend', 'To Examine'),3=>Yii::t('backend', 'Hidden')];
    	return $comment_stat;
    }
    /** 获取评论的操作状态（审批清单编辑发布操作原因）
     * @return DwCommentQuery the active query used by this AR class.
     */
    public static function comment_stat_edit()
    {
    	// $comment_stat_record=[4=>'自动送审',5=>'敏感词送审',6=>'举报送审'];
    	$comment_stat = [4=>Yii::t('backend', 'Auto Inspection'),5=>Yii::t('backend', 'Sensitive Inspection'),6=>Yii::t('backend', 'Report Inspection')];
    	return $comment_stat;
    }
    /** 获取评论的操作状态（审批管理检索）
     * @return DwCommentQuery the active query used by this AR class.
     */
    public static function comment_stat_approval_search()
    {
    	// $comment_stat_record=[2=>'审核',4=>'自动送审',5=>'敏感词送审',6=>'举报送审'];
    	$comment_stat = [2=>Yii::t('backend', 'To Examine'),4=>Yii::t('backend', 'Auto Inspection'),5=>Yii::t('backend', 'Sensitive Inspection'),6=>Yii::t('backend', 'Report Inspection')];
    	return $comment_stat;
    }
    /** 获取评论的操作状态（评论管理检索）
     * @return DwCommentQuery the active query used by this AR class.
     */
    public static function comment_stat_search()
    {
    	//$comment_stat_search=[1=>'发布',3=>'隐藏',7=>'用户冻结',8=>'用户锁定'];
    	$comment_stat = [1=>Yii::t('backend', 'Release'),3=>Yii::t('backend', 'Hidden'),7=>Yii::t('backend', 'User Frozen')];
    	return $comment_stat;
    }
    
    
    
    /** 获取评论的缓存
     * @return DwCommentQuery the active query used by this AR class.
     */
    public static function get_comment_redis($comment_id)
    {
    	$redis = Yii::$app->redis->get('comment_stat_'.$comment_id);
        if( !empty($redis)){
        	return json_decode($redis,true);
        }else{
//         	$data = DwComment::find()->joinWith('commentExp')->select("dw_comment.*,dw_comment_exp.comment_content")
//         				->andWhere(['dw_comment.id'=>$comment_id])
//         				->asArray()->one();
        	$data = DwComment::find()->andWhere(['id'=>$comment_id])->asArray()->one();
        	if( !empty($data)){
        		Yii::$app->redis->set('comment_stat_'.$comment_id,json_encode($data));
        	}else{
        		$data ='';
        		Yii::$app->redis->set('comment_stat_'.$comment_id,'');
        	}
        	return $data;
       
        }
    	
    }
    /** 更新评论的缓存
     * @return DwCommentQuery the active query used by this AR class.
     */
    public static function update_comment_redis($id)
    {
    	if( is_array($id)){
    		$ids = implode(',',$id);
    		$comment_data = DwComment::find()->where('id in ('.$ids.')')->asArray()->all();
    		foreach ($comment_data as $comment_data_k=>$comment_data_v){
    			$comment_data_redis = json_encode($comment_data_v);
    			Yii::$app->redis->set('comment_stat_'.$comment_data_v['id'],$comment_data_redis);
    		}
    	}else{
    		$comment_data = DwComment::find()->where(['id'=>$id])->asArray()->one();
    		$comment_data_redis = json_encode($comment_data);
    		Yii::$app->redis->set('comment_stat_'.$id,$comment_data_redis);
    	}
    	return true;
    }
    
    /**
     * @ 获取评论状态缓存
     * @return DwCommentQuery the active query used by this AR class.
     */
    public static function comment_stat_operation_redis($id)
    {  
    	$redis = Yii::$app->redis->get('comment_stat_'.$id);
    	$stat = '';
    	if( !empty($redis)){
    		$redis = json_decode($redis,true);
    		if( $redis['operation_stat'] == 1 ){
    			$stat = Yii::t('backend', 'First Instance');
    		}elseif($redis['operation_stat'] > 1){
    			$stat = Yii::t('backend', 'Second Instance');
    		}
    	}
    	return $stat;
    }
    
    /** 获取评论赞缓存
     * @return DwCommentQuery the active query used by this AR class.
     */
    public static function get_comment_support_redis($comment_id)
    {
    	// 评论赞缓存
    	$redis =  Yii::$app->redis->get(md5('comment_support_' . $comment_id));
    	$redis = json_decode($redis, true);
    	if (! empty($redis)) {
    		return $redis['count'];
    	} else {
    		return 0;
    	}
    	
    }
    /** 获取评论踩次数缓存
     * @return DwCommentQuery the active query used by this AR class.
     */
    public static function get_comment_dislike_redis($comment_id)
    {
    	$redis =  Yii::$app->redis->get(md5('comment_dislike_' . $comment_id));
    	$redis = json_decode($redis, true);
    	if (! empty($redis)) {
    		return $redis['count'];
    	} else {
    		return 0;
    	}
    }
    /** 获取评论举报次数缓存
     * @return DwCommentQuery the active query used by this AR class.
     */
    public static function get_comment_report_redis($comment_id)
    {
    	$redis =  Yii::$app->redis->get(md5('comment_report_' . $comment_id));
    	$redis = json_decode($redis, true);
    	if (! empty($redis)) {
    		return $redis['count'];
    	} else {
    		return 0;
    	}
    }
    
    
}

/**
 * This is the ActiveQuery class for [[DwComment]].
 *
 * @see DwComment
 */
class DwCommentQuery extends \yii\db\ActiveQuery
{
	/*public function active()
	 {
	return $this->andWhere('[[status]]=1');
	}*/

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


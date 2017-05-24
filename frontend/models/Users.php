<?php
namespace frontend\models;
use yii\db\ActiveRecord;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class Users extends ActiveRecord
{
    public $password2;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['expires'], 'integer'],
            [['password','password2'], 'string', 'max' => 100],
            [['password2'],'compare','compareAttribute'=>'password'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', '用户昵称'),
            'email' => Yii::t('app', '用户email'),
            'is_user_type' => Yii::t('app', '用户来源'),
            'expires' => Yii::t('app', 'token过期时间'),
            'third_id' => Yii::t('app', '第三方用户id'),
            'password' => Yii::t('app','用户密码'),
            'password2' => Yii::t('app','确认密码'),
        ];
    }

    /**
     * @inheritdoc
     * @return DwSensitiveQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UsersQuery(get_called_class());
    }
}
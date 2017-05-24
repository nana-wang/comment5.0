<?php
namespace frontend\models;
use yii\db\ActiveRecord;
use yii\base\Model;
use Yii;
/**
 * LoginForm is the model behind the login form.
 */
class BindUser extends ActiveRecord
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
            [['username', 'email','password'], 'required'],
            [['expires','third_id'], 'integer'],
            [['email'], 'email'],
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
            'username' => Yii::t('app', '用户昵称'),
            'email' => Yii::t('app', '用户email'),
            'password' => Yii::t('app','用户密码'),
            'password2' => Yii::t('app','确认密码'),
        ];
    }
}

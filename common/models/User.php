<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model.
 *
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'email' => '邮箱',
            'status' => '状态',
            'created_at' => '注册时间',
            'login_at' => '最后登录时间'
        ];
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE => '正常',
            self::STATUS_DELETED => '禁用'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username or email.
     *
     * @param string $username
     *
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::find()->where(['or', 'username = "' . $username . '"', 'email = "' . $username . '"'])
            ->andWhere(['status' => self::STATUS_ACTIVE])
            ->one();
    }

    /**
     * Finds user by password reset token.
     *
     * @param string $token password reset token
     *
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid.
     *
     * @param string $token password reset token
     *
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password.
     *
     * @param string $password password to validate
     *
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model.
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key.
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token.
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString().'_'.time();
    }

    /**
     * Removes password reset token.
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['id' => 'id']);
    }

    public function getAvatar($width = 96)
    {
        if($this->profile->avatar) {
            return $this->profile->avatar;
        }
        return $this->getDefaultAvatar($width, $width);
    }
    public static  function getDefaultAvatar($width, $height)
    {
        list ($basePath, $baseUrl) = \Yii::$app->getAssetManager()->publish("@common/widgets/upload/assets/avatars");

        $name = "avatar_" . $width."x".$height. ".png";
        if(file_exists($basePath . DIRECTORY_SEPARATOR . $name))
        {
            return $baseUrl . "/" . $name;
        }
        return $baseUrl . "/" . "avatar_200x200.png";
    }
    public function saveAvatar($avatar)
    {
        $this->profile->avatar = $avatar;
        return $this->profile->save();
    }

    public function init()
    {
        $this->on(self::EVENT_AFTER_INSERT, [$this,'afterInsertInternal']);
    }
    public function afterInsertInternal($event)
    {
        $profile = new Profile();
        $profile->id = $event->sender->id;
        $profile->save();
    }

    /**
     * 是否管理员用户
     * @return bool
     */
    public function getIsAdmin()
    {
        return $this->is_admin ? true : false;
    }

    /**
     * 今天是否已签到
     * @return bool
     */
    public function getIsSign()
    {
        if (!empty($this->sign)) {
            return date('Ymd', $this->sign->last_sign_at) == date('Ymd');
        }
        return false;
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSign()
    {
        return $this->hasOne(Sign::className(), ['user_id' => 'id']);
    }

    public function getBadge()
    {
        return '土豪';
    }
}

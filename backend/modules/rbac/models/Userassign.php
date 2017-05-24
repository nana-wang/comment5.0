<?php
namespace mdm\admin\models;
use Yii;
use yii\db\ActiveRecord;

class Userassign extends ActiveRecord {

    public static function tableName () {
        return '{{%auth_account_user_role_permission}}';
    }
}
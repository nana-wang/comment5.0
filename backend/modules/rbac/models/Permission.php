<?php
namespace mdm\admin\models;
use Yii;
use yii\db\ActiveRecord;

class Permission extends ActiveRecord {

    public static function tableName () {
        return '{{%auth_permission}}';
    }
}
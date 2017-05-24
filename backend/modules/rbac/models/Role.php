<?php
namespace mdm\admin\models;
use Yii;
use yii\db\ActiveRecord;

class Role extends ActiveRecord {

    public static function tableName () {
        return '{{%auth_item_child}}';
    }
}

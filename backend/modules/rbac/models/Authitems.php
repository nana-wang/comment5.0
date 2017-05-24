<?php
namespace mdm\admin\models;
use Yii;
use yii\db\ActiveRecord;

class Authitems extends ActiveRecord {

    public static function tableName () {
        return '{{%auth_item}}';
    }
}

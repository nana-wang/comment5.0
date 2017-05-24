<?php
namespace mdm\admin\models;
use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use backend\widgets\Menu;

class Menupermission extends ActiveRecord {

    public static function tableName () {
        return '{{%menu}}';
    }
}
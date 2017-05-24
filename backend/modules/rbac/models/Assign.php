<?php
namespace mdm\admin\models;
use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Assign extends ActiveRecord {

    public function __construct () {
        parent::__construct();
    }

    public function behaviors () {
        return [
                TimestampBehavior::className()
        ];
    }

    public static function tableName () {
        return '{{%auth_permission_assignment}}';
    }
}
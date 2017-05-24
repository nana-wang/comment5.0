<?php
namespace mdm\admin\models;
use Yii;
use yii\db\ActiveRecord;

class Detialcate extends ActiveRecord {

    public static function tableName () {
        return '{{%auth_detial_cate}}';
    }
}
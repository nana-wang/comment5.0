<?php
namespace mdm\admin\models;
use creocoder\nestedsets\NestedSetsBehavior;
use creocoder\nestedsets\NestedSetsQueryBehavior;

class MenuQuery extends \yii\db\ActiveQuery {

    public function behaviors () {
        return [
                NestedSetsQueryBehavior::className()
        ];
    }
}
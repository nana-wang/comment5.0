<?php
/**
 * @author: frank
 * Date: 16/07/28
 * Time: 下午3:11
 */

namespace backend\models;


use yii\base\Model;

class PluginsConfig extends Model
{
    public $name;
    public $value;
    public $extra;
    public $desc;
    public $type;

    public function rules()
    {
        return [
            [['name', 'extra', 'desc', 'type'], 'safe', 'on' => 'init'],
            ['value', 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '配置名',
            'value' => '配置值',
            'desc' => '配置描述',
            'extra' => '配置扩展'
        ];
    }
}
<?php
namespace mdm\admin\models;
use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Grainper extends ActiveRecord {

    public $name;

    public $description;

    public $route;

    public $menu;

    public function __construct () {
        parent::__construct();
    }

    public function behaviors () {
        return [
                TimestampBehavior::className()
        ];
    }

    public static function tableName () {
        return '{{%auth_grain}}';
    }

    /**
     * @ERROR!!!
     */
    public function rules () {
        return [
                [
                        [
                                'name'
                        ],
                        'required'
                ],
                [
                        [
                                'description'
                        ],
                        'default'
                ],
                [
                        [
                                'name'
                        ],
                        'string',
                        'max' => 200
                ],
                [
                        [
                                'pid'
                        ],
                        'integer'
                ]
        ];
    }

    /**
     * @ERROR!!!
     */
    public function attributeLabels () {
        return [
                'id' => '指定权限',
                'name' => '名称',
                'description' => '备注',
                'route' => '路由地址',
                'menu' => '所属模块'
        ];
    }
}
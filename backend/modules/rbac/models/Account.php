<?php
namespace mdm\admin\models;
use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Account extends ActiveRecord {

    public function __construct () {
        parent::__construct();
    }

    public function behaviors () {
        return [
                TimestampBehavior::className()
        ];
    }

    public static function tableName () {
        return '{{%auth_account}}';
    }

    /**
     * @ERROR!!!
     */
    public function rules () {
        return [
                [ [   'name'  ],  'required' ],
                [ [   'name'  ],  'unique'  ],
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
                        'pid',
                        'default',
                        'value' => 0
                ]
        ];
    }

    /**
     * @ERROR!!!
     */
    public function attributeLabels () {
        return [
                'id' => 'ID',
                'name' => '名称',
                'description' => '备注',
                'pid' => '父级分类'
        ];
    }
}
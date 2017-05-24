<?php
namespace mdm\admin\models;
use common\behaviors\MetaBehavior;
use common\models\behaviors\CategoryBehavior;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use common\helpers\Tree;

/**
 * This is the model class for table "{{%article}}".
 *
 * @property int $id
 * @property string $name
 * @property int $created_at
 * @property int $updated_at
 */
class Category extends \yii\db\ActiveRecord {

    /**
     * @ERROR!!!
     */
    public static function tableName () {
        return '{{%auth_account}}';
    }

    /**
     * @ERROR!!!
     */
    public function rules () {
        return [];
        // [['name'], 'required'],
        // ['module', 'string'],
        // [['pid', 'sort'], 'string'],
        // ['pid', 'default', 'value' => ''],
        // [['sort'], 'default', 'value' => 0]
    }

    /**
     * @ERROR!!!
     */
    public function attributeLabels () {
        return [
                'id' => 'ID',
                'name' => '分类名',
                'slug' => '标识',
                'pid' => '上级分类',
                'pname' => '上级分类', // 非表字段,方便后台显示
                'description' => '分类介绍',
                'article' => '文章数', // 冗余字段,方便查询
                'sort' => '排序',
                'module' => '文档类型',
                'is_nav' => 'test',
                'created_at' => '创建时间',
                'updated_at' => '更新时间'
        ];
    }

    public function attributeHints () {
        return [
                'name' => '(url里显示)'
        ];
    }

    /**
     * @ERROR!!!
     */
    public function behaviors () {
        return [
                TimestampBehavior::className(),
                [
                        'class' => MetaBehavior::className(),
                        'type' => 'category'
                ],
                CategoryBehavior::className()
        ];
    }

    public function getMetaData () {
        $model = $this->getMetaModel();
        
        $name = $model->name ?: $this->name;
        $description = $model->description ?: $this->description;
        
        return [
                $name,
                $description,
                $model->keywords
        ];
    }

    /**
     * 获取分类名
     */
    public function getPname () {
        return static::find()->select('name')
            ->where([
                'id' => $this->id
        ])
            ->scalar();
    }

    public static function lists () {
        $list = Yii::$app->cache->get('role_categoryList');
        if ($list === false) {
            $list = static::find()->select('name')
                ->indexBy('id')
                ->column();
            Yii::$app->cache->set('role_categoryList', $list);
        }
        
        return $list;
    }

    public static function tree ($list = null) {
        if (is_null($list)) {
            $list = self::find()->asArray()->all();
        }
        $tree = Tree::build($list);
        return $tree;
    }

    public static function treeListHtml ($tree = null, &$result = [], $deep = 0, 
            $separator = '&nbsp;') {
        if (is_null($tree)) {
            $tree = self::tree();
        }
        $deep ++;
        foreach ($tree as $list) {
            $list['name'] = str_repeat($separator, $deep - 1) . $list['name'];
            $result[] = $list;
            if (isset($list['children'])) {
                self::treeListHtml($list['children'], $result, $deep, 
                        $separator);
            }
        }
        return $result;
    }

    public static function treeList ($tree = null, &$result = [], $deep = 0, 
            $separator = '&nbsp;') {
        if (is_null($tree)) {
            $tree = self::tree();
        }
        $deep ++;
        foreach ($tree as $list) {
            $list['name'] = str_repeat($separator, $deep - 1) . $list['name'];
            $result[] = $list;
            if (isset($list['children'])) {
                self::treeList($list['children'], $result, $deep, $separator);
            }
        }
        return $result;
    }

    /**
     * 分类名下拉列表
     */
    public static function getDropDownlist ($tree = [], &$result = [], $deep = 0, 
            $separator = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;') {
        if (empty($tree)) {
            $tree = self::tree();
        }
        $deep ++;
        foreach ($tree as $list) {
            $result[$list['id']] = str_repeat($separator, $deep - 1) .
                     $list['name'];
            if (isset($list['children'])) {
                //创建子账户时只能选择主账户不显示子节点数据
                self::getDropDownlist($list['children'], $result, $deep);
            }
        }
        return $result;
    }

    public function getCategoryNameById ($id) {
        $list = $this->lists();
        
        return isset($list[$id]) ? $list[$id] : null;
    }

    public static function getIdByName ($name) {
        $list = self::lists();
        
        return array_search($name, $list);
    }

    public static function findByIdOrSlug ($id) {
        if (intval($id) == 0) {
            $condition = [
                    "slug" => $id
            ];
        } else {
            $condition = [
                    $id
            ];
        }
        return static::findOne($condition);
    }
}

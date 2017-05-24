<?php
namespace mdm\admin\models;
use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use backend\widgets\Menu;

class Menutree extends ActiveRecord {

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
                'id' => 'ID',
                'name' => '名称',
                'description' => '备注',
                'pid' => '父级分类'
        ];
    }

    public function getTreeeHtml () {
        $data = Menutree::find()->where(
                [
                        'parent' => null
                ])
            ->orderBy('order')
            ->asArray()
            ->all();
        if ($data) {
            foreach ($data as $value) {
                $title = $value['name'];
                $sub_model = $this->getTreeeHtmlSubData($value['id']);
                $sub_model_html = $sub_model['html'];
                if ($sub_model['ids']) {
                    if (in_array('39', explode(',', $sub_model['ids']))) {
                        // p
                    }
                    $sub_model_item = $this->getTreeeHtmlSubDataItem(
                            $sub_model['ids']); // 操作颗粒
                }
                $html .= <<<BBCODE
                            <div class="col-md-12">
                                  <div class="box box-default">
                                    <div class="box-header with-border">
                                      <h3 class="box-title">{$title}</h3>
                                      <div class="box-tools pull-right">
                                      <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    </div><!-- /.box-tools -->
                                    </div>
                                    <div class="box-body">
                                    <span class="label bg-blue">子模块</span>
                                    <hr>
                                    <div style="padding-left:20px;" class="text-muted"> {$sub_model_html}</div>
                                    <hr>
                                    <span class="label bg-blue">基本操作颗粒</span>
                                    <hr>
                                    <div style="padding-left:20px;" class="text-muted">{$sub_model_item}</div>
                                     <hr>
                                    <span class="label bg-red">管理员权限操作</span>
                                    <hr>
                                    <input name="img_emotion_type2" onclick="get_sub(this);" value="72" type="checkbox">&nbsp;&nbsp;等级设置&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input name="img_emotion_type2" onclick="get_sub(this);" value="72" type="checkbox">&nbsp;&nbsp;管理敏感词
                                     <hr>
                                    <span class="label bg-blue">协作者(普通操作者)权限操作</span>
                                    <hr>
                                    <input name="img_emotion_type2" onclick="get_sub(this);" value="72" type="checkbox">&nbsp;&nbsp;等级设置&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input name="img_emotion_type2" onclick="get_sub(this);" value="72" type="checkbox">&nbsp;&nbsp;管理敏感词
                
                                    </div><!-- /.box-body -->
                                    <!-- Loading (remove the following to stop the loading)-->
                                    <div class="overlay" id="show_loading_{$key}" style="display:none;">
                                      <i class="fa fa-refresh fa-spin"></i>
                                    </div>
                                    <!-- end loading -->
                                  </div><!-- /.box -->
                                </div>
BBCODE;
            }
        }
        return $html;
    }

    private function getTreeeHtmlSubData ($id, $manager = null) {
        if ($manager == null) {
            $data = Menutree::find()->where(
                    [
                            'parent' => $id
                    ])
                ->asArray()
                ->all();
            if ($data) {
                foreach ($data as $value) {
                    $_sub_list .= ' <input name="img_emotion_type2" onclick="get_sub(this);"  value="' .
                             $value['id'] . '" type="checkbox">&nbsp;&nbsp;' .
                             $value['name'] . '&nbsp;&nbsp;&nbsp;&nbsp;';
                    $list[] = $value['id'];
                }
                
                $_list['ids'] = implode(',', $list);
                $_list['html'] = $_sub_list;
                return $_list;
            }
            return false;
        } else {
            $data = Menutree::find()->where(
                    [
                            'parent' => $manager
                    ])
                ->asArray()
                ->all();
            if ($data) {
                foreach ($data as $value) {
                    $_sub_list .= ' <input name="img_emotion_type2" onclick="get_sub(this);"  value="' .
                             $value['id'] . '" type="checkbox">&nbsp;&nbsp;' .
                             $value['name'] . '&nbsp;&nbsp;&nbsp;&nbsp;';
                    $list[] = $value['id'];
                }
                
                $_list['ids'] = implode(',', $list);
                $_list['html'] = $_sub_list;
                return $_list;
            }
            return false;
        }
    }

    private function getTreeeHtmlSubDataItem ($id) {
        $data = Menutree::find()->where(
                [
                        'parent' => [
                                $id
                        ]
                ])
            ->asArray()
            ->all();
        if ($data) {
            foreach ($data as $value) {
                $_sub_list .= ' <input name="img_emotion_type2" onclick="get_sub(this);"  value="' .
                         $value['id'] . '" type="checkbox">&nbsp;&nbsp;' .
                         $value['name'] . '&nbsp;&nbsp;&nbsp;&nbsp;';
            }
            return $_sub_list;
        }
        return false;
    }

    /**
     * all category
     */
    public function getCategories () {
        $data = Menutree::find()->asArray()->all();
        return $data;
    }

    /**
     * sub_tree
     */
    public static function getTree ($data, $pid = 0, $lev = 1) {
        $tree = [];
        foreach ($data as $value) {
            if ($value['pid'] == $pid) {
                $value['name'] = str_repeat(
                        '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $lev) .
                         $value['name'];
                $tree[] = $value;
                $tree = array_merge($tree, 
                        self::getTree($data, $value['id'], $lev + 1));
            }
        }
        return $tree;
    }

    /**
     * category groups
     */
    public function getOptions () {
        $data = $this->getCategories();
        $tree = $this->getTree($data);
        $list = [];
        foreach ($tree as $value) {
            $list[$value['id']] = $value;
        }
        return $list;
    }
}

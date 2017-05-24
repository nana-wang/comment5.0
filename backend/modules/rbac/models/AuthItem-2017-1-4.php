<?php
namespace mdm\admin\models;
use Yii;
use yii\rbac\Item;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use mdm\admin\models\Assign;

/**
 * This is the model class for table "tbl_auth_item".
 *
 * @property string $name
 * @property int $type
 * @property string $description
 * @property string $ruleName
 * @property string $data
 * @property Item $item
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 *        
 * @since 1.0
 */
class AuthItem extends \yii\base\Model {

    public $name;

    public $type;

    public $description;

    public $ruleName;

    public $data;

    public $pid;

    public $id;

    public $menuID;

    /**
     *
     * @var Item
     */
    private $_item;

    /**
     * Initialize object.
     *
     * @param Item $item            
     * @param array $config            
     */
    public function __construct ($item, $config = []) {
        $this->_item = $item;
        if ($item !== null) {
            $this->name = $item->name;
            $this->type = $item->type;
            $this->description = $item->description;
            $this->ruleName = $item->ruleName;
            $this->data = $item->data === null ? null : Json::encode(
                    $item->data);
        }
        parent::__construct($config);
    }

    /**
     * @ERROR!!!
     */
    public function rules () {
        return [
                [
                        [
                                'ruleName'
                        ],
                        'in',
                        'range' => array_keys(
                                Yii::$app->authManager->getRules()),
                        'message' => 'Rule not exists'
                ],
                [
                        [
                                'name',
                                'type'
                        ],
                        'required'
                ],
                [
                        [
                                'name'
                        ],
                        'unique',
                        'when' => function () {
                            return $this->isNewRecord ||
                                     ($this->_item->name != $this->name);
                        }
                ],
                [
                        [
                                'type'
                        ],
                        'integer'
                ],
                [
                        [
                                'description',
                                'data',
                                'ruleName'
                        ],
                        'default'
                ],
                [
                        [
                                'name'
                        ],
                        'string',
                        'max' => 64
                ]
        ];
    }

    public function unique () {
        $authManager = Yii::$app->authManager;
        $value = $this->name;
        if ($authManager->getRole($value) !== null ||
                 $authManager->getPermission($value) !== null) {
            $message = Yii::t('yii', 
                    '{attribute} "{value}" has already been taken.');
            $params = [
                    'attribute' => $this->getAttributeLabel('name'),
                    'value' => $value
            ];
            $this->addError('name', 
                    Yii::$app->getI18n()
                        ->format($message, $params, Yii::$app->language));
        }
    }

    /**
     * @ERROR!!!
     */
    public function attributeLabels () {
        return [
                'name' => Yii::t('rbac-admin', 'Name'),
                'type' => Yii::t('rbac-admin', 'Type'),
                'description' => Yii::t('rbac-admin', 'Description'),
                'ruleName' => Yii::t('rbac-admin', 'Rule Name'),
                'data' => Yii::t('rbac-admin', 'Data'),
                'pid' => '所属账户',
                'id' => '指定权限名称',
                'menuID' => '所属模块'
        ];
    }

    /**
     * Check if is new record.
     *
     * @return bool
     */
    public function getIsNewRecord () {
        return $this->_item === null;
    }

    /**
     * Find role.
     *
     * @param string $id            
     *
     * @return null|\self
     */
    public static function find ($id) {
        $item = Yii::$app->authManager->getRole($id);
        if ($item !== null) {return new self($item);}
        
        return;
    }

    /**
     * Save role to [[\yii\rbac\authManager]].
     *
     * @return bool
     */
    public function save () {
        if ($this->validate()) {
            $manager = Yii::$app->authManager;
            if ($this->_item === null) {
                if ($this->type == Item::TYPE_ROLE) {
                    $this->_item = $manager->createRole($this->name);
                } else {
                    $this->_item = $manager->createPermission($this->name);
                }
                $isNew = true;
            } else {
                $isNew = false;
                $oldName = $this->_item->name;
            }
            $this->_item->name = $this->name;
            $this->_item->description = $this->description;
            $this->_item->ruleName = $this->ruleName;
            $this->_item->data = $this->data === null || $this->data === '' ? null : Json::decode(
                    $this->data);
            if ($isNew) {
                $rule_module = new Rule();
                $rule_module->name = $this->name;
                $rule_module->data = $this->data === null || $this->data === '' ? null : Json::decode(
                        $this->data);
                $rule_module->created_at = time();
                $rule_module->updated_at = time();
                $rule_module->account_id = isset($_POST['AuthItem']['pid']) ? $_POST['AuthItem']['pid'] : 0;
                if ($this->type == Item::TYPE_ROLE) {
                    $rule_module->save();
                } elseif ($this->type == Item::TYPE_PERMISSION) {
                    // 权限关系表
                    $permission_module = new Permission();
                    $permission_module->name = $this->name;
                    $permission_module->data = $this->data === null ||
                             $this->data === '' ? null : Json::decode(
                                    $this->data);
                    $permission_module->created_at = time();
                    $permission_module->updated_at = time();
                    $permission_module->menu_id = isset(
                            $_POST['AuthItem']['menuID']) ? $_POST['AuthItem']['menuID'] : 0;
                    $permission_module->save();
                    $_laste_id = $permission_module->id;
                    if ($_laste_id) {
                        // 权限编辑后颗粒配置入库
                        // 权限编辑后默认菜单指定第二级子模块subID
                        $request = Yii::$app->request;
                        try {
                            $userIP = Yii::$app->request->userIP;
                            $menu_ID = $_POST['AuthItem']['subID'];
                            $permission_Name = $_laste_id;
                            if ($request->isPost) {
                                $permission_ID = $_laste_id;
                                $assignment_module = new Assign();
                                $assignment_module->permission_id = $permission_ID;
                                $assignment_module->menu_id = $menu_ID;
                                $assignment_module->ip = $userIP;
                                if (! $assignment_module->save()) {
                                    throw new NotFoundHttpException(
                                            '权限颗粒入库数据错' . __LINE__ . __CLASS__);
                                    exit();
                                }
                            }
                        } catch (\Exception $e) {}
                    }
                }
                $manager->add($this->_item);
            } else {
                try {
                    // 更新角色
                    if ($this->type == Item::TYPE_ROLE) {
                        $rule_module = new Rule();
                        $rule_module->updateAll(
                                array(
                                        'name' => $this->name,
                                        'updated_at' => time(),
                                        'account_id' => isset(
                                                $_POST['AuthItem']['pid']) ? $_POST['AuthItem']['pid'] : 0
                                ), 'name=:name', 
                                array(
                                        ':name' => $oldName
                                ));
                        // 更新授权信息
                        $auth_rule_module = new Userassign();
                        $auth_rule_module->updateAll(
                                array(
                                        'role_name' => $this->name,
                                        'account_id' => isset(
                                                $_POST['AuthItem']['pid']) ? $_POST['AuthItem']['pid'] : 0
                                ), 'role_name=:role_name', 
                                array(
                                        ':role_name' => $oldName
                                ));
                    } else {
                        // 更新权限关系表
                        $permission_module = new Permission();
                        $permission_module->updateAll(
                                array(
                                        'name' => $this->name,
                                        'updated_at' => time(),
                                        'menu_id' => isset(
                                                $_POST['AuthItem']['menuID']) ? $_POST['AuthItem']['menuID'] : 0
                                ), 'name=:name', 
                                array(
                                        ':name' => $oldName
                                ));
                    }
                    $manager->update($oldName, $this->_item);
                } catch (\Exception $e) {}
            }
            
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get item.
     *
     * @return Item
     */
    public function getItem () {
        return $this->_item;
    }

    /**
     * Get type name.
     *
     * @param mixed $type            
     *
     * @return string|array
     */
    public static function getTypeName ($type = null) {
        $result = [
                Item::TYPE_PERMISSION => 'Permission',
                Item::TYPE_ROLE => 'Role'
        ];
        if ($type === null) {return $result;}
        
        return $result[$type];
    }
}

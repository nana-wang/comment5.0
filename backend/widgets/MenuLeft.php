<?php
namespace backend\widgets;
use yii\base\Widget;
use Yii;
use mdm\admin\models\Account;
use common\helpers\Tree;
use mdm\admin\models\Userassign;

class MenuLeft extends Widget {

    const ACCOUNT = 'parentToSubID_';

    const ACCOUNT_ALL = 'all_parentToSubID_';

    const ACCOUNT_SELECT = 'select_parentToSubID_';

    const MAIN_ACCOUNT = 'main_parentToSubID_';

    public function init () {
        parent::init();
        ob_start();
    }

    public function run () {
        $content = ob_get_clean();
        return $this->getAccountOptions();
    }

    /**
     * left 菜单项权限
     *
     * @return string
     */
    private function getAccountOptions () {
        $redis = Yii::$app->redis;
        // $m = new \mdm\admin\models\Category();
        // $res = $m::getDropDownlist();
        $user_data = $this->find();
        if ($user_data) {
            $option = $option2 = '';
            $cookies = Yii::$app->request->cookies;
            $cookie = $cookies->getValue('account_stat_id');
            foreach ($user_data as $key => $value) {
                if (! $this->checkAssage($key)) {
                    $select = 'disabled';
                } else {
                    if ($cookie == $key) {
                        $select = ' selected = "selected"';
                    } else {
                        $select = '';
                    }
                }
                $option .= '<option value="' . $key . '"' . $select . ' >' .
                         $value . '</option>';
                $option2 .= '<option value="' . $key . '">' . $value .
                         '</option>';
            }
            $user_id = Yii::$app->user->id;
            $redis->set(md5(self::ACCOUNT_SELECT . $user_id), $option2);
            return $option;
        }
    }

    private function checkAssage ($account_id) {
        $res = Userassign::find()->where(
                [
                        'account_id' => $account_id
                ])
            ->andWhere([
                'beoperator_userid' => \Yii::$app->user->identity->id
        ])
            ->all();
        if ($res) {
            return $res;
        } else {
            return false;
        }
    }

    /**
     *
     * @param array $tree            
     * @param array $result            
     * @param number $deep            
     * @param string $separator            
     * @return string
     */
    private static function getDropDownlist ($tree = [], &$result = [], $deep = 0, 
            $separator = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;') {
        $deep ++;
        foreach ($tree as $list) {
            $result[$list['id']] = str_repeat($separator, $deep - 1) .
                     $list['name'];
            if (isset($list['children'])) {
                self::getDropDownlist($list['children'], $result, $deep);
            }
        }
        return $result;
    }

    private function getSubID ($id, $parentID = null) {
        $res = Account::find()->select('id,pid,name')
            ->where([
                'id' => $id
        ])
            ->asArray()
            ->one();
        return $res;
    }

    /**
     * 便利树
     *
     * @param unknown $list            
     */
    private static function tree ($list = null) {
        if (is_null($list)) {
            $list = Account::find()->asArray()->all();
        }
        $tree = Tree::build($list);
        return $tree;
    }

    /**
     * 账户角色菜单权限列表
     *
     * @return void|\yii\rbac\Role[]
     */
    private $account_data = '';

    private $root = '';

    private $finalData = '';

    private $RootFinalData = '';

    private $SubFinalData = '';

    private $root_sub = '';

    private $_MenutData = '';

    public function find () {
        $cache = Yii::$app->redis;
        $login_user_id = Yii::$app->user->id;
        $item = Yii::$app->authManager->getRolesByUser($login_user_id);
        if ($item) {
            foreach ($item as $key => $value) {
                $data[] = Userassign::find()->where(
                        [
                                'beoperator_userid' => $login_user_id,
                                'role_name' => $key
                        ])
                    ->asArray()
                    ->one();
            }
            if ($data) {
                $data = array_filter($data);
            }
            // 取出授权节点账户AccountID
            if ($data) {
                foreach ($data as $key => $value) {
                    $this->account_data[] = $this->getParentID(
                            $value['account_id']);
                }
            }
            // 取出子节点父类
            if ($this->account_data && $data) {
                foreach ($this->account_data as $key => $value) {
                    // 如果是根节点取所有账户
                    if ($value['pid'] == 0) {
                        $this->root[] = $this->_getSubAll($value['id']);
                    } else {
                        // 没有根节点
                        $this->root_sub[] = $value;
                    }
                }
                
                // 根节点有数据
                if ($this->root && $this->root_sub) {
                    // 转换
                    $newArr = array();
                    foreach ($this->root as $key => $val) {
                        if ($val) {
                            foreach ($val as $k => $v) {
                                $newArr[] = $v;
                            }
                        }
                    }
                    // 取所有根下子节点
                    foreach ($newArr as $value) {
                        $get_parent_sub[$value['id']] = $this->_getParentSubAll(
                                $value['id']);
                        // 只创建根节点没有建子节点授权角色后数据
                        if ($get_parent_sub[$value['id']] == false) {
                            $get_parent_sub[][$value['id']] = $value;
                        }
                    }
                    // 转换
                    $_newArr = array();
                    foreach ($get_parent_sub as $key => $val) {
                        if ($val) {
                            foreach ($val as $k => $v) {
                                $_newArr[$v['id']] = $v;
                            }
                        }
                    }
                    // 数据组合
                    $newData = array_merge($this->root_sub, $_newArr);
                    foreach ($newData as $value) {
                        $parent_id[$value['pid']] = $this->_getSubID(
                                $value['pid']);
                    }
                    // 转换
                    $parent_arr = array();
                    foreach ($parent_id as $key => $val) {
                        if ($val) {
                            foreach ($val as $k => $v) {
                                $parent_arr[] = $v;
                            }
                        }
                    }
                    // 最终数据
                    $this->RootFinalData = array_merge($newData, $parent_arr);
                } else if ($this->root_sub) {
                    // 只有子节点数据
                    foreach ($this->root_sub as $value) {
                        $_sub_parent_sub[$value['id']] = $value;
                    }
                    foreach ($_sub_parent_sub as $value) {
                        $parent_id[$value['pid']] = $this->_getSubID(
                                $value['pid']);
                    }
                    $parent_arr = array();
                    foreach ($parent_id as $key => $val) {
                        if ($val) {
                            foreach ($val as $k => $v) {
                                $parent_arr[] = $v;
                            }
                        }
                    }
                    // 最终数据
                    $this->SubFinalData = array_merge($_sub_parent_sub, 
                            $parent_arr);
                } else {
                    // 只有多个主节点有数据
                    if (empty($this->SubFinalData) && empty(
                            $this->RootFinalData)) {
                        if ($this->root) {
                            $_parent_uniq = array();
                            foreach ($this->root as $key => $val) {
                                if ($val) {
                                    foreach ($val as $k => $v) {
                                        $_parent_uniq[] = $v;
                                    }
                                }
                            }
                        }
                        // 最终数据
                        $this->RootFinalData = $_parent_uniq;
                    }
                }
            }
            if ($this->root) {
                $this->finalData = $this->RootFinalData;
            } else {
                $this->finalData = $this->SubFinalData;
            }
            if ($this->root || $this->root_sub) {
                // 只有根节点没无子节点 只要用户账户可见说明
                if (empty($this->SubFinalData) && empty($this->RootFinalData)) {
                    // 授权数据
                    $_AllData = Tree::build($_parent_uniq);
                } else {
                    // 角色是否受权
                    foreach ($this->finalData as $key => $value) {
                        if (! $this->roleAssginCheck($value['id'])) {
                            $this->finalData[$key]['name'] = $value['name'] .
                                     '(未授权角色)';
                        }
                    }
                    // 清洗数据
                    foreach ($this->finalData as $value) {
                        $_finalData[$value['id']] = $value;
                    }
                    // 授权数据
                    $_AllData = Tree::build($_finalData);
                }
                
                // 用户所有授权账户组 tonghui
                $p_arr = $main_account = [];
                // 所有权限数据缓存
                $cache->set(md5(self::ACCOUNT_ALL . $login_user_id), 
                        json_encode($_finalData));
                
                foreach ($_finalData as $_final_key => $_final_value) {
                    if (stripos($_final_value['name'], '未授') == true) {
                        unset($_finalData[$_final_key]);
                    }
                }
                foreach ($_finalData as $_final_key => $_final_value) {
                    if (($_final_value['pid'] > 0) &&
                             isset($_finalData[$_final_value['pid']])) {
                        $p_arr[$_final_value['pid']][] = $_final_value['id'];
                    } else {
                        $p_arr[$_final_value['id']][] = $_final_value['id'];
                    }
                    if ($_final_value['pid'] == 0) {
                        $main_account[$_final_value['id']] = $_final_value['name'];
                    }
                }
                // 有权限的主账户下拉框
                $cache->set(md5(self::MAIN_ACCOUNT . $login_user_id), 
                        json_encode($main_account));
                // 数据缓存
                $cache->set(md5(self::ACCOUNT . $login_user_id), 
                        json_encode($p_arr));
                // 用户所有授权账户组 tonghui end
                if ($_AllData) {
                    // 缓存数据
                    $account_cache = '';
                    foreach ($_AllData as $key => $value) {
                        if (isset($value['children'])) {
                            $account_cache[$value['id']] = $value['children'];
                        }
                    }
                    if ($account_cache) {
                        foreach ($account_cache as $key => $value) {
                            // if (! $cache->exists(md5(self::ACCOUNT . $key)))
                            // {
                            if (is_array($value)) {
                                foreach ($value as $k => $val) {
                                    if (stripos($value[$k]['name'], '未授') == true) {
                                        unset($value[$k]);
                                    }
                                    if (isset($value[$k]['id'])) {
                                        $normal[$value[$k]['pid']][] = $value[$k]['id'];
                                    }
                                }
                            }
                            if (isset($normal[$key])) {
                                if (is_array($normal)) {
                                    foreach ($normal as $v) {
                                        // 数据缓存
                                        $cache->set(md5(self::ACCOUNT . $key), 
                                                implode(',', $v));
                                    }
                                }
                            }
                            // }
                        }
                    }
                    // echo '<pre>';
                    // print_r($cache->get(md5('parentToSubID_59')));
                    // exit;
                }
                $this->_MenutData = $this->getDropDownlist($_AllData);
            }
        }
        if ($this->_MenutData !== null) {return $this->_MenutData;}
        return;
    }

    function array_remove_value (&$arr, $var) {
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                array_remove_value($arr[$key], $var);
            } else {
                $value = trim($value);
                if ($value == $var) {
                    unset($arr[$key]);
                } else {
                    $arr[$key] = $value;
                }
            }
        }
    }

    /**
     * *
     * 子账户到主账户转换数据
     *
     * @param unknown $id            
     * @param unknown $parentID            
     * @return \yii\db\ActiveRecord|NULL
     */
    private function getParentID ($id, $parentID = null) {
        $res = Account::find()->select('id,pid,name')
            ->where([
                'id' => $id
        ])
            ->asArray()
            ->one();
        return $res;
    }

    /**
     * 角色所有ID
     *
     * @param unknown $name            
     */
    private function _getSubID ($id) {
        $res = Account::find()->select('id,pid,name')
            ->where([
                'id' => $id
        ])
            ->asArray()
            ->all();
        if ($res) {return $res;}
        return false;
    }

    /**
     * 取所有根节点数据
     *
     * @param unknown $pid            
     * @return \yii\db\ActiveRecord[]|boolean
     */
    private function _getSubAll ($pid) {
        $res = Account::find()->select('id,pid,name')
            ->where([
                'id' => $pid
        ])
            ->asArray()
            ->all();
        $list = array();
        foreach ($res as $value) {
            $list[$value['id']] = $value;
        }
        if ($list) {return $list;}
        return false;
    }

    /**
     * 检测角色是否授权
     *
     * @param unknown $id            
     * @return boolean
     */
    private function roleAssginCheck ($id) {
        $uid = Yii::$app->user->id;
        $res = Userassign::find()->where(
                [
                        'account_id' => $id,
                        'beoperator_userid' => $uid
                ])
            ->asArray()
            ->one();
        if ($res) {return true;}
        return false;
    }

    /**
     * 取所有根节点子账户
     *
     * @param unknown $pid            
     * @return \yii\db\ActiveRecord[]|boolean
     */
    private function _getParentSubAll ($pid) {
        $res = Account::find()->select('id,pid,name')
            ->where([
                'pid' => $pid
        ])
            ->asArray()
            ->all();
        if ($res) {return $res;}
        return false;
    }
}
?>
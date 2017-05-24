<?php
namespace mdm\admin\controllers;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use mdm\admin\components\MenuHelper;
use mdm\admin\models\Account;
use mdm\admin\models\Menutree;
use mdm\admin\models\Rule;
use backend\models\DwAuthAccount;
use yii\data\ActiveDataProvider;
use mdm\admin\models\Userassign;
use mdm\admin\models\Role;

/**
 * DefaultController.
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 *        
 * @since 1.0
 */
class AccountController extends \yii\web\Controller {

    public $categoryClass;

    public function behaviors () {
        return [
                'verbs' => [
                        'class' => VerbFilter::className(),
                        'actions' => [
                                'delete' => [
                                        'post'
                                ]
                        ]
                ]
        ];
    }

    /**
     * Action index.
     */
    public function actionIndex () {
        // echo '<pre>';
        $manager = Yii::$app->getAuthManager();
        // echo '<pre>';
        // 取出拥有的所有角色
        // $manager->getAssignments(6)
        // 所有权限
        // $manager->getPermissionsByUser(6)
        // print_r($manager->createRole('hk2017'));
        // exit();
        // print_r(\Yii::$app->user->identity->is_user_type);
        // print_r(\Yii::$app->user->identity->id);
        // print_r(unserialize($_COOKIE['_identityBackend']));
        // exit();
        // superAdmin 用户拥有所有权限
        // \Yii::$app->user->identity->is_user_type
        // echo '<pre>';
        // print_r(\mdm\admin\components\MenuHelper::getAssignedMenu(Yii::$app->user->id));
        // exit;
        if (\Yii::$app->user->identity->is_user_type == 'superadmin') {
            $dataProvider = new ActiveDataProvider(
                    [
                            'query' => Account::find(),
                            'pagination' => [
                                    'pageSize' => 2000
                            ]
                    ]);
            return $this->render('index', 
                    [
                            'dataProvider' => $dataProvider
                    ]);
        } else {
            throw new NotFoundHttpException(
                    "I'm sorry you have no right to access!");
        }
    }

    public function actionAccountmenu () {
        $account_id = Yii::$app->request->get('account_id');
        $_RoleName = Rule::findOne($account_id);
        $session = Yii::$app->session;
        $session->set('account_stat_id', $account_id);
        $cookies = Yii::$app->response->cookies;
        $cookies->add(
                new \yii\web\Cookie(
                        [
                                'name' => 'account_stat_id',
                                'value' => $account_id
                        ]));
        if ($account_id) {
            $checkAccountRole = Userassign::find()->where(
                    [
                            'account_id' => $account_id
                    ])
                ->asArray()
                ->all();
            foreach ($checkAccountRole as $value) {
                $routes[] = $this->getUserRouteItem($value['role_name']);
            }
            // 路由生成规则
            $routes = array_filter($routes);
            $_newArr = array();
            foreach ($routes as $key => $val) {
                if ($val) {
                    foreach ($val as $k => $v) {
                        $_newArr[$k] = $v['child'];
                    }
                }
            }
            if ($_newArr) {
                $account_data = $_newArr;
                if ($account_id) {
                    $menu_data[] = \backend\widgets\Menu::widget(
                            [
                                    'options' => [
                                            'class' => 'sidebar-menu'
                                    ],
                                    'items' => \mdm\admin\components\MenuHelper::getAssignedMenu(
                                            $account_id, $account_data)
                            ]);
                }
            } else {
                $menu_data = '';
            }
            if ($menu_data) {
                $data = $menu_data;
            } else {
                $data = '';
            }
            $html = [
                    'status' => 'ok',
                    'data' => $data
            ];
            return \yii\helpers\Json::encode($html);
        }
    }
    
    // 检测账户是否是主账户
    private function checkAccountMaster ($account_id) {
        $data = Account::findOne($account_id);
        if ($data) {
            return true;
        } else {
            return false;
        }
    }

    private function getUserRouteItem ($roleName) {
        $data = Role::find()->where(
                [
                        'parent' => $roleName
                ])
            ->select('child')
            ->asArray()
            ->all();
        if ($data) {
            return $data;
        } else {
            return false;
        }
    }

    /**
     * 子账户数据信息
     *
     * @param unknown $id            
     * @return unknown|boolean
     */
    private function getSubData ($data) {
        $data = new Menutree();
        $rc = $data->getOptions();
        $html = '';
        foreach ($rc as $value) {
            if ($value['stat'] == 1) {
                $stat = '&nbsp;&nbsp;<span data-placement="bottom" data-original-title="账户启用" data-toggle="tooltip" class="label label-danger" style="text-align:left">账户已经停用</span>';
                $restart = '<li><a href="javascript:void(0);"
												class="confirm-delete actstart" data-reload="' . $value['id'] . '" title="Delete item"><i
													class="glyphicon glyphicon-eye-close font-12"></i> 账户启用</a></li>';
            } else {
                $stat = '';
                $restart = '';
            }
            if ($value['pid'] == 0) {
                $t = '<td style="padding-left: 0px;"><i class="caret"></i>&nbsp;&nbsp;<a href="###" class="text-muted">' .
                         $value['name'] . '</a>' . $stat . '</td>';
            } else {
                $t = '<td style="padding-left: 20px;">&nbsp;&nbsp;<a href="###" class="text-muted">' .
                         $value['name'] . '</a>' . $stat . '</td>';
            }
            $html .= '<tr>
								<td width="50">' . $value['id'] . '</td>
								    ' . $t . '
								<td width="120" class="text-right">
									<div class="dropdown actions">
										<i id="dropdownMenu1" data-toggle="dropdown"
											aria-expanded="true" title="Actions"
											class="glyphicon glyphicon-menu-hamburger"></i>
										<ul class="dropdown-menu dropdown-menu-right" role="menu"
											aria-labelledby="dropdownMenu1">
											<li><a href="index.php?r=rbac%2Faccount%2Fupdate&id=' .
                     $value['id'] . '"><i
													class="glyphicon glyphicon-pencil font-12"></i> 账户编辑 </a></li>
											<li><a href="index.php?r=rbac%2Faccount%2Fcreate"><i
													class="glyphicon glyphicon-plus font-12"></i> 账户添加</a></li>
                             ' . $restart;
            
            if (empty($restart)) {
                $html .= '<li><a href="javascript:void(0);"
												class="confirm-delete actstop" data-reload="' . $value['id'] . '" title="Delete item"><i
													class="glyphicon glyphicon-eye-close font-12"></i> 账户停用</a></li>';
            }
            
            $html .= '<li><a href="javascript:void(0);"
												class="confirm-delete actdel" data-reload="' . $value['id'] . '" title="Delete item"><i
													class="glyphicon glyphicon-remove font-12"></i> 账户删除</a></li>
										</ul>
									</div>
								</td>
							</tr>';
        }
        return $html;
    }

    /**
     * 账户删除
     *
     * @throws NotFoundHttpException
     * @return string
     */
    public function actionDelete () {
        try {
            $id = Yii::$app->request->post('id');
            if (empty($id)) {
                $array = [
                        'stauts' => 'error',
                        'message' => '操作失败!更新角色账户关系数据错!' . __LINE__ . __CLASS__
                ];
                return \yii\helpers\Json::encode($array);
            }
            try {
                // stat 0 默认正常 1 停用 2 删除
                $customer = Account::findOne($id);
                if ($customer->delete()) {
                    // 更新角色账户关系
                    $rule_module = new Rule();
                    $_flage = $rule_module->updateAll(
                            array(
                                    'stat' => '2'
                            ), 'account_id=:account_id', 
                            array(
                                    ':account_id' => $id
                            ));
                    if (! $_flage) {
                        throw new NotFoundHttpException(
                                '更新角色账户关系数据错 auth_permission 没有找到数据!' . __LINE__ .
                                         __CLASS__);
                        exit();
                    }
                } else {
                    throw new NotFoundHttpException(
                            '更新角色账户关系数据错 auth_permission 没有找到数据!' . __LINE__ .
                                     __CLASS__);
                    exit();
                }
            } catch (\Exception $e) {}
            
            MenuHelper::invalidate();
            
            // 更新账户数据缓存
            DwAuthAccount::updateAccountRedis();
            $array = [
                    'stauts' => 'ok',
                    'message' => '操作完成'
            ];
            return \yii\helpers\Json::encode($array);
        } catch (\Exception $e) {
            $array = [
                    'stauts' => 'error',
                    'message' => '操作失败!更新角色账户关系数据错!' . __LINE__ . __CLASS__
            ];
            return \yii\helpers\Json::encode($array);
        }
    }

    /**
     * 账户停用
     *
     * @throws NotFoundHttpException
     * @return string
     */
    public function actionStop () {
        try {
            $id = Yii::$app->request->post('id');
            if (empty($id)) {
                $array = [
                        'stauts' => 'error',
                        'message' => '操作失败!更新角色账户关系数据错!' . __LINE__ . __CLASS__
                ];
                return \yii\helpers\Json::encode($array);
            }
            try {
                // 账户停用状态
                $Account = new Account();
                $_flage = $Account->updateAll(
                        array(
                                'stat' => 1
                        ), 'id=:id', 
                        array(
                                ':id' => $id
                        ));
                
                if ($_flage) {
                    // 更新角色账户状态
                    $rule_module = new Rule();
                    $_flage = $rule_module->updateAll(
                            array(
                                    'stat' => 1
                            ), 'account_id=:account_id', 
                            array(
                                    ':account_id' => $id
                            ));
                    if (! $_flage) {
                        throw new NotFoundHttpException(
                                '更新角色账户关系数据错 auth_permission 没有找到数据!' . __LINE__ .
                                         __CLASS__);
                        exit();
                    }
                } else {
                    throw new NotFoundHttpException(
                            '更新角色账户关系数据错 auth_permission 没有找到数据!' . __LINE__ .
                                     __CLASS__);
                    exit();
                }
            } catch (\Exception $e) {}
            
            MenuHelper::invalidate();
            $array = [
                    'stauts' => 'ok',
                    'message' => '操作完成'
            ];
            return \yii\helpers\Json::encode($array);
        } catch (\Exception $e) {
            $array = [
                    'stauts' => 'error',
                    'message' => '操作失败!更新角色账户关系数据错!' . __LINE__ . __CLASS__
            ];
            return \yii\helpers\Json::encode($array);
        }
    }

    /**
     * 账户启用
     *
     * @throws NotFoundHttpException
     * @return string
     */
    public function actionStart () {
        try {
            $id = Yii::$app->request->post('id');
            if (empty($id)) {
                $array = [
                        'stauts' => 'error',
                        'message' => '操作失败!更新角色账户关系数据错!' . __LINE__ . __CLASS__
                ];
                return \yii\helpers\Json::encode($array);
            }
            try {
                // 账户停用状态
                $Account = new Account();
                $_flage = $Account->updateAll(
                        array(
                                'stat' => 0
                        ), 'id=:id', 
                        array(
                                ':id' => $id
                        ));
                
                if ($_flage) {
                    // 更新角色账户状态
                    $rule_module = new Rule();
                    $_flage = $rule_module->updateAll(
                            array(
                                    'account_id' => $id,
                                    'stat' => 0
                            ), 'account_id=:account_id', 
                            array(
                                    ':account_id' => $id
                            ));
                    if (! $_flage) {
                        throw new NotFoundHttpException(
                                '更新角色账户关系数据错 auth_permission 没有找到数据!' . __LINE__ .
                                         __CLASS__);
                        exit();
                    }
                } else {
                    throw new NotFoundHttpException(
                            '更新角色账户关系数据错 auth_permission 没有找到数据!' . __LINE__ .
                                     __CLASS__);
                    exit();
                }
            } catch (\Exception $e) {}
            
            MenuHelper::invalidate();
            $array = [
                    'stauts' => 'ok',
                    'message' => '操作完成'
            ];
            return \yii\helpers\Json::encode($array);
        } catch (\Exception $e) {
            $array = [
                    'stauts' => 'error',
                    'message' => '操作失败!更新角色账户关系数据错!' . __LINE__ . __CLASS__
            ];
            return \yii\helpers\Json::encode($array);
        }
    }

    public function actionView ($id) {
        $model = new Account();
        return $this->render('view', 
                [
                        'model' => $model
                ]);
    }

    public function actionUpdate ($id) {
        $model = new Account();
        $options_data = Account::find()->where(
                [
                        'id' => $id
                ])
            ->asArray()
            ->one();
        if ($options_data) {
            $optionid = $options_data['pid'];
        } else {
            $optionid = '';
        }
        if ($model->load(Yii::$app->getRequest()
            ->post()) && $model->validate()) {
            $name = $_POST['Account']['name'];
            $description = $_POST['Account']['description'];
            // $pid = $_POST['Account']['pid'];
            $model::updateAll(
                    array(
                            'name' => $name,
                            'description' => $description
                    ), 
                    // 'pid' => $pid
                    'id=:id', 
                    array(
                            ':id' => $id
                    ));
            MenuHelper::invalidate();
            // 更新账户数据缓存
            DwAuthAccount::updateAccountRedis();
            return $this->redirect([
                    'index'
            ]);
        }
        $model = Account::find()->where([
                'id' => $id
        ])->one();
        return $this->render('update', 
                [
                        'model' => $model,
                        'optionid' => $optionid
                ]);
    }

    public function actionCreate ($id = null) {
        // 父级角色ID
        $options = new Account();
        $options_data = Account::find()->where(
                [
                        'id' => $id
                ])
            ->asArray()
            ->one();
        if ($options_data) {
            $optionid = $options_data['pid'];
        } else {
            $optionid = '0';
        }
        
        $model = new Account();
        if ($model->load(Yii::$app->getRequest()
            ->post()) && $model->save()) {
            MenuHelper::invalidate();
            
            // 更新账户数据缓存
            DwAuthAccount::updateAccountRedis();
            // return $this->redirect(
            // [
            // 'update',
            // 'id' => $model->id
            // ]);
            return $this->redirect([
                    'index'
            ]);
        } else {
            return $this->render('create', 
                    [
                            'model' => $model,
                            'optionid' => $optionid
                    ]);
        }
    }
}


<?php
namespace mdm\admin\controllers;
use mdm\admin\models\AuthItem;
use mdm\admin\models\searchs\AuthItem as AuthItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\rbac\Item;
use Yii;
use mdm\admin\components\MenuHelper;
use yii\web\Response;
use mdm\admin\models\Assign;
use mdm\admin\models\Permission;
use mdm\admin\models\Grainper;
use mdm\admin\models\Menupermission;

/**
 * AuthItemController implements the CRUD actions for AuthItem model.
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 *        
 * @since 1.0
 */
class PermissionController extends Controller {

    /**
     * @ERROR!!!
     */
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
     * Lists all AuthItem models.
     *
     * @return mixed
     */
    public function actionIndex () {
        $searchModel = new AuthItemSearch(
                [
                        'type' => Item::TYPE_PERMISSION
                ]);
        $dataProvider = $searchModel->search(
                Yii::$app->getRequest()
                    ->getQueryParams());
        
        return $this->render('index', 
                [
                        'dataProvider' => $dataProvider,
                        'searchModel' => $searchModel
                ]);
    }

    /**
     * Displays a single AuthItem model.
     *
     * @param string $id            
     *
     * @return mixed
     */
    public function actionView ($id) {
        $model = $this->findModel($id);
        
        return $this->render('view', 
                [
                        'model' => $model
                ]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view'
     * page.
     *
     * @return mixed
     */
    public function actionCreate () {
        // menu list
        try {
            $data = Menupermission::find()->where(
                    [
                            'parent' => null
                    ])
                ->asArray()
                ->orderBy('order')
                ->all();
            if ($data) {
                foreach ($data as $value) {
                    $_data[$value['id']] = $value['name'];
                }
            } else {
                throw new NotFoundHttpException('数据错' . __LINE__);
            }
        } catch (\Exception $e) {}
        //
        $model = new AuthItem(null);
        $model->type = Item::TYPE_PERMISSION;
        if ($model->load(Yii::$app->getRequest()
            ->post()) && $model->save()) {
            MenuHelper::invalidate();
            
            return $this->redirect(
                    [
                            'view',
                            'id' => $model->name
                    ]);
        } else {
            return $this->render('create', 
                    [
                            'model' => $model,
                            'menu' => $_data
                    ]);
        }
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view'
     * page.
     *
     * @param string $id            
     *
     * @return mixed
     */
    public function actionUpdate ($id) {
        // 权限编辑后颗粒配置入库
        // 权限编辑后默认菜单指定第二级子模块subID
        $request = Yii::$app->request;
        try {
            $userIP = Yii::$app->request->userIP;
            $menu_ID = $_POST['AuthItem']['subID'];
            $permission_Name = $id;
            if ($id) {
                $_permission_data = Permission::find()->where(
                        [
                                'name' => $id
                        ])
                    ->asArray()
                    ->one();
            } else {
                throw new NotFoundHttpException(
                        '权限颗粒入库数据错 auth_permission 没有找到数据!' . __LINE__ .
                                 __CLASS__);
                exit();
            }
            if ($request->isPost) {
                if ($_POST['permission_id']) {
                    // 权限颗粒操作
                    $request = Yii::$app->request;
                    $permission_ID = $_POST['permission_id'];
                    $assignment_module = new Assign();
                    $userIP = Yii::$app->request->userIP;
                    // 更新权限颗粒表
                    $_flag = $assignment_module->updateAll(
                            array(
                                    'menu_id' => $_POST['AuthItem']['subID'],
                                    'ip' => $userIP
                            ), 'permission_id=:permission_id', 
                            array(
                                    ':permission_id' => $_permission_data['id']
                            ));
                    if (! $_flag) {
                        throw new NotFoundHttpException(
                                '权限颗粒入库数据错' . __LINE__ . __CLASS__);
                        exit();
                    }
                } else {
                    throw new NotFoundHttpException(
                            '权限颗粒入库数据错' . __LINE__ . __CLASS__);
                    exit();
                }
            }
        } catch (\Exception $e) {}
        // menu list
        try {
            $data = Menupermission::find()->where(
                    [
                            'parent' => null
                    ])
                ->asArray()
                ->orderBy('order')
                ->all();
            if ($data) {
                foreach ($data as $value) {
                    $_data[$value['id']] = $value['name'];
                }
            } else {
                throw new NotFoundHttpException('数据错' . __LINE__);
            }
        } catch (\Exception $e) {}
        //
        $model_per = new Permission();
        $options_data = Permission::find()->where(
                [
                        'name' => $id
                ])
            ->asArray()
            ->one();
        if ($options_data) {
            $optionid = $options_data['menu_id'];
        } else {
            $optionid = '';
        }
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->getRequest()
            ->post()) && $model->save()) {
            MenuHelper::invalidate();
            
            return $this->redirect(
                    [
                            'view',
                            'id' => $model->name
                    ]);
        }
        return $this->render('update', 
                [
                        'model' => $model,
                        'optionid' => $optionid,
                        'menu' => $_data,
                        'permission_id' => $_permission_data['id']
                ]);
    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index'
     * page.
     *
     * @param string $id            
     *
     * @return mixed
     */
    public function actionDelete ($id) {
        try {
            // 规则权限表删除auth_rule
            $_permission_model = Permission::findOne(
                    [
                            'name' => $id
                    ]);
            $_permission_assign = Assign::findOne(
                    [
                            'permission_id' => $_permission_model->id
                    ]);
            if ($_permission_model->delete() && $_permission_assign->delete()) {
                $model = $this->findModel($id);
                Yii::$app->getAuthManager()->remove($model->item);
                MenuHelper::invalidate();
                return $this->redirect(
                        [
                                'index'
                        ]);
            }
            throw new NotFoundHttpException('The requested page does not exist.');
        } catch (\Exception $e) {
            echo $e->getMessage() . __LINE__;
            exit();
        }
    }

    /**
     * Assign or remove items.
     *
     * @param string $id            
     * @param string $action            
     *
     * @return array
     */
    public function actionAssign () {
        $post = Yii::$app->getRequest()->post();
        $id = $post['id'];
        $action = $post['action'];
        $roles = $post['roles'];
        $manager = Yii::$app->getAuthManager();
        $parent = $manager->getPermission($id);
        $error = [];
        if ($action == 'assign') {
            foreach ($roles as $role) {
                $child = $manager->getPermission($role);
                try {
                    $manager->addChild($parent, $child);
                } catch (\Exception $exc) {
                    $error[] = $exc->getMessage();
                }
            }
        } else {
            foreach ($roles as $role) {
                $child = $manager->getPermission($role);
                try {
                    $manager->removeChild($parent, $child);
                } catch (\Exception $exc) {
                    $error[] = $exc->getMessage();
                }
            }
        }
        MenuHelper::invalidate();
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        
        return [
                'type' => 'S',
                'errors' => $error
        ];
    }

    /**
     * Search role.
     *
     * @param string $id            
     * @param string $target            
     * @param string $term            
     *
     * @return array
     */
    public function actionSearch ($id, $target, $term = '') {
        $result = [
                'Permission' => [],
                'Routes' => []
        ];
        $authManager = Yii::$app->getAuthManager();
        if ($target == 'avaliable') {
            $children = array_keys($authManager->getChildren($id));
            $children[] = $id;
            foreach ($authManager->getPermissions() as $name => $role) {
                if (in_array($name, $children)) {
                    continue;
                }
                if (empty($term) or strpos($name, $term) !== false) {
                    $result[$name[0] === '/' ? 'Routes' : 'Permissions'][$name] = $name;
                }
            }
        } else {
            foreach ($authManager->getChildren($id) as $name => $child) {
                if (empty($term) or strpos($name, $term) !== false) {
                    $result[$name[0] === '/' ? 'Routes' : 'Permissions'][$name] = $name;
                }
            }
        }
        
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        
        return array_filter($result);
    }

    public function actionGetpersub () {
        $id = $_POST['id'];
        $type = $_POST['type'];
        if ($id && $type) {
            if ($type == 'sub') {
                // menu list
                try {
                    $data = Menupermission::find()->where(
                            [
                                    'parent' => $id
                            ])
                        ->asArray()
                        ->orderBy('order')
                        ->all();
                    if ($data) {
                        $_tmp = '';
                        foreach ($data as $value) {
                            $_tmp .= '<option value="' . $value['id'] . '">' .
                                     $value['name'] . '</option>';
                        }
                    } else {
                        throw new NotFoundHttpException('数据错' . __LINE__);
                    }
                } catch (\Exception $e) {}
                $html = '<div class="form-group field-tipodecliente_lst">
                        <label class="control-label" for="tipodecliente_lst">子模块</label>
                        <select id="tipodecliente_lst" class="form-control" name="AuthItem[subID]" onchange="getsubper(this,\'options\');">
                        <option value="">--请选择--</option>
                        ' . $_tmp . '
                        </select>
                        <div class="help-block"></div>
                        </div></select>';
                
                $attr = [
                        'status' => 'ok',
                        'type' => 'sub',
                        'data' => $html
                ];
                return \yii\helpers\Json::encode($attr);
            } elseif ($type == 'options') {
                // menu list
                try {
                    $data = Menupermission::find()->where(
                            [
                                    'parent' => $id
                            ])
                        ->asArray()
                        ->orderBy('order')
                        ->all();
                    if ($data) {
                        $check = '';
                        foreach ($data as $value) {
                            $check .= '<label><input name="AuthItem[options]" value="' .
                                     $value['id'] .
                                     '" type="checkbox" disabled checked="true">' .
                                     $value['name'] .
                                     '&nbsp;&nbsp;&nbsp;&nbsp;</label>';
                            // 审批清单 146
                            if ($value['id'] == 146) {
                                $approval = ' <hr>
                                      <label class="control-label" for="authitem-name">操作权限</label>
                                      &nbsp;&nbsp;&nbsp;&nbsp;
                                      <input name="AuthItem[public]" value="147" disabled checked="true" type="checkbox">发布&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                      <input name="AuthItem[hid]" value="148" disabled checked="true" type="checkbox">隐藏&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                      <input name="AuthItem[del]" value="149" disabled checked="true" type="checkbox">删除&nbsp;&nbsp;&nbsp;&nbsp;';
                            } elseif ($value['id'] == 126 || $value['id'] == 127 ||
                                     $value['id'] == 128 || $value['id'] == 129) { // 评论管理详细操作
                                switch ($value['id']) {
                                    case 126:
                                        $pro = '表单设定';
                                        $val_1 = '  value="130"';
                                        $val_2 = '  value="131"';
                                        $val_3 = '  value="132"';
                                        $val_4 = '  value="133"';
                                        break;
                                    case 127:
                                        $pro = '表单类型';
                                        $val_1 = '  value="134"';
                                        $val_2 = '  value="135"';
                                        $val_3 = '  value="136"';
                                        $val_4 = '  value="137"';
                                        break;
                                    case 128:
                                        $pro = '使用区域';
                                        $val_1 = '  value="138"';
                                        $val_2 = '  value="139"';
                                        $val_3 = '  value="140"';
                                        $val_4 = '  value="141"';
                                        break;
                                    case 129:
                                        $pro = '生成管理';
                                        $val_1 = '  value="142"';
                                        $val_2 = '  value="143"';
                                        $val_3 = '  value="144"';
                                        $val_4 = '  value="145"';
                                        break;
                                    default:
                                        break;
                                }
                                
                                $approval .= ' <hr>
                                      <label class="control-label" for="authitem-name">' .
                                         $pro . '</label>
                                      &nbsp;&nbsp;&nbsp;&nbsp;
                                      <input name="AuthItem[add]"  ' . $val_1 . '   disabled checked="true" type="checkbox">添加&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                      <input name="AuthItem[edit]"   ' .
                                         $val_2 . '  checked="true" disabled type="checkbox">编辑&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                      <input name="AuthItem[del]"   ' . $val_3 . '  disabled checked="true" type="checkbox">删除&nbsp;&nbsp;&nbsp;&nbsp;
                                       &nbsp;&nbsp;&nbsp;&nbsp;
                                      <input name="AuthItem[search]"   ' .
                                         $val_4 .
                                         '   disabled checked="true" type="checkbox">检索列表&nbsp;&nbsp;&nbsp;&nbsp;';
                            }
                        }
                    } else {
                        throw new NotFoundHttpException('数据错' . __LINE__);
                    }
                } catch (\Exception $e) {}
                if ($data) {
                    $html = '<div class="row"><div class="col-md-12">
                              <div class="box box-solid">
                                <div class="box-header with-border">
                                  <h5 class="text-muted">权限颗粒度&nbsp;&nbsp;<span class="text-red">(指派用户角色时设置)</span></h5>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                  <div class="checkbox text-muted">
                                   ' . $check . '
                                  </div>
                                   ' . $approval . '
                                </div>
                                <!-- /.box-body --> 
                              </div>
                              <!-- /.box --> 
                            </div></div>';
                } else {
                    $attr = [
                            'status' => 'error',
                            'type' => 'options',
                            'data' => '系统权限不能设置!'
                    ];
                    return \yii\helpers\Json::encode($attr);
                }
                $attr = [
                        'status' => 'ok',
                        'type' => 'options',
                        'data' => $html
                ];
                return \yii\helpers\Json::encode($attr);
            }
            //
        } else {
            $attr = [
                    'status' => 'error',
                    'data' => '参数出错!'
            ];
            return \yii\helpers\Json::encode($attr);
        }
    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id            
     *
     * @return AuthItem the loaded model
     *        
     * @throws HttpException if the model cannot be found
     */
    protected function findModel ($id) {
        $item = Yii::$app->getAuthManager()->getPermission($id);
        if ($item) {
            return new AuthItem($item);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

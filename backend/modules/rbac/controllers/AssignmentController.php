<?php
namespace mdm\admin\controllers;
use Yii;
use mdm\admin\models\Assignment;
use mdm\admin\models\searchs\Assignment as AssignmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use mdm\admin\components\MenuHelper;
use yii\web\Response;
use mdm\admin\models\Rule;
use mdm\admin\models\Userassign;

/**
 * AssignmentController implements the CRUD actions for Assignment model.
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 *        
 * @since 1.0
 *        即：
 *       
 *        角色 包含 角色
 *       
 *        角色 包含 权限
 *       
 *        权限 包含 权限
 *       
 *        但 ：
 *       
 *        权限 不可包含 角色
 */
class AssignmentController extends Controller {

    public $userClassName;

    public $idField = 'id';

    public $usernameField = 'username';

    public $searchClass;

    /**
     *
     * {@inheritdoc}
     *
     */
    public function init () {
        parent::init();
        if ($this->userClassName === null) {
            $this->userClassName = Yii::$app->getUser()->identityClass;
            $this->userClassName = $this->userClassName ?: 'common\models\User';
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function behaviors () {
        return [
                'verbs' => [
                        'class' => VerbFilter::className(),
                        'actions' => [
                                'assign' => [
                                        'post'
                                ]
                        ]
                ]
        ];
    }

    /**
     * Lists all Assignment models.
     *
     * @return mixed
     */
    public function actionIndex () {
        if ($this->searchClass === null) {
            $searchModel = new AssignmentSearch();
        } else {
            $class = $this->searchClass;
            $searchModel = new $class();
        }
        
        $dataProvider = $searchModel->search(
                \Yii::$app->request->getQueryParams(), $this->userClassName, 
                $this->usernameField);
        
        return $this->render('index', 
                [
                        'dataProvider' => $dataProvider,
                        'searchModel' => $searchModel,
                        'idField' => $this->idField,
                        'usernameField' => $this->usernameField
                ]);
    }

    /**
     * Displays a single Assignment model.
     *
     * @param int $id            
     *
     * @return mixed
     */
    public function actionView ($id) {
        $model = $this->findModel($id);
        
        return $this->render('view', 
                [
                        'model' => $model,
                        'idField' => $this->idField,
                        'usernameField' => $this->usernameField
                ]);
    }

    /**
     * Assign or revoke assignment to user.
     *
     * @param int $id            
     * @param string $action            
     *
     * @return mixed
     */
    public function actionAssign () {
        $post = Yii::$app->request->post();
        $id = $post['id'];
        $action = $post['action'];
        $roles = $post['roles'];
        $manager = Yii::$app->authManager;
        $error = [];
        if( empty($roles)){
        	return '';
        }else{
        if ($action == 'assign') {
            // 操作人UID
            $operator_userid = Yii::$app->user->identity->id;
            // 被操作人UID
            $beoperator_userid = $id;
            // 操作人IP
            $userIP = Yii::$app->request->userIP;
	            foreach ($roles as $name) {
	                try {
	                    // 用户->角色->账户->权限 关系
	                    $item = $manager->getRole($name);
	                    if ($item) {
	                        $role_data = Rule::find()->select('id,account_id')
	                            ->where(
	                                [
	                                        'name' => $name
	                                ])
	                            ->asArray()
	                            ->one();
	                        $assginRole = new Userassign();
	                        $assginRole->role_id = $role_data['id'];
	                        $assginRole->operator_userid = $operator_userid;
	                        $assginRole->beoperator_userid = $beoperator_userid;
	                        $assginRole->ip = $userIP;
	                        $assginRole->account_id = $role_data['account_id'];
	                        $assginRole->role_name = $name;
	                        try {
	                            if (! $assginRole->save()) {throw new NotFoundHttpException(
	                                        '数据错' . __LINE__);}
	                        } catch (\Exception $e) {}
	                    }
	                    // system
	                    $item = $item ?: $manager->getPermission($name);
	                    $manager->assign($item, $id);
	                } catch (\Exception $exc) {
	                    $error[] = $exc->getMessage();
	                }
	            }
        } else {
            foreach ($roles as $name) {
                try {
                    $item = $manager->getRole($name);
                    if ($item) {
                        // 取消角色权限表
                        $role_data = Rule::find()->select('id,account_id')
                            ->where(
                                [
                                        'name' => $name
                                ])
                            ->asArray()
                            ->one();
                        $revokeRole = new Userassign();
                        $_flag = $revokeRole->deleteAll(
                                'role_id = :role_id AND beoperator_userid = :beoperator_userid', 
                                [
                                        ':role_id' => $role_data['id'],
                                        ':beoperator_userid' => $id
                                ]);
                        
                        try {
                            if (! $_flag) {throw new NotFoundHttpException(
                                        '数据错' . __LINE__);}
                        } catch (\Exception $e) {}
                    }
                    
                    $item = $item ?: $manager->getPermission($name);
                    $manager->revoke($item, $id);
                } catch (\Exception $exc) {
                    $error[] = $exc->getMessage();
                }
            }
        }
        MenuHelper::invalidate();
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        return [
                'type' => 'S',
                'errors' => $error
        ];
        }
    }

    /**
     * Search roles of user.
     *
     * @param int $id            
     * @param string $target            
     * @param string $term            
     *
     * @return string
     */
    public function actionSearch ($id, $target, $term = '') {
        Yii::$app->response->format = 'json';
        $authManager = Yii::$app->authManager;
        $roles = $authManager->getRoles();
        $permissions = $authManager->getPermissions();
        
        $avaliable = [];
        $assigned = [];
        foreach ($authManager->getAssignments($id) as $assigment) {
            if (isset($roles[$assigment->roleName])) {
                if (empty($term) || strpos($assigment->roleName, $term) !== false) {
                    $assigned['Roles'][$assigment->roleName] = $assigment->roleName;
                }
                unset($roles[$assigment->roleName]);
            } elseif (isset($permissions[$assigment->roleName]) &&
                     $assigment->roleName[0] != '/') {
                if (empty($term) || strpos($assigment->roleName, $term) !== false) {
                    //$assigned['Permissions'][$assigment->roleName] = $assigment->roleName;
                }
                unset($permissions[$assigment->roleName]);
            }
        }
        
        if ($target == 'avaliable') {
            if (count($roles)) {
                foreach ($roles as $role) {
                    if (empty($term) || strpos($role->name, $term) !== false) {
                        $avaliable['Roles'][$role->name] = $role->name;
                    }
                }
            }
            if (count($permissions)) {
                foreach ($permissions as $role) {
                    if ($role->name[0] != '/' && (empty($term) ||
                             strpos($role->name, $term) !== false)) {
                        //$avaliable['Permissions'][$role->name] = $role->name;
                    }
                }
            }
            
            return $avaliable;
        } else {
            return $assigned;
        }
    }

    /**
     * Finds the Assignment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id            
     *
     * @return Assignment the loaded model
     *        
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel ($id) {
        $class = $this->userClassName;
        if (($model = $class::findIdentity($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

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
use mdm\admin\models\Account;
use mdm\admin\models\Rule;
use mdm\admin\models\Role;
use mdm\admin\models\Authitems;
use mdm\admin\models\Userassign;

/**
 * AuthItemController implements the CRUD actions for AuthItem model.
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 *        
 * @since 1.0
 */
class RoleController extends Controller {

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
                        'type' => Item::TYPE_ROLE
                ]);
        $dataProvider = $searchModel->search(
                Yii::$app->request->getQueryParams());
        
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
        $model = new AuthItem(null);
        $model->type = Item::TYPE_ROLE;
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
                            'model' => $model
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
        $model = new Rule();
        $options_data = Rule::find()->where(
                [
                        'name' => $id
                ])
            ->asArray()
            ->one();
        if ($options_data) {
            $optionid = $options_data['account_id'];
            $role_id = $options_data['id'];
        } else {
            $optionid = $role_id='';
        }
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
        	$transaction=\Yii::$app->db->beginTransaction();
        	try {
        		//  $model->save() 即：dw_auth_item dw_auth_rule编辑保存成功
        		MenuHelper::invalidate();
        		if(Yii::$app->getRequest()->post()){
        			$name = isset($_POST['AuthItem']['name'])?$_POST['AuthItem']['name']:exit;('error');
        			$new_pid = isset($_POST['AuthItem']['pid'])?$_POST['AuthItem']['pid']:exit;('error');
        			$falg = $falg2 = true;
        			 // 更新auth_item_child 角色-路由表
        			 $role_model = new Role();
        			 if($id != $name ) { // 当角色名称发生改变的时候在做更新
	        			 $options_data = $role_model::find()->where(['parent' => $id])->asArray()->one();
	        			 if($options_data){
	        				$auth_item_child_data['parent'] = $name;
	        				$falg = $role_model::updateAll($auth_item_child_data,'parent='."'".$id."'");
	        			 }
        			 }
        			
        			 // 更新 关系表dw_auth_account_user_role_permission（只有用户被授权后，才会有此数据）
        			 $userassign_model = new Userassign();
        			 $userassign_checkdata = $userassign_model::find()->where(['role_name' => $id,'role_id'=>$role_id])->asArray()->one();
        			 if($userassign_checkdata){
        			 	$userassign_data['account_id'] = $new_pid;
        			 	$userassign_data['role_name'] = $name;
        			 	
        			 	$falg2 = $userassign_model::updateAll($userassign_data,['role_name'=>$id,'role_id'=>$role_id]);
        			 }
        			 $transaction->commit();
        		}
        		return $this->redirect([
        				'index'
        				]);
        	}catch (\Exception $e) {
	    		$transaction->rollback();
	    		echo '更新角色失败，请联系管理员';exit;
	    	}
            
        }
        
        return $this->render('update', 
                [
                        'model' => $model,
                        'optionid' => $optionid
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
    	
    	// 开始事务
    	try {
            $transaction=\Yii::$app->db->beginTransaction();
            // 规则角色表删除auth_rule
            $_auth_rule = Rule::findOne(
                    [
                            'name' => $id
                    ]);
            $_auth_role = Role::findOne(
                    [
                            'parent' => $id
                    ]);
            $_auth_userassign = Userassign::findOne(
                    [
                            'role_name' => $id
                    ]);
            //角色表
            if($_auth_rule){
                $rule = $_auth_rule->delete();
            }
            //角色授权
            if($_auth_userassign){
                $_auth_userassign->delete();
            }
            $model = $this->findModel($id);
            Yii::$app->getAuthManager()->remove($model->item);
            MenuHelper::invalidate();
            //提交事务
            $transaction->commit();
            if ($rule) {
               
                return $this->redirect(
                        [
                                'index'
                        ]);
                }else {
                    throw new NotFoundHttpException('角色授权删除失败!' . __LINE__);
                    exit();
                }
        } catch (\Exception $e) {
        	$transaction->rollback();
        	throw new NotFoundHttpException('操作失败!' . __LINE__.'<br>'.$e->getMessage());
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
        $parent = $manager->getRole($id);
        $error = [];
        if( empty($roles)){
        	return '';
        }else{
        if ($action == 'assign') {
            foreach ($roles as $role) {
                $child = $manager->getRole($role);
                $child = $child ?: $manager->getPermission($role);
                try {
                    $manager->addChild($parent, $child);
                } catch (\Exception $e) {
                    $error[] = $e->getMessage();
                }
            }
        } else {
            foreach ($roles as $role) {
                $child = $manager->getRole($role);
                $child = $child ?: $manager->getPermission($role);
                try {
                    $manager->removeChild($parent, $child);
                } catch (\Exception $e) {
                    $error[] = $e->getMessage();
                }
            }
        }
        MenuHelper::invalidate();
        Yii::$app->response->format = 'json';
        
        return [
                'type' => 'S',
                'errors' => $error
        ];
        }
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
                'Roles' => [],
                'Permissions' => [],
                'Routes' => []
        ];
        $authManager = Yii::$app->authManager;
        if ($target == 'avaliable') {
            $children = array_keys($authManager->getChildren($id));
            $children[] = $id;
            foreach ($authManager->getRoles() as $name => $role) {
                if (in_array($name, $children)) {
                    continue;
                }
                if (empty($term) or strpos($name, $term) !== false) {
                    $result['Roles'][$name] = $name;
                }
            }
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
                    if ($child->type == Item::TYPE_ROLE) {
                        $result['Roles'][$name] = $name;
                    } else {
                        $result[$name[0] === '/' ? 'Routes' : 'Permissions'][$name] = $name;
                    }
                }
            }
        }
        Yii::$app->response->format = 'json';
        
        return array_filter($result);
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
        $item = Yii::$app->getAuthManager()->getRole($id);
        if ($item) {
            return new AuthItem($item);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}


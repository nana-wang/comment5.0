<?php
namespace backend\controllers;
use Yii;
use backend\models\User;
use backend\models\search\DwUserSearch;
use backend\controllers\BaseController;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use backend\models\DwUser;
use Qiniu\json_decode;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller {

    /**
     * Lists all User models.
     *
     * @return mixed
     */
    public function actionIndex () {
        
        // $dataProvider = new ActiveDataProvider([
        // 'query' => User::find(),
        // 'sort' => [
        // 'defaultOrder' => [
        // 'id' => SORT_DESC
        // ]
        // ]
        // ]);
        $searchModel = new DwUserSearch();
        $dataProvider = $searchModel->search(
                Yii::$app->request->getQueryParams());
        
        return $this->render('index', 
                [
                        'dataProvider' => $dataProvider,
                        'searchModel' => $searchModel
                ]);
    }

    /**
     * Displays a single User model.
     *
     * @param integer $id            
     * @return mixed
     */
    public function actionView ($id) {
        return $this->render('view', 
                [
                        'model' => $this->findModel($id)
                ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view'
     * page.
     *
     * @return mixed
     */
    public function actionCreate () {
        $model = new User();
        $model->scenario = 'create';
        if ($model->load(Yii::$app->request->post()) && $model->signUp()) {
            DwUser::update_user_redis();
            return $this->redirect([
                    'index'
            ]);
        } else {
            return $this->render('create', 
                    [
                            'model' => $model
                    ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view'
     * page.
     *
     * @param integer $id            
     * @return mixed
     */
    public function actionUpdate ($id) {
        $model = $this->findModel($id);
        $model->scenario = 'update';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            DwUser::update_user_redis();
            return $this->redirect([
                    'index'
            ]);
        } else {
            return $this->render('update', 
                    [
                            'model' => $model
                    ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index'
     * page.
     *
     * @param integer $id            
     * @return mixed
     */
    public function actionDelete ($id) {
        $this->findModel($id)->delete();
        DwUser::update_user_redis();
        return $this->redirect([
                'index'
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id            
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel ($id) {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 重置密码
     */
    public function actionResetPassword ($id) {
        $model = $this->findModel($id);
        $model->scenario = 'resetPassword';
        if ($model->load(Yii::$app->request->post()) && $model->resetPassword()) {
            Yii::$app->user->logout();
            return $this->goHome();
        }
        return $this->render('reset-password', 
                [
                        'model' => $model
                ]);
    }

    /**
     * 封禁用户
     */
    public function actionBan () {
        $id = Yii::$app->request->post('id');
        $banUser = User::findOne($id);
        if ($banUser->isAdmin) {throw new ForbiddenHttpException('不支持封禁管理员帐号');}
        $model = $this->findModel($id);
        $model->status = \common\models\User::STATUS_DELETED;
        $model->save(false);
        return $this->goBack();
    }
}

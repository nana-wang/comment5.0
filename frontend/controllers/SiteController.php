<?php
namespace frontend\controllers;
use Yii;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\Register;
use frontend\models\Users;
use yii\base\InvalidParamException;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Site controller.
 */
class SiteController extends Controller {

    /**
     *
     * {@inheritdoc}
     *
     */
    public function behaviors () {
        return [];
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function actions () {
        return [];
    }

    public function successCallback ($client) {
        $attributes = $client->getUserAttributes();
        // 获取用户的信息并将其存入数据库中
        $model = new Users();
        $model->username = $attributes['name'];
        $model->email = $attributes['email'];
        $model->created_at = time();
        $model->is_user_type = 'facebook';
        $flg = $model->insert();
        if ($flg) {
            echo '添加用户成功';
        } else {
            echo '添加用户失败';
        }
        die();
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex () {
        return $this->redirect('admin');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin () {
        $model = new Users();
        return $this->render('login', 
                [
                        'model' => $model
                ]);
    }
    // 登录验证
    public function actionLogin_form () {
        $result['status'] = 0;
        $email = Yii::$app->request->post("email");
        $password = Yii::$app->request->post("password");
        if ($email && $password) {
            // 检测用户是否存在
            $users = Users::find()->where(
                    [
                            'and',
                            "email='" . $email . "'",
                            "password='" . $password . "'"
                    ])
                ->asArray()
                ->one();
            if ($users) {
                // 登录成功
                $_SESSION['userinfo'] = $users;
                // $_COOKIE['userinfo'] = $users;
                $result['status'] = 1;
                $result['data'] = '登录成功！';
            } else {
                // 用户名或密码有误
                $result['status'] = 2;
                $result['data'] = '用户名或密码有误！';
            }
        } else {
            $result['status'] = - 1;
            $result['data'] = '用户名或密码不能为空！';
        }
        return json_encode($result);
    }
    
    // 用户注册
    public function actionRegist () {
        $model = new Register();
        return $this->render('register', 
                [
                        'model' => $model
                ]);
    }
    
    // 用户注册检测
    public function actionRegist_form () {
        $result['status'] = 0;
        $username = Yii::$app->request->post("username");
        $email = Yii::$app->request->post("email");
        $password = Yii::$app->request->post("password");
        if ($username && $email && $password) {
            // 判读数据库中是否有值
            $users = Users::find()->where(
                    [
                            'or',
                            "email='" . $email . "'",
                            "username='" . $username . "'"
                    ])
                ->asArray()
                ->one();
            if ($users) {
                $result['status'] = - 1;
                $result['data'] = '用户已经存在！';
            } else {
                // 存入数据库
                $model = new Users();
                $model->username = $username;
                $model->password = $password;
                $model->email = $email;
                $model->third_id = '0';
                $model->created_at = time();
                $flg = $model->insert();
                if ($flg) {
                    $result['status'] = 1;
                    $result['data'] = '注册成功！';
                } else {
                    $result['status'] = 2;
                    $result['data'] = '注册失败！';
                }
            }
        }
        return json_encode($result);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout () {
        Yii::$app->user->logout();
        
        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup () {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                
                return ActiveForm::validate($model);
            }
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {return $this->goHome();}
            }
        }
        
        return $this->render('signup', 
                [
                        'model' => $model
                ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset () {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', '请登录邮箱重置密码');
                
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', '很抱歉,发生错误了!');
            }
        }
        
        return $this->render('requestPasswordResetToken', 
                [
                        'model' => $model
                ]);
    }

    /**
     * Resets password.
     *
     * @param string $token            
     *
     * @return mixed
     *
     * @throws BadRequestHttpException
     */
    public function actionResetPassword ($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->validate() &&
                 $model->resetPassword()) {
            Yii::$app->session->setFlash('success', '新密码设置成功！');
            
            return $this->goHome();
        }
        
        return $this->render('resetPassword', 
                [
                        'model' => $model
                ]);
    }

    /**
     * 网站地图，百度搜索引擎爬虫用.
     *
     * @return array
     */
    public function actionSitemap () {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_XML;
        \Yii::$container->set('yii\web\XmlResponseFormatter', 
                [
                        'rootTag' => 'urlset',
                        'itemTag' => 'url'
                ]);
        $urls = [];
        $models = Article::find()->published()
            ->select('id')
            ->orderBy([
                'id' => SORT_DESC
        ])
            ->each(20);
        foreach ($models as $model) {
            $url = [];
            $url['loc'] = Url::to(
                    [
                            '/article/view',
                            'id' => $model->id
                    ], true);
            $urls[] = $url;
        }
        
        return $urls;
    }
}

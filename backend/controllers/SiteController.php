<?php
namespace backend\controllers;
use Yii;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use mdm\admin\models\Account;
use mdm\admin\models\Menutree;

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
        return [
                'verbs' => [
                        'class' => VerbFilter::className(),
                        'actions' => [
                                'logout' => [
                                        'post'
                                ]
                        ]
                ]
        ];
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function actions () {
        return [
                'error' => [
                        'class' => 'yii\web\ErrorAction'
                ],
                'demo' => [
                        'class' => 'yii\web\ViewAction'
                ],
                'webupload' => [
                        'class' => \yidashi\webuploader\Action::className()
                ]
        ];
    }

    public function actionIndex () {
        $module = new Account();
        $parents_data = Account::find()->asArray()->all();
        $data = $this->getSubData($parents_data);
        return $this->render('index', 
                [
                        'html' => $data
                ]);
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
        $_data = new \backend\widgets\MenuLeft();
        $data = $_data->find();
        if ($data) {
            foreach ($data as $key => $value) {
                if ($this->checkAccountLevel($key)) {
                    $t = '<td style="padding-left: 0px;" id="dropdownMenu1"><i class="caret"></i>&nbsp;&nbsp;<a href="###" class="text-muted">' .
                             $value . '</a></td>';
                } else {
                    $t = '<td width="100%" style="padding-left: 20px;">&nbsp;&nbsp;<a href="###" class="text-muted">' .
                             $value . '</a></td>';
                }
                $html .= '<tr><ul class="dropdown-menu dropdown-menu-right" role="menu"
											aria-labelledby="dropdownMenu1">
											<li><a href="index.php?r=rbac%2Faccount%2Fupdate&id=' .
                         $key . '"><i
													class="glyphicon glyphicon-pencil font-12"></i> 账户编辑 </a></li>
											
										</ul>' . $t . '</tr>';
            }
            return $html;
        }
    }

    /**
     * 账户级别检测
     *
     * @param unknown $id            
     * @return boolean
     */
    private function checkAccountLevel ($id) {
        $data = Account::find()->where(
                [
                        'id' => $id,
                        'pid' => 0
                ])
            ->asArray()
            ->one();
        if ($data) {
            return true;
        } else {
            return false;
        }
    }

    public function actionLogin () {
        $this->layout = 'main-login';
        if (! \Yii::$app->user->isGuest) {return $this->goHome();}
        
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', 
                    [
                            'model' => $model
                    ]);
        }
    }

    public function actionLogout () {
        Yii::$app->user->logout();
        
        return $this->goHome();
    }
}


<?php
namespace backend\controllers;
use Yii;
use common\models\Comment;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use backend\models\DwParameter;
use GuzzleHttp\json_encode;
use Qiniu\json_decode;
use yii\web\NotFoundHttpException;
use backend\models\search\DwCommentSearch;
use backend\models\DwAuthAccount;

/**
 * CommentController implements the CRUD actions for Comment model.
 */
class ParameterController extends Controller {

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

    public function actions () {
        return [
                'delete' => 'yii2tech\\admin\\actions\\Delete'
        ];
    }

    /**
     * 账户参数首页
     */
    public function actionIndex () {
        $model = new DwParameter();
        
        // $uid = Yii::$app->user->id;
        // 获取当前登录用户有权限的pid
        // $all_account_arr = DwCommentSearch::getAccountPidByUid($uid);
        $blacklist_level = isset($_POST['blacklist_level']) ? $this->request->getPost(
                'blacklist_level') : 1;
        $all_account_arr = DwAuthAccount::getCurrentAccount(2);
        // print_r($all_account_arr);exit;
        if (! empty($all_account_arr)) {
            $query = DwParameter::find()->Where(
                    'parameter_account_pid in (' . $all_account_arr . ')');
            $dataProvider = new ActiveDataProvider(
                    [
                            'query' => $query,
                            'sort' => [
                                    'defaultOrder' => [
                                            'id' => SORT_DESC
                                    ]
                            ]
                    ]);
            return $this->render('index', 
                    [
                            'model' => $model,
                            'dataProvider' => $dataProvider
                    ]);
        } else {
            return $this->render('index', 
                    [
                            'model' => $model,
                            'dataProvider' => ''
                    ]);
        }
    }

    /**
     * 账户参数添加
     *
     * @return mixed
     */
    public function actionParameter_add () {
        // 手动添加
        $parameter_report_num = Yii::$app->request->post("parameter_report_num");
        $parameter_report_brush = Yii::$app->request->post(
                "parameter_report_brush");
        $parameter_account_id = Yii::$app->request->post("parameter_account_id");
        $parameter_operation_id = Yii::$app->request->post(
                "parameter_operation_id");
        
        if (! empty($parameter_report_num) || ! empty($parameter_report_brush) ||
                 ! empty($parameter_account_id)) {
            
            $str1 = '';
            $account_pid = '';
            
            // $data =
            // DwParameter::find()->where(['parameter_account_id'=>$parameter_account_id])->one();
            // 获取父id
            $account_pid = DwAuthAccount::getAccountPidByAccountid(
                    $parameter_account_id);
            
            if ($account_pid == 0) $account_pid = $parameter_account_id;
            $data = DwParameter::find()->where(
                    [
                            'parameter_account_pid' => $account_pid
                    ])->one();
            if (isset($data)) {
                $return = [
                        'flg' => false,
                        'data' => Yii::t('backend', 'Set Exists')
                ]; // 该主账户下配置已经存在
            } else {
                $model = new DwParameter();
                $model->parameter_report_num = $parameter_report_num;
                $model->parameter_report_brush = $parameter_report_brush;
                $model->parameter_account_id = $parameter_account_id;
                $model->parameter_account_pid = $account_pid;
                $model->parameter_operation_id = Yii::$app->user->identity->id;
                $model->parameter_time = time();
                $flg = $model->insert();
                if ($flg) {
                    $return = [
                            'flg' => true,
                            'data' => Yii::t('backend', 'Account Success')
                    ]; // 账户参数插入成功
                } else {
                    $return = [
                            'flg' => false,
                            'data' => Yii::t('backend', 'Account Fail')
                    ]; // 账户参数插入失败
                }
            }
            
            $this->update_parameter_redis($account_pid);
        } else {
            $return = [
                    'flg' => false,
                    'data' => Yii::t('backend', 'Parameter Error')
            ]; // 参数错误
        }
        return json_encode($return);
    }

    /**
     * 账户参数编辑保存
     *
     * @param integer $id            
     * @return mixed
     */
    public function actionParameter_edit () {
        $id = Yii::$app->request->post("id");
        $parameter_report_brush = Yii::$app->request->post(
                "parameter_report_brush");
        $parameter_report_num = Yii::$app->request->post("parameter_report_num");
        $parameter_account_id = Yii::$app->request->post("parameter_account_id");
        
        if (! empty($id) && ! empty($parameter_report_brush) &&
                 ! empty($parameter_report_num)) {
            $datelist = DwParameter::findOne($id);
            if (empty($datelist)) {
                $return = [
                        'flg' => false,
                        'data' => Yii::t('backend', 'Parameter Error')
                ]; // 参数错误
                return json_encode($return);
            }
            $data = DwParameter::find()->where(
                    [
                            'parameter_account_id' => $parameter_account_id
                    ])
                ->andWhere([
                    '<>',
                    'id',
                    $id
            ])
                ->one();
            if (! empty($data)) {
                $return = [
                        'flg' => false,
                        'data' => Yii::t('backend', 'Account Group Exist')
                ]; // 该账户组账户已经存在
            } else {
                $save['parameter_report_brush'] = $parameter_report_brush;
                $save['parameter_report_num'] = $parameter_report_num;
                $save['parameter_operation_id'] = Yii::$app->user->identity->id;
                $save['parameter_time'] = time();
                $flg = DwParameter::updateAll($save, [
                        'id' => $id
                ]);
                if ($flg) {
                    $return = [
                            'flg' => true,
                            'data' => Yii::t('backend', 'Success Operation')
                    ]; // 操作成功
                    
                    $this->update_parameter_redis(
                            $datelist['parameter_account_pid']);
                } else {
                    $return = [
                            'flg' => false,
                            'data' => Yii::t('backend', 'Fail Peration')
                    ]; // 操作失败
                }
            }
        } else {
            $return = [
                    'flg' => false,
                    'data' => Yii::t('backend', 'Parameter Error')
            ]; // 参数错误
        }
        return json_encode($return);
    }

    /**
     * 账户参数 查看
     */
    public function actionView () {
        $id = Yii::$app->request->post("id");
        if (! empty($id)) {
            $data = DwParameter::find()->where([
                    'id' => $id
            ])
                ->asArray()
                ->one();
            if (! empty($data)) {
                $return = [
                        'flg' => true,
                        'data' => $data
                ];
            } else {
                $return = [
                        'flg' => false,
                        'data' => 'Without this data'
                ];
            }
        } else {
            $return = [
                    'flg' => false,
                    'data' => Yii::t('backend', 'Parameter Error')
            ]; // 参数错误
        }
        return json_encode($return);
    }

    /**
     * 账户参数删除
     *
     * @return mixed
     */
    public function actionParameter_del () {
        $id = Yii::$app->request->post("id");
        
        if (! empty($id)) {
            $datelist = DwParameter::findOne($id);
            if (empty($datelist)) {
                $return = [
                        'flg' => false,
                        'data' => Yii::t('backend', 'Parameter Error')
                ]; // 参数错误
                return json_encode($return);
            }
            
            $flg = $this->findModell($id)->delete();
            
            if ($flg) {
                $this->update_parameter_redis(
                        $datelist['parameter_account_pid']);
                $return = [
                        'flg' => true,
                        'data' => Yii::t('backend', 'Delete Success')
                ]; // 删除成功
            } else {
                $return = [
                        'flg' => false,
                        'data' => Yii::t('backend', 'Delete Error')
                ]; // 删除失败
            }
        } else {
            $return = [
                    'flg' => false,
                    'data' => Yii::t('backend', 'Parameter Error')
            ]; // 参数错误
        }
        
        return json_encode($return);
    }

    /**
     * Finds the Comment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id            
     * @return Comment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModell ($id) {
        if (($model = DwParameter::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 参数 缓存更新pid父id
     */
    protected function update_parameter_redis ($pid) {
        $redis = Dwparameter::find()->andFilterWhere(
                [
                        'parameter_account_pid' => $pid
                ])
            ->asArray()
            ->one();
        $redis_name = $pid . 'parameter';
        if (! empty($redis)) {
            // foreach ( $redis as $s_key =>$s_v){
            // $a2[$s_v['parameter_account_id']] = $s_v;
            // }
            $value = @json_encode($redis);
            Yii::$app->redis->set(md5($redis_name), $value);
        } else {
            $value = '';
            Yii::$app->redis->set(md5($redis_name), '');
        }
        
        return true;
    }
}

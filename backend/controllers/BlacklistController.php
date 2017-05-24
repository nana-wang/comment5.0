<?php
namespace backend\controllers;

use Yii;
use common\models\Comment;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\Blacklist;
use backend\models\DwCommentLog;
use backend\models\DwComment;
use GuzzleHttp\json_encode;
use backend\models\search\DwBlacklistSearch;

/**
 * SensitiveController implements the CRUD actions for Comment model.
 * tonghui
 */
class BlacklistController extends Controller
{

    public function behaviors()
    {
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

    public function actions()
    {
        return [
            'delete' => 'yii2tech\\admin\\actions\\Delete'
        ];
    }

    /**
     * 黑名单列表
     */
    public function actionIndex()
    {
        $searcharray = Yii::$app->request->get("DwBlacklistSearch");
        $issearch = 0; // 是否定有检索条件

        if (!empty($searcharray)) {
            $issearch = 1;
        }
        $uid = Yii::$app->user->id;
        $searchModel = new DwBlacklistSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        // 所有的用户
        // $users = User::find()->asArray()->all();
        return $this->render('index',
            [
                'dataProvider' => $dataProvider,
                'issearch' => $issearch,
                'searchModel' => $searchModel
            ]);
    }

    /**
     * 添加黑名单
     */
    public function actionBlack_add()
    {
        $blacklist_uid = Yii::$app->request->post('blacklist_uid');
        $username = Yii::$app->request->post('username');
        if (!empty($blacklist_uid)) {
            $data = Blacklist::find()->where(
                [
                    'blacklist_uid' => $blacklist_uid
                ])->one();
            if (!empty($data)) {
                $msg = Yii::t('backend', 'Already Exist');
                $return = [
                    'flg' => false,
                    'data' => '【' . $username . '】"' . $msg . '"'
                ];
            } else {
                $model = new Blacklist();
                $model->blacklist_uid = $blacklist_uid;
                // $model->blacklist_action_uid = $blacklist_action_uid; //操作人
                $model->blacklist_create = time();
                $flg = $model->insert();
                if ($flg) {
                    $return = [
                        'flg' => true,
                        'data' => Yii::t('backend', 'Add Sucess')
                    ];
                    return $this->redirect(
                        [
                            'index'
                        ]);
                } else {
                    $return = [
                        'flg' => false,
                        'data' => Yii::t('backend', 'Add Fail')
                    ];
                }
            }
        } else {
            $return = [
                'flg' => false,
                'data' => Yii::t('backend', 'Parameter Error')
            ];
        }
        return json_encode($return);
    }

    public function findBmodel($id)
    {
        if (($model = Blacklist::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 黑名单删除
     */
    public function actionBlackdel()
    {
        $id = Yii::$app->request->post("id");
        if (!empty($id)) {
            // 查询用户
            $data = Blacklist::find()->where(
                [
                    'id' => $id
                ])
                ->asArray()
                ->one();
            $flg = $this->findBmodel($id)->delete();
            if ($flg) {
                // 跟新缓存
                $this->Update_black_redis();

                // 冻结用户删除后恢复到之前的评论状态
                if (!empty($data['blacklist_uid']) &&
                    $data['blacklist_level'] == 2
                ) {
                    // 获取所有评论信息
                    $comment_data = DwComment::find()->where(
                        [
                            'comment_user_id' => $data['blacklist_uid']
                        ])
                        ->asArray()
                        ->all();
                    // 评论信息循环更新为原来的状态值
                    if (!empty($comment_data)) {
                        foreach ($comment_data as $v) {
                            $comment_log = DwCommentLog::find()->where(
                                [
                                    'comment_id' => $v['id']
                                ])
                                ->orderBy(
                                    [
                                        'id' => SORT_DESC
                                    ])
                                ->asArray()
                                ->one();

                            if (!empty($comment_log)) {
                                $model['comment_status'] = $comment_log['operation_reason'];
                                // 更新评论到原状态
                                $flg = DwComment::updateAll($model,
                                    [
                                        'id' => $v['id']
                                    ]);
                            }
                        }
                    }
                }

                $return = [
                    'flg' => true,
                    'data' => Yii::t('backend', 'Success Operation')
                ];
            } else {
                $return = [
                    'flg' => false,
                    'data' => Yii::t('backend', 'Fail Operation')
                ];
            }
        } else {
            $return = [
                'flg' => false,
                'data' => Yii::t('backend', 'Parameter Error')
            ];
        }
        return json_encode($return);
    }

    /**
     * 移除黑名单用户缓存更新
     */
    protected function Update_black_redis()
    {
        $redis = Blacklist::find()->select(
            'blacklist_uid,blacklist_action_uid,blacklist_account_pid,blacklist_account_id,blacklist_level')
            ->asArray()
            ->all();
        if (!empty($redis)) {
            foreach ($redis as $s_key => $s_v) {
                $key = md5($s_v['blacklist_uid']);
                $a2[$key] = $s_v;
            }
            $value = @json_encode($a2);
            Yii::$app->redis->set('blacklist', $value);
        } else {
            $value = '';
            Yii::$app->redis->set('blacklist', '');
        }
        return $value;
    }
}
<?php
namespace backend\controllers;
use Yii;
use common\models\Comment;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\Report;
use app\models\ReportSearch;
use backend\models\ReportCategory;
use backend\models\ReportReplace;
use backend\models\search\DwCommentSearch;
use backend\models\DwComment;
use Qiniu\json_decode;
use backend\models\DwCommentExp;
use backend\models\DwAuthAccount;
use backend\models\DwCommentScore;
use backend\models\DwFourmCategoryItem;
use backend\models\DwCommentLog;

/**
 * CommentController implements the CRUD actions for Comment model.
 */
class ReportController extends Controller {

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
     * Lists all Comment models.
     *
     * @return mixed
     */
    public function actionIndex () {
        $issearch = 0; // 是否定有检索条件
        $report_status = (int) Yii::$app->request->get("report_status");
        $report_idtype = (int) Yii::$app->request->get("report_idtype");
        $report_uid = Yii::$app->request->get("report_uid");
        $report_from_uid = Yii::$app->request->get("report_from_uid");
        $start_time = strtotime(Yii::$app->request->get("start_time")) ? strtotime(
                Yii::$app->request->get("start_time")) : '';
        $end_time = strtotime(Yii::$app->request->get("end_time")) ? strtotime(
                Yii::$app->request->get("end_time")) : '';
        $report_content = Yii::$app->request->get("report_content");
        $report_account = Yii::$app->request->get("report_account");
        $report_title = Yii::$app->request->get("report_title");
        // $dataProvider = Report::find()->orderBy('id desc')->asArray()->all();
        // $query= Report::find();
        $query = Report::find()->joinWith('commentExp')->select(
                "dw_report.*,dw_comment_exp.comment_content");
        $search_flg = false;
        if (! empty($report_status)) {
            $issearch = 1;
            $query = $query->andFilterWhere(
                    [
                            'report_status' => $report_status
                    ]);
            $search_flg = true;
        }
        if (! empty($report_idtype)) {
            $issearch = 1;
            $query = $query->andFilterWhere(
                    [
                            'report_idtype' => $report_idtype
                    ]);
            $search_flg = true;
        }
        if (! empty($report_uid)) {
            $issearch = 1;
            $query = $query->andFilterWhere(
                    [
                            'report_uid' => $report_uid
                    ]);
            $search_flg = true;
        }
        if (! empty($report_from_uid)) {
            $issearch = 1;
            $query = $query->andFilterWhere(
                    [
                            'report_from_uid' => $report_from_uid
                    ]);
            $search_flg = true;
        }
        if (! empty($report_content)) {
            $issearch = 1;
            $query = $query->andFilterWhere(
                    [
                            'like',
                            'report_content',
                            $report_content
                    ]);
            $search_flg = true;
        }
        if (! empty($report_title)) {
            $issearch = 1;
            $query = $query->andFilterWhere(
                    [
                            'like',
                            'dw_comment_exp.comment_content',
                            $report_title
                    ]);
            $search_flg = true;
        }
        
        if (! empty($report_account)) {
            $issearch = 1;
            $account_id = DwAuthAccount::getCurrentAccount(1, $report_account);
            $query = $query->andWhere('report_account in (' . $account_id . ')');
            // $query = $query->andWhere(['report_account'=>$report_account]);
            $search_flg = true;
            // 举报类型
            $types = ReportCategory::get_report_type_redis($report_account);
        } else {
            $uid = Yii::$app->user->id;
            // $account_id = DwCommentSearch::getAccountByUid($uid);
            $account_id = DwAuthAccount::getCurrentAccount(1);
            $query = $query->andWhere('report_account in (' . $account_id . ')');
            // 举报类型
            $types = '';
        }
        if (! empty($start_time) || ! empty($end_time)) {
            $issearch = 1;
            if (! empty($start_time) && ! empty($end_time)) {
                $query = $query->andFilterWhere(
                        [
                                'between',
                                'report_create',
                                $start_time,
                                $end_time + 86400
                        ]);
            } elseif (! empty($start_time) && empty($end_time)) {
                $query = $query->andFilterWhere(
                        [
                                'between',
                                'report_create',
                                $start_time,
                                time()
                        ]);
            } elseif (empty($start_time) && ! empty($end_time)) {
                $query = $query->andFilterWhere(
                        [
                                'between',
                                'report_create',
                                0,
                                $end_time + 86400
                        ]);
            }
            $search_flg = true;
        }
        $dataProvider = new ActiveDataProvider(
                [
                        'query' => $query,
                        'sort' => [
                                'defaultOrder' => [
                                        'id' => SORT_DESC
                                ]
                        ]
                ]);
        
        // $types = ReportCategory::find()->asArray()->all();
        $model = new Report();
        return $this->render('index', 
                [
                        'dataProvider' => $dataProvider,
                        'types' => $types,
                        'model' => $model,
                        'report_status' => $report_status,
                        'report_idtype' => $report_idtype,
                        'issearch' => $issearch,
                        'report_uid' => $report_uid,
                        'report_from_uid' => $report_from_uid,
                        'start_time' => Yii::$app->request->get("start_time"),
                        'end_time' => Yii::$app->request->get("end_time"),
                        'report_content' => $report_content,
                        'report_account' => $report_account,
                        'report_title' => $report_title,
                        'search_flg' => $search_flg
                ]);
    }

    /**
     * 评论查看
     *
     * @param integer $id            
     * @return mixed
     */
    public function actionView ($id, $report_id, $from = 'index') {
        $data = DwComment::find()->joinWith('commentExp')
            ->select("dw_comment.*,dw_comment_exp.comment_content")
            ->where([
                'dw_comment.id' => $id
        ])
            ->one();
        $parent_data = '';
        $reportdata = Report::find()->where(
                [
                        'id' => $report_id
                ])->one();
        // 获取替换内容
        $replacedata = ReportReplace::get_report_replace_redis();
        if ($data->comment_parent_id > 0) {
            // 父类评论
            $parent_data = DwCommentExp::find()->where(
                    [
                            'id' => $data->comment_parent_id
                    ])
                ->asArray()
                ->one();
        } else {
            $parent_data['comment_content'] = '';
        }
        // 是否有评分
        $comment_score = DwCommentScore::get_comment_score($id);
        $comment_score_new = [];
        if ($comment_score) {
            $tagdelete = Yii::t('backend', 'Tag Delete');
            // $redis = Yii::$app->redis->get('fourm_item');
            // $redis = json_decode($redis,true);
            $redis = DwFourmCategoryItem::get_fourm_item_redis();
            $comment_html = $xingxing = $dafen = $biaoqian = '';
            foreach ($comment_score as $c_s_k => $c_s_v) {
                if (isset($redis[$c_s_v['item_id']])) {
                    $redis_item = $redis[$c_s_v['item_id']];
                    // 1:星型评分 2：打分评分 3：标签评分
                    if ($redis_item['fourm_item_tag_type'] == 3) {
                        $comment_score_new['tag']['checked_item_ext'][] = $c_s_v['item_ext_id'];
                        // $comment_score_new['tag']['checked_id'][] =
                        // $c_s_v['item_ext_id'];
                        $comment_score_new['tag']['title'] = $redis_item['fourm_item_title'];
                        $comment_score_new['tag']['item_ext'] = $redis_item['fourmCategoryItemExt'];
                    } else {
                        $comment_score_new[$c_s_k]['checked_item_ext'] = $c_s_v['item_ext_id'];
                        $comment_score_new[$c_s_k]['checked_id'] = $c_s_v['id'];
                        $comment_score_new[$c_s_k]['title'] = $redis_item['fourm_item_title'];
                        $comment_score_new[$c_s_k]['item_ext'] = $redis_item['fourmCategoryItemExt'];
                    }
                } else {
                    $comment_score_new[$c_s_k][$tagdelete]; // 标签已删除
                }
            }
        }
        
        // 审核日历
        $comment_log = DwCommentLog::find()->where(
                [
                        'comment_id' => $id
                ])
            ->asArray()
            ->all();
        // 编辑返回页面地址
        if (isset($_SERVER['HTTP_REFERER'])) {
            $back_url = $_SERVER['HTTP_REFERER'];
        } else {
            $back_url = Url::toRoute(
                    [
                            'comment/index'
                    ], true);
        }
        $comment_stat = DwComment::comment_stat_approval();
        $comment_stat_record = DwComment::comment_stat_edit();
        return $this->render('view', 
                [
                        'model' => DwComment::findOne($id),
                        'comment_stat' => $comment_stat,
                        'comment_stat_record' => $comment_stat_record,
                        'parent_data' => $parent_data,
                        'comment_score' => $comment_score_new,
                        'reportdata' => $reportdata,
                        'replacedata' => $replacedata,
                        'comment_log' => $comment_log,
                        'back_url' => $back_url,
                        'from' => 'report'
                ]);
    }

    /**
     * 所属账户等级联动
     *
     * @return mixed $account_id 账户id
     *         $change_area 联动区域class
     *         $check_val 默认选中值
     */
    public function actionCategory_account ($account_id, $check_val) {
        // $pid = DwAuthAccount::getAccountPidByAccountid($account_id);
        // if($pid == 0){
        // $pid = $account_id;
        // }
        $account = ReportCategory::get_report_type_redis($account_id);
        return $this->renderPartial('category_account', 
                [
                        'account' => $account,
                        'check_val' => $check_val
                ]);
    }

    /**
     * Finds the Comment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id            
     * @return Comment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel ($id) {
        if (($model = Report::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 举报信息删除
     */
    public function actionRepdel () {
        $id = Yii::$app->request->get("id");
        $report_comment_id = Yii::$app->request->get("report_comment_id");
        if (! empty($id)) {
            $flg1 = $this->findModel($id)->delete();
            return $this->redirect($_SERVER['HTTP_REFERER']);
        } else {
            $return = [
                    'flg' => false,
                    'data' => Yii::t('backend', 'Parameter Error')
            ];
        }
        return json_encode($return);
    }

    /**
     * 查看举报
     */
    public function actionRepview () {
        $id = Yii::$app->request->post("id");
        if (! empty($id)) {
            $data = Report::find()->where(
                    [
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
                        'data' => Yii::t('backend', 'Without This Data')
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
     * 审核举报
     */
    public function actionReedit () {
        $id = (int) Yii::$app->request->post("id");
        $report_status = (int) Yii::$app->request->post("report_status") ? (int) Yii::$app->request->post(
                "report_status") : 1;
        if (! empty($id) && ! empty($report_status)) {
            $save['report_status'] = $report_status;
            $save['report_audtime'] = time();
            $flg = Report::updateAll($save, 
                    [
                            'id' => $id
                    ]);
            
            if ($flg) {
                $return = [
                        'flg' => true,
                        'data' => Yii::t('backend', 'Audit Sucess')
                ];
                // return $this->redirect(['index']);
            } else {
                $return = [
                        'flg' => false,
                        'data' => Yii::t('backend', 'Audits Fail')
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
     * 审核分类
     */
    public function actionCategory () {
        $query = ReportCategory::find();
        $uid = Yii::$app->user->id;
        $account_where = DwAuthAccount::getCurrentAccount(2);
        
        // 查询当前用户下，有权限的账户数据
        if (! empty($account_where)) {
            // $account_where = implode(',',$account_where['parent_account'] );
            // 此主账户下的子账户数据
            $query = $query->orWhere(
                    'report_account_id in (' . $account_where . ')');
            // 此主账户下的数据
            $query = $query->orWhere(
                    'report_account_pid in (' . $account_where . ')');
        } else {
            $query = $query->andWhere(
                    [
                            'id' => 0
                    ]);
        }
        $dataProvider = new ActiveDataProvider(
                [
                        'query' => $query,
                        'sort' => [
                                'defaultOrder' => [
                                        'id' => SORT_DESC
                                ]
                        ]
                ]);
        $model = new ReportCategory();
        return $this->render('category', 
                [
                        'dataProvider' => $dataProvider,
                        'model' => $model
                ]);
    }

    /**
     * 添加分类
     */
    public function actionCategory_add () {
        $name = Yii::$app->request->post("name");
        $account = Yii::$app->request->post("account");
        $pid = DwAuthAccount::getAccountPidByAccountid($account);
        $save_pid = $pid;
        if (! empty($name) && ! empty($account)) {
            if ($pid == 0) {
                $pid = $account;
            }
            $data = ReportCategory::find()->where(
                    [
                            'report_type_title' => $name
                    ])
                ->andWhere(
                    [
                            'or',
                            [
                                    'report_account_id' => $pid
                            ],
                            [
                                    'report_account_pid' => $pid
                            ]
                    ])
                ->asArray()
                ->one();
            if (! empty($data)) {
                $msg = Yii::t('backend', 'Already Exist');
                $return = [
                        'flg' => false,
                        'data' => '【' . $name . '】"' . $msg . '"'
                ];
            } else {
                $model = new ReportCategory();
                $model->report_type_title = $name;
                $model->report_account_id = $account;
                $model->report_account_pid = $save_pid;
                $model->report_type_create = time();
                $flg = $model->insert();
                if ($flg) {
                    $return = [
                            'flg' => true,
                            'data' => Yii::t('backend', 'Add Sucess')
                    ];
                    $this->update_report_type_redis($account);
                    return $this->redirect(
                            [
                                    'category'
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

    public function findCmodel ($id) {
        if (($model = ReportCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 分类状态修改
     */
    public function actionState () {
        $id = Yii::$app->request->post("id");
        if (! empty($id)) {
            $report_category = ReportCategory::findOne($id);
            if ($report_category->report_type_state == 1) {
                $report_category->report_type_state = 2;
                //$report_category->save();
               ReportCategory::updateAll(['report_type_state'=>2],['id'=>$id]);
            } else {
                $report_category->report_type_state = 1;
                //$report_category->save();
                ReportCategory::updateAll(['report_type_state'=>1],['id'=>$id]);
            }
            $this->update_report_type_redis($report_category->report_account_id);
            return $this->redirect(
                    [
                            'category'
                    ]);
        } else {
            $return = [
                    'flg' => false,
                    'data' => Yii::t('backend', 'Parameter Error')
            ];
        }
        return json_encode($return);
    }

    /**
     * 查看分类
     */
    public function actionCateview () {
        $id = Yii::$app->request->post("id");
        if (! empty($id)) {
            $data = ReportCategory::find()->where(
                    [
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
                        'data' => Yii::t('backend', 'Without This Data')
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
     * 编辑分类
     */
    public function actionCatedit () {
        $id = (int) Yii::$app->request->post("id");
        $report_type_title = Yii::$app->request->post("name");
        if (! empty($id) && ! empty($report_type_title)) {
            $ReportCategory = ReportCategory::findOne($id);
            $account_id = $ReportCategory->report_account_id;
            $pid = $ReportCategory->report_account_pid;
            if ($pid == 0) {
                $pid = $account_id;
            }
            $data = ReportCategory::find()->where(
                    [
                            'report_type_title' => $report_type_title
                    ])
                ->andWhere(
                    [
                            'or',
                            [
                                    'report_account_id' => $pid
                            ],
                            [
                                    'report_account_pid' => $pid
                            ]
                    ])
                ->andWhere(
                    [
                            '<>',
                            'id',
                            $id
                    ])
                ->asArray()
                ->one();
            
            if (! empty($data)) {
                $msg = Yii::t('backend', 'Already Exist');
                $return = [
                        'flg' => false,
                        'data' => '【' . $report_type_title . '】"' . $msg . '"'
                ];
            } else {
                $save['report_type_title'] = $report_type_title;
                $save['report_type_create'] = time();
                $flg = ReportCategory::updateAll($save, 
                        [
                                'id' => $id
                        ]);
                if ($flg) {
                    $return = [
                            'flg' => true,
                            'data' => Yii::t('backend', 'Editor Sucess')
                    ];
                    $edit_da = ReportCategory::findOne($id);
                    $this->update_report_type_redis($edit_da->report_account_id);
                    return $this->redirect(
                            [
                                    'category'
                            ]);
                } else {
                    $return = [
                            'flg' => false,
                            'data' => Yii::t('backend', 'Editor Fail')
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

    /**
     * 举报分类缓存
     *
     * @param
     *            integeR
     * @return mixed $account_id 更新数据所属账户id
     *         更新缓存的主账户
     */
    public function update_report_type_redis ($account_id) {
        // $redis = ReportCategory::find()
        // ->orwhere(['report_account_pid'=>$pid])
        // ->orwhere(['report_account_id'=>$pid])
        // ->asArray()->all();
        // if( !empty($redis)){
        // foreach ( $redis as $s_key =>$s_v){
        // $a2[$s_v['id']] = $s_v;
        // }
        // $value = @json_encode($a2);
        // Yii::$app->redis->set('report_type',$value);
        // }else {
        // $value='';
        // Yii::$app->redis->set('report_type',$value);
        // }
        // return $value;
        ReportCategory::update_report_type_redis($account_id);
    }

    /**
     * 举报替换
     */
    public function actionReplace () {
        $query = ReportReplace::find();
        
        $dataProvider = new ActiveDataProvider(
                [
                        'query' => $query,
                        'sort' => [
                                'defaultOrder' => [
                                        'id' => SORT_DESC
                                ]
                        ]
                ]);
        $model = new ReportReplace();
        return $this->render('replace', 
                [
                        'dataProvider' => $dataProvider,
                        'model' => $model
                ]);
    }

    /**
     * 添加举报替换
     */
    public function actionReplace_add () {
        $name = Yii::$app->request->post("name");
        $account = Yii::$app->request->post("account");
        $pid = DwAuthAccount::getAccountPidByAccountid($account);
        $save_pid = $pid;
        if (! empty($name) && ! empty($account)) {
            if ($pid == 0) {
                $pid = $account;
            }
            $data = ReportReplace::find()->where(
                    [
                            'report_replace_content' => $name
                    ])
                ->andWhere(
                    [
                            'or',
                            [
                                    'report_account_id' => $pid
                            ],
                            [
                                    'report_account_pid' => $pid
                            ]
                    ])
                ->asArray()
                ->one();
            if (! empty($data)) {
                $msg = Yii::t('backend', 'Already Exist');
                $return = [
                        'flg' => false,
                        'data' => '【' . $name . '】"' . $msg . '"'
                ];
            } else {
                $model = new ReportReplace();
                $model->report_replace_content = $name;
                $model->report_account_id = $account;
                $model->report_account_pid = $save_pid;
                $model->report_replace_create = time();
                $flg = $model->insert();
                if ($flg) {
                    $return = [
                            'flg' => true,
                            'data' => Yii::t('backend', 'Add Sucess')
                    ];
                    $this->update_report_replace_redis();
                    return $this->redirect(
                            [
                                    'replace'
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

    public function update_report_replace_redis () {
        ReportReplace::update_report_replace_redis();
    }

    /**
     * 查看分类
     */
    public function actionReplaceview () {
        $id = Yii::$app->request->post("id");
        if (! empty($id)) {
            $data = ReportReplace::find()->where(
                    [
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
                        'data' => Yii::t('backend', 'Without This Data')
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
     * 编辑分类
     */
    public function actionReplaceedit () {
        $id = (int) Yii::$app->request->post("id");
        $report_replace_content = Yii::$app->request->post("name");
        if (! empty($id) && ! empty($report_replace_content)) {
            $ReportCategory = ReportCategory::findOne($id);
            $account_id = $ReportCategory->report_account_id;
            $pid = $ReportCategory->report_account_pid;
            if ($pid == 0) {
                $pid = $account_id;
            }
            $data = ReportReplace::find()->where(
                    [
                            'report_replace_content' => $report_replace_content
                    ])
                ->andWhere(
                    [
                            'or',
                            [
                                    'report_account_id' => $pid
                            ],
                            [
                                    'report_account_pid' => $pid
                            ]
                    ])
                ->andWhere(
                    [
                            '<>',
                            'id',
                            $id
                    ])
                ->asArray()
                ->one();
            if (! empty($data)) {
                $msg = Yii::t('backend', 'Already Exist');
                $return = [
                        'flg' => false,
                        'data' => '【' . $report_replace_content . '】"' . $msg .
                                 '"'
                ];
            } else {
                $save['report_replace_content'] = $report_replace_content;
                $save['report_replace_create'] = time();
                $flg = ReportReplace::updateAll($save, 
                        [
                                'id' => $id
                        ]);
                if ($flg) {
                    $return = [
                            'flg' => true,
                            'data' => Yii::t('backend', 'Editor Sucess')
                    ];
                    $edit_da = ReportReplace::findOne($id);
                    $this->update_report_replace_redis();
                    return $this->redirect(
                            [
                                    'replace'
                            ]);
                } else {
                    $return = [
                            'flg' => false,
                            'data' => Yii::t('backend', 'Editor Fail')
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

    /**
     * 举报信息删除
     */
    public function actionReplacedel () {
        $id = Yii::$app->request->post("id");
        if (! empty($id)) {
            $flg1 = $this->findsModel($id)->delete();
            $this->update_report_replace_redis();
            return $this->redirect($_SERVER['HTTP_REFERER']);
        } else {
            $return = [
                    'flg' => false,
                    'data' => Yii::t('backend', 'Parameter Error')
            ];
        }
        return json_encode($return);
    }

    public function findsModel ($id) {
        if (($model = ReportReplace::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

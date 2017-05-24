<?php
namespace backend\controllers;

use Yii;
use common\models\Comment;
use backend\models\DwComment;
use backend\models\search\DwCommentSearch;
use backend\models\DwFourmCategoryItem;
use backend\models\DwFourmCategory;
use backend\models\DwFourmGenerateArea;
use backend\models\DwPropsCategory;
use backend\models\DwemoticonCategory;
use backend\models\DwAuthAccount;
use backend\models\Report;
use backend\models\DwFourmCategoryItemExt;
use backend\models\DwCommentScore;
use backend\models\DwCommentLog;
use backend\models\Blacklist;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\DwCommentExp;
use GuzzleHttp\json_encode;
use Qiniu\json_decode;
use yii\helpers\Url;
use mPDF;

/**
 * CommentController implements the CRUD actions for Comment model.
 */
class CommentController extends Controller
{
    // public
    // $comment_stat=[1=>Yii::t('backend','Release'),2=>Yii::t('backend','To
    // Examine'),3=>Yii::t('backend','Hidden')];
    // public
    // $comment_stat_all=[1=>Yii::t('backend','Release'),2=>Yii::t('backend','To
    // Examine'),3=>Yii::t('backend','Hidden'),4=>Yii::t('backend','Auto
    // Inspection'),5=>Yii::t('backend','Sensitive
    // Inspection'),6=>Yii::t('backend','Report
    // Inspection'),7=>Yii::t('backend','User Frozen'),8=>Yii::t('backend','User
    // Lock')];
    // public $comment_stat_record=[4=>Yii::t('backend','Auto
    // Inspection'),5=>Yii::t('backend','Sensitive
    // Inspection'),6=>Yii::t('backend','Report Inspection')];
    // public
    // $comment_stat_search=[1=>Yii::t('backend','Release'),3=>Yii::t('backend','Hidden'),7=>Yii::t('backend','User
    // Frozen'),8=>Yii::t('backend','User Lock')];

    // public $comment_stat=[1=>'发布',2=>'审核',3=>'隐藏'];
    // public
    // $comment_stat_all=[1=>'发布',2=>'审核',3=>'隐藏',4=>'自动送审',5=>'敏感词送审',6=>'举报送审',7=>'用户冻结',8=>'用户锁定'];
    // public $comment_stat_record=[4=>'自动送审',5=>'敏感词送审',6=>'举报送审'];
    // public $comment_stat_search=[1=>'发布',3=>'隐藏',7=>'用户冻结',8=>'用户锁定'];
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
     * 评论管理首页
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searcharray = Yii::$app->request->get("DwCommentSearch");
        $issearch = 0; // 是否定有检索条件
        if (!empty($searcharray)) {
            $issearch = 1;
        }
        $uid = Yii::$app->user->id;
        $searchModel = new DwCommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,
            $uid);
        $comment_channel_area_id = Yii::$app->request->get('DwCommentSearch');
        $comment_channel_area_id = $comment_channel_area_id['comment_channel_area'];
        $model = new DwComment();
        $comment_stat_search = DwComment::comment_stat_search();
        return $this->render('index',
            [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'comment_stat_search' => $comment_stat_search,
                'issearch' => $issearch,
                'comment_channel_area_id' => $comment_channel_area_id
            ]);
    }

    /**
     * Lists all Comment models.
     *
     * @return mixed
     * @return mixed
     */
    public function actionApproval()
    {
        $searcharray = Yii::$app->request->get("DwCommentSearch");
        $issearch = 0; // 是否定有检索条件
        if (!empty($searcharray)) {
            $issearch = 1;
        }
        $searchModel = new DwCommentSearch();
        $uid = Yii::$app->user->id;
        $dataProvider = $searchModel->approval_search(
            Yii::$app->request->queryParams, $uid);
        $model = new DwComment();
        $comment_channel_area_id = Yii::$app->request->get('DwCommentSearch');
        $comment_channel_area_id = $comment_channel_area_id['comment_channel_area'];
        $comment_stat_search = DwComment::comment_stat_approval_search();
        return $this->render('approval',
            [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'issearch' => $issearch,
                'comment_stat_search' => $comment_stat_search,
                'comment_channel_area_id' => $comment_channel_area_id
            ]);
    }

    /**
     * 评论查看
     *
     * @param integer $id
     * @return mixed
     */
    public function actionExport()
    {
        $this->layout = 'main_export';
        $id = Yii::$app->request->get("id");
        $report_id = Yii::$app->request->get("report_id");
        $this->layout = 'main_export';
        $data = DwComment::findOne($id);
        // $data
        // =DwComment::find()->joinWith('commentExp')->select("dw_comment.*,dw_comment_exp.comment_content")->where(['dw_comment.id'=>$id])->one();
        $parent_data = '';
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

        // 审核日历
        $comment_log = DwCommentLog::find()->where(
            [
                'comment_id' => $id
            ])
            ->asArray()
            ->all();

        $array = [
            'model' => $data,
            'parent_data' => $parent_data,
            'comment_log' => $comment_log
        ];
        if ($data->comment_status == Yii::$app->params['sensitive_audit']) {
            // 检测敏感词
            $sensitive_word = $this->sensitive_check(
                $data->commentExp->comment_content,
                $data->comment_channel_area);
            $array['sensitive_word'] = $sensitive_word;
        } elseif ($data->comment_status == Yii::$app->params['report_audit']) {
            // 举报数据
            $report = Report::find()->where(
                [
                    'id' => $report_id
                ])
                ->asArray()
                ->one();
            if (!empty($report)) {
                $array['report'] = $report;
            }
        }

        $redis = $this->render('view_export', $array);
        $time = date('Ymd', time());
        $uid = Yii::$app->user->id;
        $pdf_name = $uid . '_' . $id . '_' . $time . '_vidence.pdf';
        $mpdf = new \mPDF('UTF-8', 'A4', '', '', 15, 15, 44, 15);
        $mpdf->useAdobeCJK = true;
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        $mpdf->SetDisplayMode('fullpage');
        // $url =
        // 'http://focus.dwnews.com/admin/index.php?r=comment%2Fview&id=6340&from=approval&comment_status=5
        // ';
        // $html = Yii::$app->redis->get('explrd');
        // $strContent = file_get_contents($url);
        $mpdf->showWatermarkText = true;
        $mpdf->WriteHTML($redis);
        $mpdf->Output($pdf_name, true);
    }

    /**
     * 评论查看
     *
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $from = 'index')
    {
        $data = DwComment::findOne($id);
        // $data
        // =DwComment::find()->joinWith('commentExp')->select("dw_comment.*,dw_comment_exp.comment_content")->where(['dw_comment.id'=>$id])->one();
        $parent_data = '';
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

        $array = [
            'model' => $data,
            'comment_stat' => $comment_stat,
            'comment_stat_record' => $comment_stat_record,
            'parent_data' => $parent_data,
            'comment_score' => $comment_score_new,
            'comment_log' => $comment_log,
            'back_url' => $back_url,
            'from' => $from
        ];
        if ($data->comment_status == Yii::$app->params['sensitive_audit']) {
            // 检测敏感词
            $sensitive_word = $this->sensitive_check(
                $data->commentExp->comment_content,
                $data->comment_channel_area);
            $array['sensitive_word'] = $sensitive_word;
        }

        return $this->render('view', $array);
    }

    /**
     * 评论编辑保存
     *
     * @param integer $id
     * @return mixed
     */
    public function actionView_save()
    {
        $id = Yii::$app->request->post('id');
        $stat = Yii::$app->request->post('comment_status'); // 要更新的状态
        // $comment_status_public
        // =
        // Yii::$app->request->post('comment_status_public');//发布的状态
        $old_comment_status = Yii::$app->request->post('old_comment_status'); // 操作前旧的状态
        $from = Yii::$app->request->post('from');
        $replace_content = Yii::$app->request->post('replace_content');

        if (!empty($id)) {
            $transaction = \Yii::$app->db->beginTransaction();
            $flg1 = $flg2 = $flg3 = $flg4 = true;

            $comment_save['comment_status'] = $stat;
            $comment_save['comment_examine_at'] = time();

            $comment_log['comment_status'] = $stat;
            $comment_log['operation_reason'] = $old_comment_status;

            $flg1 = DwComment::updateAll($comment_save,
                [
                    'id' => $id
                ]);
            // 此评论是否被举报,举报状态设置为成功
            $flg2 = $this->Update_report_stat($id, $stat);

            if ($from == 'report') {
                // 如果替换内容不为空则更新评论表
                if (!empty($replace_content)) {
                    // 更新评论表
                    $commentexp_save['comment_content'] = $replace_content;
                    $flg4 = DwCommentExp::updateAll($commentexp_save,
                        [
                            'id' => $id
                        ]);
                }
            }

            // if($from == 'index' ){
            // $comment_save['comment_status'] = $stat;
            // $comment_save['comment_examine_at'] = time();

            // $comment_log['comment_status'] = $stat;
            // $comment_log['operation_reason'] = $stat;

            // // 此评论是否被举报,举报状态设置为成功
            // $flg2 = $this->Update_report_stat($id,$stat);
            // }elseif($from == 'approval' ){
            // $comment_save['comment_status'] = $comment_status_public;
            // $comment_save['comment_examine_at'] = time();

            // $comment_log['comment_status'] = $comment_status_public;
            // $comment_log['operation_reason'] = $stat;

            // // 此评论是否被举报,举报状态设置为成功
            // $flg2 = $this->Update_report_stat($id,$comment_status_public);
            // }elseif($from == 'report'){
            // $comment_save['comment_status'] = $comment_status_public;
            // $comment_save['comment_examine_at'] = time();

            // $comment_log['comment_status'] = $comment_status_public;
            // $comment_log['operation_reason'] = $stat;

            // // 此评论是否被举报,举报状态设置为成功
            // $flg2 = $this->Update_report_stat($id,$comment_status_public);
            // // 如果替换内容不为空则更新评论表
            // if(!empty($replace_content)){
            // // 更新评论表
            // $commentexp_save['comment_content']=$replace_content;
            // $flg4 = DwCommentExp::updateAll($commentexp_save,['id'=>$id]);
            // }

            // }

            // $report_count =
            // Report::find()->where(['report_comment_id'=>$id,'report_status'=>1])->count();
            // if( $report_count > 0 ){
            // $report_data['report_status'] = 2;
            // $report_data['report_audtime'] = time();
            // $flg2=Report::updateAll($report_data,['report_comment_id'=>$id]);
            // }
            // 操作审核日志
            $DwCommentLogmodel = new DwCommentLog();
            $DwCommentLogmodel->comment_id = $id;
            $DwCommentLogmodel->comment_status = $comment_log['comment_status'];
            $DwCommentLogmodel->operation_reason = $comment_log['operation_reason'];
            $DwCommentLogmodel->operation_id = Yii::$app->user->id;
            $DwCommentLogmodel->operation_time = time();
            $flg3 = $DwCommentLogmodel->insert();

            if (!$flg1 || !$flg2 || !$flg3 || !$flg4) {
                $transaction->rollback();
                return Yii::t('backend', 'Abnormaloper'); // 操作异常,请联系管理员
            } else { // save表示不校验数据
                $transaction->commit();
                $this->update_comment_stat_redis($id);
                if ($from == 'report') {
                    $this->redirect(
                        array(
                            'report/index'
                        ));
                } else {
                    $this->redirect(
                        [
                            $from
                        ]);
                }
            }
        }
    }

    /**
     * 评论编辑保存
     *
     * @param integer $id
     * @return mixed
     */
    public function actionTest()
    {
        $a = rand(1, 9);
        if ($a > 5) {
            $f = true;
        } else {
            $f = false;
        }
        $return = [
            'flg' => $f,
            'data' => Yii::t('backend', 'Delete Success')
        ]; // 删除成功

        return json_encode($return);
    }

    /**
     * 举报数据状态更新
     *
     * @param integer $id
     * @return mixed
     */
    public function Update_report_stat($comment_id, $stats = null)
    {
        $flg = true;
        if (is_array($comment_id)) {
            // 此评论是否被举报,状态自动为审核 成功
            $ids = implode(',', $comment_id);
            $report_count = Report::find()->where(
                'report_comment_id in (' . $ids . ')')
                ->andFilterWhere(
                    [
                        'report_status' => 1
                    ])
                ->count();
            if ($report_count > 0) {
                $report_data['report_status'] = 2;
                $report_data['report_audtime'] = time();
                $flg = Report::updateAll($report_data,
                    'report_comment_id in (' . $ids . ')');
            }
            if ($stats == Yii::$app->params['public'] ||
                $stats == Yii::$app->params['hidden']
            ) {
                // 发布
                foreach ($comment_id as $key => $v) {
                    $redis = Yii::$app->redis->get(md5('comment_report_' . $v));
                    $redis = json_decode($redis, true);
                    if (!empty($redis)) {
                        $redis['count'] = 0;
                        Yii::$app->redis->set(md5('comment_report_' . $v),
                            json_encode($redis));
                    }
                }
            }
        } else {
            // 此评论是否被举报,举报状态设置为成功
            $report_count = Report::find()->where(
                [
                    'report_comment_id' => $comment_id
                ])
                ->andFilterWhere(
                    [
                        'report_status' => 1
                    ])
                ->count();
            if ($report_count > 0) {
                $report_data['report_status'] = 2;
                $report_data['report_audtime'] = time();
                $flg = Report::updateAll($report_data,
                    [
                        'report_comment_id' => $comment_id
                    ]);
            }
            if ($stats == Yii::$app->params['public'] ||
                $stats == Yii::$app->params['hidden']
            ) {
                // 发布
                $redis = Yii::$app->redis->get(
                    md5('comment_report_' . $comment_id));
                $redis = json_decode($redis, true);
                if (!empty($redis)) {
                    $redis['count'] = 0;
                    Yii::$app->redis->set(md5('comment_report_' . $comment_id),
                        json_encode($redis));
                }
            }
        }
        return $flg;
    }

    /**
     * 举报数据删除
     *
     * @param integer $id
     * @return mixed
     */
    public function del_report_stat($comment_id)
    {
        // 举报删除TODO
        $flg = Report::deleteAll('report_comment_id in (' . $comment_id . ')');
        return $flg;
    }

    /**
     * 评论删除
     *
     * @param integer $id
     * @return mixed
     */
    public function actionComment_del($delid)
    {
        if (!is_numeric($delid)) {
            $return = [
                'flg' => false,
                'data' => Yii::t('backend', 'Parameter Error')
            ]; // 参数错误
            return json_encode($return);
        }
        $comment = DwComment::get_comment_redis($delid);
        if (empty($comment)) {
            $return = [
                'flg' => false,
                'data' => Yii::t('backend', 'No The data')
            ]; // 无此数据
            return json_encode($return);
        }
        $comment_url = $comment['comment_url'];
        // $id= Yii::$app->request->post('id');
        $id = $delid;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            // 本条数据
            $flg1 = DwComment::findOne($id)->delete();
            $flg2 = DwCommentExp::findOne($id)->delete();
            // 缓存删除
            $this->del_relevant_redis($id);
            $flg3 = $flg4 = true;
            // 数据下的回复
            $ziji = DwComment::find()->select('id')
                ->where(
                    [
                        'comment_parent_id' => $id
                    ])
                ->asArray()
                ->all();
            $val = '';
            if (!empty($ziji)) {
                foreach ($ziji as $key => $v) {
                    $val[] = $v['id'];
                }
                // 缓存删除
                $this->del_relevant_redis($val);
                $val = implode(',', $val);
                $flg3 = DwComment::deleteAll(
                    [
                        'comment_parent_id' => $id
                    ]);
                $flg4 = DwCommentExp::deleteAll('id in (' . $val . ')');
            }

            // 评论积分
            DwCommentScore::deleteAll(
                [
                    'comment_id' => $id
                ]);

            // 举报删除TODO
            // if( !empty( $val )){
            // $val .= ','.$id;
            // }else{
            // $val = $id;
            // }
            // $this->del_report_stat($val);
            // 评论日历
            DwCommentLog::deleteAll(
                [
                    'comment_id' => $id
                ]);
            // 评论数缓存
            $this->update_comment_count_redis($comment_url);
            $transaction->commit();
            return $this->redirect($_SERVER['HTTP_REFERER']);
            $return = [
                'flg' => true,
                'data' => Yii::t('backend', 'Delete Success')
            ]; // 删除成功
        } catch (\Exception $e) {
            $transaction->rollback();
            $return = [
                'flg' => false,
                'data' => Yii::t('backend', 'Delete Error')
            ]; // 删除失败
        }
        return json_encode($return);
    }

    /**
     * 批量操作
     *
     * @param integer $id
     * @return mixed
     */
    public function actionComment_batch()
    {
        $id = Yii::$app->request->post('id');
        $type = Yii::$app->request->post('type');
        $check_url = Yii::$app->request->post('check_url');
        $comment_stat_all = DwComment::comment_stat_all();
        // 事物
        $transaction = \Yii::$app->db->beginTransaction();
        if ($type == 'del') { // 删除
            $ids = implode(',', $id);
            // 本条数据
            $flg1 = DwComment::deleteAll('id in (' . $ids . ')');
            $flg2 = DwCommentExp::deleteAll('id in (' . $ids . ')');
            $this->del_relevant_redis($id); // 主屏相关缓存
            $flg3 = $flg4 = true;
            // 数据下的回复
            $ziji = DwComment::find()->select('id')
                ->where('comment_parent_id in (' . $ids . ')')
                ->asArray()
                ->all();
            $val = '';
            if (!empty($ziji)) {
                foreach ($ziji as $key => $v) {
                    $val[] = $v['id'];
                }
                $this->del_relevant_redis($val);
                $val = implode(',', $val);
                $flg3 = DwComment::deleteAll('id in (' . $val . ')');
                $flg4 = DwCommentExp::deleteAll('id in (' . $val . ')');
            }
            // 评论积分
            DwCommentScore::deleteAll('comment_id in (' . $ids . ')');
            // 举报删除TODO
            // if( !empty( $val )){
            // $val .= ','.$ids;
            // }else{
            // $val = $ids;
            // }
            // $this->del_report_stat($val);
            // 日志记录
            DwCommentLog::deleteAll('comment_id in (' . $ids . ')');
            // 评论缓存数,
            $check_url = array_unique($check_url);
            foreach ($check_url as $check_url_key => $check_url_v) {
                $this->update_comment_count_redis($check_url_v);
            }
            if (!$flg1 || !$flg2 || !$flg3 || !$flg4) {
                $transaction->rollback();
                $return = [
                    'flg' => false,
                    'data' => Yii::t('backend', 'Batch Delete Fail')
                ]; // 批量删除失败
            } else { // save表示不校验数据
                $transaction->commit();
                $return = [
                    'flg' => true,
                    'data' => Yii::t('backend', 'Batch Delete Success')
                ]; // 批量删除成功
            }
        } elseif (isset($comment_stat_all[$type])) {
            $ids = implode(',', $id);
            $flg1 = $flg2 = $flg3 = true;
            // 批量审核
            $save['comment_status'] = $type;
            $save['comment_examine_at'] = time();
            $flg1 = DwComment::updateAll($save, 'id in (' . $ids . ')');
            // 此评论是否被举报,状态自动为审核 成功
            $flg2 = $this->Update_report_stat($id, $type);
            $operation_reason = Yii::$app->request->post('reason');
            foreach ($id as $id_key => $id_v) {
                // 操作审核日志
                $DwCommentLogmodel = new DwCommentLog();
                $DwCommentLogmodel->comment_id = $id_v;
                $DwCommentLogmodel->comment_status = $type;
                $operation_reason_redis = DwComment::get_comment_redis($id_v);
                $DwCommentLogmodel->operation_reason = $operation_reason_redis['comment_status'];
                // 把送审原因删除了，送审原因取每个评论的原有状态，如果恢复，在approval视图中，批量操作处添加，onchang事件reason_show,batch_operation方法加非空判断
                // $DwCommentLogmodel->operation_reason = $operation_reason;
                $DwCommentLogmodel->operation_id = Yii::$app->user->id;
                $DwCommentLogmodel->operation_time = time();
                $flg3 = $DwCommentLogmodel->insert();
            }
            if (!$flg1 || !$flg2 || !$flg3) {
                $transaction->rollback();
                $return = [
                    'flg' => false,
                    'data' => Yii::t('backend', 'Batch Audit Fail')
                ]; // 批量审核失败
            } else { // save表示不校验数据
                $transaction->commit();
                $this->update_comment_stat_redis($id);
                $return = [
                    'flg' => true,
                    'data' => Yii::t('backend', 'Batch Audit Success')
                ]; // 批量审核成功
            }
        } elseif ($type == 'blicklist') {
            // 移入黑名单
            $uids = Yii::$app->request->post('check_uid');
            $check_account_id = Yii::$app->request->post('check_account_id');
            $uid = Yii::$app->user->id;
            if (is_array($uids)) {

                $flg = true;
                foreach ($uids as $u_key => $u_v) {
                    // 检查黑名单是否存在
                    $redis_blacklist = Blacklist::get_blacklist_redis();
                    if (in_array($u_v, $redis_blacklist)) {
                        continue;
                    }
                    $model = new Blacklist();
                    $model->blacklist_uid = $u_v;
                    $model->blacklist_account_id = $check_account_id[$u_key];
                    $pid = DwAuthAccount::getAccountPidByAccountid(
                        $check_account_id[$u_key]);
                    if ($pid == 0) {
                        $pid = $check_account_id[$u_key];
                    }
                    $model->blacklist_account_pid = $pid;
                    $model->blacklist_level = 1;
                    $model->blacklist_action_uid = $uid;
                    $model->blacklist_create = time();
                    $insert_flg = $model->insert();
                    if (!$insert_flg) {
                        $flg = false;
                    }
                }

                if (!$flg) {
                    $transaction->rollback();
                    $return = [
                        'flg' => false,
                        'data' => Yii::t('backend', 'Fail Peration')
                    ]; // 操作失败
                } else {
                    // 黑名单缓存
                    Blacklist::update_blacklist_redis();
                    $transaction->commit();
                    $return = [
                        'flg' => true,
                        'data' => Yii::t('backend', 'Success Operation')
                    ]; // 操作成功
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
     * Displays a single Comment model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionFourm()
    {
        $uid = Yii::$app->user->id;
        $web_type = Yii::$app->request->get('web_type');
        if ($web_type == 'item' || empty($web_type)) {
            // 道具
            $props_redis = DwPropsCategory::get_category_redis_all();
            // 表情
            $emotion_redis = DwemoticonCategory::getCate_redis();
            // 表单设定
            $account_where = DwAuthAccount::getCurrentAccount(1);
            $query = DwFourmCategoryItem::find();
            $query = $query->where(
                'fourm_item_account in (' . $account_where . ')');
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $query,
                    'sort' => [
                        'defaultOrder' => [
                            'fourm_item_idtype' => SORT_ASC,
                            'id' => SORT_DESC
                        ]
                    ]
                ]);
            return $this->render('fourm_item',
                [
                    'dataProvider' => $dataProvider,
                    'props_redis' => $props_redis,
                    'emotion_redis' => $emotion_redis
                ]);
        } elseif ($web_type == 'category') {
            // 表单类型
            $model = new DwFourmCategory();
            // $category_item = $this->get_fourm_item_redis();
            // $account_where =DwCommentSearch::getAccountByUid($uid);
            $account_where = DwAuthAccount::getCurrentAccount(1);
            $query = DwFourmCategory::find();
            $query = $query->where('fourm_account in (' . $account_where . ')');
            $dataProvider_category = new ActiveDataProvider(
                [
                    'query' => $query,
                    'sort' => [
                        'defaultOrder' => [
                            'id' => SORT_DESC
                        ]
                    ]
                ]);
            return $this->render('fourm_category',
                [
                    // 'category_item'=>$category_item,
                    'dataProvider_category' => $dataProvider_category,
                    'model' => $model
                ]);
        } elseif ($web_type == 'area') {
            // 区域设置
            $dataProvider_area = new ActiveDataProvider(
                [
                    'query' => DwFourmGenerateArea::find(),
                    'sort' => [
                        'defaultOrder' => [
                            'id' => SORT_DESC
                        ]
                    ]
                ]);
            return $this->render('fourm_area',
                [
                    'dataProvider_area' => $dataProvider_area
                ]);
        } elseif ($web_type == 'makeing') {
            return $this->render('fourm_makeing', []);
        }
    }

    /**
     * 表单设置添加
     *
     * @param
     *            integeR
     * @return mixed
     */
    public function actionFourm_category_item_add()
    {
        $fourm_item_idtype = Yii::$app->request->post('fourm_item_idtype');
        $model = new DwFourmCategoryItem();

        // 公共参数
        $fourm_item_title = Yii::$app->request->post('fourm_item_title');
        $fourm_item_is_ver = Yii::$app->request->post('fourm_item_is_ver');
        $fourm_item_account = Yii::$app->request->post('fourm_item_account');
        $model->fourm_item_title = $fourm_item_title;
        $model->fourm_item_is_ver = $fourm_item_is_ver;
        $model->fourm_item_account = $fourm_item_account;
        $id = Yii::$app->request->post('id');

        if (!empty($fourm_item_idtype) && $fourm_item_idtype == 2) {
            $model->fourm_item_idtype = 2;
            $fourm_item_content['img_content_online'] = Yii::$app->request->post(
                'img_content_online');
            $fourm_item_content['img_content_path'] = Yii::$app->request->post(
                'img_content_path');
            $fourm_item_content['img_content_type'] = Yii::$app->request->post(
                'img_content_type');
            // 图片类型时，fourm_item_tag 传递的是图片类型中贴纸表情的id
            $fourm_item_content['img_emotion_type'] = Yii::$app->request->post(
                'fourm_item_tag');
            $model->fourm_item_content = json_encode($fourm_item_content);
        } else
            if (!empty($fourm_item_idtype) && $fourm_item_idtype == 1) {
                $model->fourm_item_idtype = 1;
                $fourm_item_content['word_content_prompt'] = Yii::$app->request->post(
                    'word_content_prompt');
                $fourm_item_content['word_content_online'] = Yii::$app->request->post(
                    'word_content_online');
                $model->fourm_item_content = json_encode($fourm_item_content);
            } else
                if (!empty($fourm_item_idtype) && $fourm_item_idtype == 3) {
                    $model->fourm_item_idtype = 3;
                    $model->fourm_item_tag_type = Yii::$app->request->post(
                        'fourm_item_tag_type');
                    // $fourm_item_tag =
                    // Yii::$app->request->post('fourm_item_tag');
                    $fourm_item_content['tag_content_online'] = Yii::$app->request->post(
                        'tag_content_online');
                    $model->fourm_item_content = json_encode(
                        $fourm_item_content);
                }
        $model->fourm_item_time = time();
        // $transaction=\Yii::$app->db->beginTransaction();
        if (empty($id)) {
            // 新加
            // $check_title =
            // DwFourmCategoryItem::find()->where(['like','fourm_item_title',$fourm_item_title])->one();
            // if( !empty($check_title)){
            // $return = ['flg'=>false,'data'=>'【'.$fourm_item_title.'】名稱已经存在'];
            // return json_encode($return);
            // }

            $flg = $model->insert();
            if ($model->fourm_item_idtype == 3) {
                $fourm_item_tag = Yii::$app->request->post('fourm_item_tag');
                // 标签扩展，标签类型时，fourm_item_tag 传递的是标签选项值
                if (!empty($fourm_item_tag)) {
                    foreach ($fourm_item_tag as $f_i_t_k => $f_i_v) {
                        $item_ext_model = new DwFourmCategoryItemExt();
                        $item_ext_model->item_id = $model->id;
                        $item_ext_model->item_idtype = $model->fourm_item_idtype;
                        $item_ext_model->item_tag_type = $model->fourm_item_tag_type;
                        $tag_zi = explode('#', $f_i_v);
                        // [0] => 很差#1#1#49 [1] => 一般#2#2#54
                        $item_ext_model->item_tag_name = $tag_zi[0];
                        $item_ext_model->item_tag_score = $tag_zi[1];
                        $item_ext_model->item_tag_sort = $tag_zi[2];
                        $flg2 = $item_ext_model->insert(false);
                    }
                }
            }
        } else {
            // 编辑
            // $check_title = DwFourmCategoryItem::find()
            // ->where(['like','fourm_item_title',$fourm_item_title])
            // ->andWhere(['<>','id',$id])
            // ->one();
            // if( !empty($check_title)){
            // $return = ['flg'=>false,'data'=>'【'.$fourm_item_title.'】名稱已经存在'];
            // return json_encode($return);
            // }
            $model->id = $id;
            $model->fourm_item_stats = 0;
            $flg = $model->updateAll($model,
                [
                    'id' => $id
                ]);
            if ($model->fourm_item_idtype == 3) {
                // 标签扩展
                $del_ids = trim(Yii::$app->request->post('item_ext_delid'), ',');
                if (!empty($del_ids)) {
                    DwFourmCategoryItemExt::deleteAll(
                        'id in (' . $del_ids . ')');
                }
                $fourm_item_tag = Yii::$app->request->post('fourm_item_tag');
                if (!empty($fourm_item_tag)) {
                    foreach ($fourm_item_tag as $f_i_t_k => $f_i_v) {
                        $tag_zi = explode('#', $f_i_v);
                        if (empty($tag_zi[3])) {
                            // 说明是新加标签
                            $item_ext_model = new DwFourmCategoryItemExt();
                            $item_ext_model->item_id = $model->id;
                            $item_ext_model->item_idtype = $model->fourm_item_idtype;
                            $item_ext_model->item_tag_type = $model->fourm_item_tag_type;
                            $item_ext_model->item_tag_name = $tag_zi[0];
                            $item_ext_model->item_tag_score = $tag_zi[1];
                            $item_ext_model->item_tag_sort = $tag_zi[2];
                            $flg2 = $item_ext_model->insert(false);
                        } else {
                            // 编辑标签
                            $item_ext['item_idtype'] = $model->fourm_item_idtype;
                            $item_ext['item_tag_type'] = $model->fourm_item_tag_type;
                            $item_ext['item_tag_name'] = $tag_zi[0];
                            $item_ext['item_tag_score'] = $tag_zi[1];
                            $item_ext['item_tag_sort'] = $tag_zi[2];
                            // [0] => 很差#1#1#49 [1] => 一般#2#2#54
                            $flg2 = DwFourmCategoryItemExt::updateAll($item_ext,
                                [
                                    'id' => $tag_zi[3]
                                ]);
                        }
                    }
                }
            }
        }

        if ($flg) {
            $return = [
                'flg' => true,
                'data' => Yii::t('backend', 'Success Operation')
            ]; // 操作成功
            // $transaction->commit();
            $this->Fourm_item_redis($model->id);
        } else {
            $error = $model->getErrors();
            // $transaction->rollback();
            $return = [
                'flg' => false,
                'data' => $model
            ];
        }
        return json_encode($return);
    }

    /**
     * 表单设置添加
     *
     * @param
     *            integeR
     * @return mixed
     */
    public function actionFourm_category_item_view()
    {
        $id = Yii::$app->request->post('id');
        if (!empty($id)) {
            $data = DwFourmCategoryItem::get_fourm_item_redis_byid($id);
            // $data =
            // DwFourmCategoryItem::find()->where(['id'=>$id])->asArray()->one();
            if (!empty($data)) {
                // if( $data['fourm_item_idtype'] == 3){
                // $ext =
                // DwFourmCategoryItemExt::find()->where(['item_id'=>$data['id']])->asArray()->all();
                // if( !empty($ext)){
                // foreach ($ext as $key => $v ){
                // $ext_tag[] =$v['item_tag_name'];
                // }
                // $data['fourm_item_tag'] = json_encode($ext_tag);
                // }
                // }
                $return = [
                    'flg' => true,
                    'data' => $data
                ];
            } else {
                $return = [
                    'flg' => false,
                    'data' => Yii::t('backend', 'No The data')
                ]; // 无此数据
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
     * 表单设定删除
     *
     * @param
     *            integeR
     * @return mixed
     */
    public function actionFourm_category_item_del()
    {
        $id = Yii::$app->request->post('id');
        if (!empty($id)) {
            $save['fourm_item_stats'] = 1;
            $flg = DwFourmCategoryItem::updateAll($save,
                [
                    'id' => $id
                ]);
            // $flg = DwFourmCategoryItem::findOne($id)->delete();
            // DwFourmCategoryItemExt::deleteAll(['item_id'=>$id]);
            if ($flg) {
                $return = [
                    'flg' => true,
                    'data' => Yii::t('backend', 'Delete Success')
                ]; // 删除成功
                // $this->Fourm_item_redis($id,'del');
                $this->Fourm_item_redis($id);
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
     * 获取账户下的表单设定值
     *
     * @param integeR $id
     *            int 账户组id $check_val string 默认选中值
     * @return mixed
     */
    public function actionFourm_item_account($id, $check_val)
    {
        $item = '';

        $uid = Yii::$app->user->id;
        // 查询此账户 下的子账户
        $id = DwCommentSearch::getAccountByAccountId($uid, $id);
        // 查询此账户 下的子账户
        if (!empty($check_val)) {
            $check_val = explode(',', $check_val);
        }
        $item = DwFourmCategoryItem::find()->where(
            'fourm_item_account in (' . $id . ')')
            ->andFilterWhere(
                [
                    'fourm_item_stats' => 0
                ])
            ->asArray()
            ->all();
        return $this->renderPartial('fourm_item_account',
            [
                'item' => $item,
                'check_val' => $check_val
            ]);
    }

    /**
     * 获取账户下的表单类型值
     *
     * @param integeR $id
     *            int 账户组id
     * @return mixed
     */
    public function actionFourm_category_account($id)
    {
        $category = '';
        $uid = Yii::$app->user->id;
        // 查询此账户 下的子账户
        $zi_accountid = DwCommentSearch::getAccountByAccountId($uid, $id);
        $category = DwFourmCategory::find()->where(
            'fourm_account in (' . $id . ')')
            ->asArray()
            ->all();

        return $this->renderPartial('fourm_category_account',
            [
                'category' => $category
            ]);
    }

    /**
     * 表单类型添加
     *
     * @param
     *            integeR
     * @return mixed
     */
    public function actionFourm_category_add()
    {
        $fourm_title = Yii::$app->request->post('fourm_title');
        $fourm_idtype_id = trim(Yii::$app->request->post('fourm_idtype_id'),
            ',');
        $fourm_order = Yii::$app->request->post('fourm_order');
        $fourm_meth = Yii::$app->request->post('fourm_meth');
        $fourm_pess = Yii::$app->request->post('fourm_pess');
        $fourm_number = Yii::$app->request->post('fourm_number');
        $fourm_reply = Yii::$app->request->post('fourm_reply');
        $fourm_anonymous = Yii::$app->request->post('fourm_anonymous');
        $fourm_account = Yii::$app->request->post('fourm_account');
        $id = Yii::$app->request->post('id');
        $model = new DwFourmCategory();
        $model->fourm_title = $fourm_title;
        $model->fourm_idtype_id = $fourm_idtype_id;
        $model->fourm_account = $fourm_account;
        $model->fourm_order = $fourm_order;
        $model->fourm_meth = $fourm_meth;
        $model->fourm_pess = $fourm_pess;
        $model->fourm_number = $fourm_number;
        $model->fourm_reply = $fourm_reply;
        $model->fourm_anonymous = $fourm_anonymous;
        $model->fourm_dateline = time();
        $model->fourm_actions_uid = Yii::$app->user->identity->id;
        $ip = $_SERVER["REMOTE_ADDR"];
        $model->fourm_actions_ip = $ip;

        if (empty($id)) {
            $flg = $model->insert();
        } else {
            $model->id = $id;
            $flg = $model->updateAll($model,
                [
                    'id' => $id
                ]);
        }
        if ($flg) {
            $return = [
                'flg' => true,
                'data' => Yii::t('backend', 'Success Operation')
            ]; // 操作成功
            $this->Fourm_category_redis($model->id);
        } else {
            $error = $model->getErrors();
            $return = [
                'flg' => false,
                'data' => $error
            ];
        }
        return json_encode($return);
    }

    /**
     * 表单类型删除
     *
     * @param
     *            integeR
     * @return mixed
     */
    public function actionFourm_category_del()
    {
        $id = Yii::$app->request->post('id');
        if (!empty($id)) {
            $flg = DwFourmCategory::findOne($id)->delete();
            if ($flg) {
                $return = [
                    'flg' => true,
                    'data' => Yii::t('backend', 'Delete Success')
                ]; // 删除成功
                $this->Fourm_category_redis($id, 'del');
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
     * 表单类型查看
     *
     * @param
     *            integeR
     * @return mixed
     */
    public function actionFourm_category_view()
    {
        $id = Yii::$app->request->post('id');
        if (!empty($id)) {
            $data = DwFourmCategory::find()->where(
                [
                    'id' => $id
                ])
                ->asArray()
                ->one();
            if (!empty($data)) {
                $return = [
                    'flg' => true,
                    'data' => $data
                ];
            } else {
                $return = [
                    'flg' => false,
                    'data' => Yii::t('backend', 'No The data')
                ]; // 无此数据
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
     * 使用区域添加
     *
     * @param
     *            integeR
     * @return mixed
     */
    public function actionFourm_area_add()
    {
        $fourm_area = Yii::$app->request->post('fourm_area');
        $id = Yii::$app->request->post('id');
        $model = new DwFourmGenerateArea();
        $model->fourm_area = $fourm_area;
        $model->fourm_actions_uid = Yii::$app->user->identity->id;
        $model->fourm_dateline = time();
        if (empty($id)) {
            $flg = $model->insert();
        } else {
            $model->id = $id;
            $flg = $model->updateAll($model,
                [
                    'id' => $id
                ]);
        }
        if ($flg) {
            $return = [
                'flg' => true,
                'data' => Yii::t('backend', 'Success Operation')
            ]; // 操作成功
            $this->Fourm_area_redis(); // 设定缓存
        } else {
            $error = $model->getErrors();
            $return = [
                'flg' => false,
                'data' => $error
            ];
        }
        return json_encode($return);
    }

    /**
     * 使用区域删除
     *
     * @param
     *            integeR
     * @return mixed
     */
    public function actionFourm_area_del()
    {
        $id = Yii::$app->request->post('id');
        if (!empty($id)) {
            $flg = DwFourmGenerateArea::findOne($id)->delete();
            if ($flg) {
                $return = [
                    'flg' => true,
                    'data' => Yii::t('backend', 'Delete Success')
                ]; // 删除成功
                $this->Fourm_area_redis(); // 设定缓存
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
     * 使用区域添加
     *
     * @param
     *            integeR
     * @return mixed
     */
    public function actionFourm_area_view()
    {
        $id = Yii::$app->request->post('id');
        if (!empty($id)) {
            $data = DwFourmGenerateArea::find()->where(
                [
                    'id' => $id
                ])
                ->asArray()
                ->one();
            if (!empty($data)) {
                $return = [
                    'flg' => true,
                    'data' => $data
                ];
            } else {
                $return = [
                    'flg' => false,
                    'data' => Yii::t('backend', 'No The data')
                ]; // 无此数据
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
     * 生成管理添加
     *
     * @param
     *            integeR
     * @return mixed
     */
    public function actionFourm_making()
    {
        $fourm_generate_area = Yii::$app->request->post('fourm_generate_area');
        $fourm_category_id = Yii::$app->request->post('fourm_category_id');
        if (!empty($fourm_category_id)) {
            // $model = new DwFourmGenerate();
            // $model->fourm_generate_area = $fourm_generate_area;
            // $model->fourm_category_id = $fourm_category_id;
            // $flg = $model->insert();
            $js = <<<EOD
        <script src="http://demo.dwnews.com/js/api_common.js"></script>
        <script src="http://comment.login.dwnews.com/js/messenger.js"></script>
        <link rel="stylesheet" type="text/css" href="http://demo.dwnews.com/dist/css/wangEditor.min.css">
        <script type="text/javascript" src="http://demo.dwnews.com/dist/js/wangEditor.js"></script>
        <div id="commentArea"> </div>
        <div class="pl_box">
          <ul id="commentList">
          </ul>
        </div>
        <div style="text-align:center;padding-bottom:50px;display:none;" id='morepage'>
          <div id='pagemorediv'><a href="javascript:void(0)" id='pagemore'>加载更多</a></div>
          <div id='loadingimg' style="display:none;"><img src="images/loading.gif" ></div>
        </div>
        <script type="text/javascript">
        //由后端接口提供
        var access_token = '后端接口生成的access_token';
        //评论系统5.0调用
        JSsdk.init({
            CommentApiUrl: 'http://comment.api.dwnews.com/v1/frontend?access_token=' + access_token,
            CommentUrl: '9e59d215aba68abc16092266b87a272b',
            CommentTitle: '习近平主席亚洲之行的6个感人细节',
            CommentUserID: 0,
            CommentNumber: 10,
            CommentContainerClass: 'box_pl',
            CommentDivId: 'commentArea',
            CommentList: 'commentList',
            CommentChannelArea:$fourm_generate_area,
            CommentFormCategoryId: $fourm_category_id
        });</script>
EOD;
            $return = [
                'flg' => true,
                'data' => $js
            ]; // 操作成功
        } else {
            $return = [
                'flg' => false,
                'data' => Yii::t('backend', 'Parameter Error')
            ]; // 参数错误
        }

        return json_encode($return);
    }

    /**
     * 表单设定缓存
     *
     * @param
     *            integeR
     * @return mixed
     */
    public function Fourm_item_redis($id, $type = 'set')
    {
        // $redis =
        // DwFourmCategoryItem::find()->joinWith('fourmCategoryItemExt')->orderBy('fourm_item_idtype')->asArray()->all();
        // if( !empty($redis)){
        // foreach ( $redis as $s_key =>$s_v){
        // $a2[$s_v['id']]['id'] = $s_v['id'];
        // $a2[$s_v['id']]['fourm_item_title'] = $s_v['fourm_item_title'];
        // $a2[$s_v['id']]['fourm_item_tag_type'] = $s_v['fourm_item_tag_type'];
        // $a2[$s_v['id']]['fourm_item_idtype'] = $s_v['fourm_item_idtype'];
        // $a2[$s_v['id']]['fourm_item_content'] = $s_v['fourm_item_content'];
        // $a2[$s_v['id']]['fourm_item_is_ver'] = $s_v['fourm_item_is_ver'];
        // $a2[$s_v['id']]['fourmCategoryItemExt'] =
        // $s_v['fourmCategoryItemExt'];

        // }
        // $value = @json_encode($a2);
        // Yii::$app->redis->set('fourm_item',$value);
        // }
        // return $value;
        if (!empty($id)) {
            // $redis = Yii::$app->redis->get('fourm_item');
            // $redis = json_decode($redis,true);
            $redis = DwFourmCategoryItem::get_fourm_item_redis();
            if ($type == 'del') {
                unset($redis[$id]);
            } else {
                $redis_id = DwFourmCategoryItem::find()->where(
                    [
                        'id' => $id
                    ])
                    ->asArray()
                    ->one();
                if ($redis_id['fourm_item_idtype'] == 3) {
                    $ext = DwFourmCategoryItemExt::find()->where(
                        [
                            'item_id' => $redis_id['id']
                        ])
                        ->orderBy('item_tag_sort asc')
                        ->asArray()
                        ->all();
                    $redis_id['fourmCategoryItemExt'] = $ext;
                } else {
                    $redis_id['fourmCategoryItemExt'] = [];
                }
                $redis[$id] = $redis_id;
            }
            $value = @json_encode($redis);
            Yii::$app->redis->set('fourm_item', $value);
            return $value;
        } else {
            $redis = DwFourmCategoryItem::find()->joinWith(
                'fourmCategoryItemExt')
                ->orderBy('fourm_item_idtype')
                ->asArray()
                ->all();
            if (!empty($redis)) {
                foreach ($redis as $s_key => $s_v) {
                    $a2[$s_v['id']]['id'] = $s_v['id'];
                    $a2[$s_v['id']]['fourm_item_title'] = $s_v['fourm_item_title'];
                    $a2[$s_v['id']]['fourm_item_tag_type'] = $s_v['fourm_item_tag_type'];
                    $a2[$s_v['id']]['fourm_item_idtype'] = $s_v['fourm_item_idtype'];
                    $a2[$s_v['id']]['fourm_item_content'] = $s_v['fourm_item_content'];
                    $a2[$s_v['id']]['fourm_item_is_ver'] = $s_v['fourm_item_is_ver'];
                    $a2[$s_v['id']]['fourmCategoryItemExt'] = $s_v['fourmCategoryItemExt'];
                }
                $value = @json_encode($a2);
                Yii::$app->redis->set('fourm_item', $value);
            } else {
                $value = '';
                Yii::$app->redis->set('fourm_item', '');
            }
            return $value;
        }
    }

    /**
     * 获取表单设定缓存
     *
     * @param
     *            integeR
     * @return mixed
     */
    public function get_fourm_item_redis()
    {
        $redis = Yii::$app->redis->get('fourm_item');
        if (!$redis) {
            $redis = $this->Fourm_item_redis();
        }
        return json_decode($redis, true);
    }

    /**
     * 表单类型缓存
     *
     * @param
     *            integeR
     * @return mixed
     */
    public function Fourm_category_redis($id, $type = 'set')
    {
        if (!empty($id)) {
            // $redis = Yii::$app->redis->get('fourm_category');
            // $redis = json_decode($redis,true);
            $redis = DwFourmCategory::get_fourm_category_redis();
            if ($type == 'del') {
                unset($redis[$id]);
            } else {
                $redis_id = DwFourmCategory::find()->where(
                    [
                        'id' => $id
                    ])
                    ->asArray()
                    ->one();
                $redis[$id] = $redis_id;
            }
            $value = @json_encode($redis);
            Yii::$app->redis->set('fourm_category', $value);
            return $value;
        } else {
            $redis = DwFourmCategory::find()->asArray()->all();
            if (!empty($redis)) {
                foreach ($redis as $s_key => $s_v) {
                    $a2[$s_v['id']]['id'] = $s_v['id'];
                    $a2[$s_v['id']]['fourm_title'] = $s_v['fourm_title'];
                    $a2[$s_v['id']]['fourm_idtype_id'] = $s_v['fourm_idtype_id'];
                    $a2[$s_v['id']]['fourm_order'] = $s_v['fourm_order'];
                    $a2[$s_v['id']]['fourm_meth'] = $s_v['fourm_meth'];
                    $a2[$s_v['id']]['fourm_pess'] = $s_v['fourm_pess'];
                    $a2[$s_v['id']]['fourm_number'] = $s_v['fourm_number'];
                    $a2[$s_v['id']]['fourm_reply'] = $s_v['fourm_reply'];
                    $a2[$s_v['id']]['fourm_anonymous'] = $s_v['fourm_anonymous'];
                    $a2[$s_v['id']]['fourm_account'] = $s_v['fourm_account'];
                }
                $value = @json_encode($a2);
                $redis = Yii::$app->redis->set('fourm_category', $value);
            } else {
                $value = '';
                $redis = Yii::$app->redis->set('fourm_category', '');
            }
            return $value;
        }
    }

    /**
     * 获取表单类型缓存
     *
     * @param
     *            integeR
     * @return mixed
     */
    public function get_fourm_category_redis()
    {
        $redis = Yii::$app->redis->get('fourm_category');
        if (!$redis) {
            $redis = $this->Fourm_category_redis('');
        }
        return json_decode($redis, true);
    }

    /**
     * 删除有关评论的缓存
     *
     * @param
     *            integeR
     * @return mixed
     */
    public function del_relevant_redis($comment_id)
    {
        // 评论状态、赞、举报
        if (is_array($comment_id)) {
            foreach ($comment_id as $key => $v) {
                Yii::$app->redis->del('comment_stat_' . $v);
                Yii::$app->redis->del(md5('comment_support_' . $v));
                Yii::$app->redis->del(md5('comment_report_' . $v));
            }
        } else {
            Yii::$app->redis->del('comment_stat_' . $comment_id);
            Yii::$app->redis->del(md5('comment_support_' . $comment_id));
            Yii::$app->redis->del(md5('comment_report_' . $comment_id));
        }
    }

    /**
     * 谋篇文章评论数缓存
     *
     * @param
     *            integeR
     * @return mixed
     */
    public function update_comment_count_redis($comment_url, $action = '+',
                                               $count = 1)
    {
        $redis_count = DwComment::find()->where(
            [
                'comment_url' => $comment_url
            ])
            ->asArray()
            ->count();
        Yii::$app->redis->set($comment_url . '_count', $redis_count);
        return $redis_count;
    }

    /**
     * 谋条评论状态数据缓存
     *
     * @param
     *            integeR
     * @return mixed
     */
    public function update_comment_stat_redis($id)
    {
        // if( is_array($id)){
        // $ids = implode(',',$id);
        // $comment_data =
        // DwComment::find()->select('id,comment_status,comment_is_lock,comment_is_hide,comment_is_report')->where('id
        // in ('.$ids.')')->asArray()->all();
        // foreach ($comment_data as $comment_data_k=>&$comment_data_v){
        // $data =
        // DwCommentLog::find()->where(['comment_id'=>$comment_data_v['id']])->count();
        // $comment_data_v['operation_stat'] = $data;
        // $comment_data_redis = json_encode($comment_data_v);
        // Yii::$app->redis->set('comment_stat_'.$comment_data_v['id'],$comment_data_redis);
        // }
        // return true;
        // }else{
        // $comment_data =
        // DwComment::find()->select('id,comment_status,comment_is_lock,comment_is_hide,comment_is_report')->asArray()->one();
        // $data = DwCommentLog::find()->where(['comment_id'=>$id])->count();
        // $comment_data['operation_stat'] = $data;
        // $comment_data_redis = json_encode($comment_data);
        // Yii::$app->redis->set('comment_stat_'.$id,$comment_data_redis);
        // return true;
        // }
        DwComment::update_comment_redis($id);
    }

    /**
     * Finds the Comment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Comment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = Comment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 当前账户下可用表单设定
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Comment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionSearch($id, $target, $term = '')
    {
        Yii::$app->response->format = 'json';

        $avaliable = [];
        $assigned = [];
        // 完成的选择项
        $_data = DwFourmCategoryItem::find()->select('id,fourm_item_title')->where->asArray()->all();
        foreach ($_data as $value) {
            $assigned['Roles'][$value['id']] = $value['fourm_item_title'];
        }

        $data = DwFourmCategoryItem::find()->select('id,fourm_item_title')
            ->asArray()
            ->all();
        // 可用的选项
        if ($target == 'avaliable') {
            if (count($data)) {
                foreach ($data as $value) {
                    $avaliable['Roles'][$value['id']] = $value['fourm_item_title'];
                }
            }

            return $avaliable;
        } else {
            return $assigned;
        }
    }

    /**
     * 内部事件评论数据应用
     *
     * @return boolean
     */
    public function sensitive_check($content, $account_id = null)
    {
        $pid = DwAuthAccount::getAccountPidByAccountid($account_id);
        if ($pid == 0) {
            // 说明是主账户，只检测主账户的敏感词数据
            $check_txt = '0_' . $account_id . '_sensitive';
        } else {
            // 说明是子账户，检测主账户+子账户的敏感词数据
            $check_txt = '0_' . $pid . '_sensitive,' . $pid . '_' . $account_id .
                '_sensitive';
        }

        $content = urlencode(strip_tags($content));
        $url = Yii::$app->params['check_sensitive_url'];
        $post_data = 'type=' . $check_txt . '&content=' . $content;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if ($err) {
            echo '敏感词检测接口异常，请联系管理员';
            exit();
        } else {
            return json_decode($response, true);
        }
    }
}

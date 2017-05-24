<?php
namespace backend\controllers;
use Yii;
use common\models\Comment;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use backend\models\DwAuthAccount;
use backend\models\DwProps;
use backend\models\DwPropsCategory;
use backend\models\search\DwCommentSearch;
use GuzzleHttp\json_encode;
use Qiniu\json_decode;

/**
 * CommentController implements the CRUD actions for Comment model.
 */
class PropsController extends Controller {

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
     * 道具管理
     */
    public function actionIndex () {
        $model = new DwProps();
        // $uid = Yii::$app->user->id;
        // 获取当前登录用户有权限的pid
        // $all_account_arr = DwCommentSearch::getAccountPidByUid($uid);
        $all_account_arr = DwAuthAccount::getCurrentAccount(2);
        if (! empty($all_account_arr)) {
            // $all_account=implode(',', $all_account_arr['parent_account']);
            
            $query = DwProps::find()->Where(
                    'props_account_pid in (' . $all_account_arr . ')');
            $dataProvider = new ActiveDataProvider(
                    [
                            'query' => $query,
                            'sort' => [
                                    'defaultOrder' => [
                                            'id' => SORT_DESC
                                    ]
                            ]
                    ]);
        } else {
            return $this->render('index', 
                    [
                            'model' => $model,
                            'dataProvider' => ''
                    ]
                    // 'propscategory_redis'=>$propscategory_redis
                    );
        }
        // 道具分类
        // $propscategory_redis = DwPropsCategory::get_propscategory_redis();
        return $this->render('index', 
                [
                        'model' => $model,
                        'dataProvider' => $dataProvider
                ]
                // 'propscategory_redis'=>$propscategory_redis
                );
    }

    /**
     * 道具分类根据账户联动
     *
     * @return mixed $account_id 账户id
     *         $change_area 联动区域class
     *         $check_val 默认选中值
     */
    public function actionLevel_account ($account_id, $change_area, $check_val) {
        // 获取父id
        $account_pid = DwAuthAccount::getAccountPidByAccountid($account_id);
        // var_dump($account_pid);exit;
        if ($account_pid == 0) { // 自己是主账户
            $account = DwPropsCategory::get_category_redis($account_id);
        } elseif ($account_pid > 0) { // 子账户
            $account = DwPropsCategory::get_category_redis($account_pid);
        } else {
            return fasle;
        }
        
        return $this->renderPartial('level_account', 
                [
                        'account' => $account,
                        'change_area' => $change_area,
                        'check_val' => $check_val
                ]);
    }

    /**
     * 道具管理
     */
    public function actionAdd () {
        $model = new DwProps();
        $model->file = UploadedFile::getInstance($model, 'file');
        /**
         * 简体中文*
         */
        $readd = (string) Yii::t('backend', 'Readd');
        $operationerror = (string) Yii::t('backend', 'Operation Error');
        $parametererror = (string) Yii::t('backend', 'Parameter Error');
        $imageerror = (string) Yii::t('backend', 'Image Error');
        $pclassxists = (string) Yii::t('backend', 'Prop Name Exists');
        if ($model->file) {
            $filename = $model->file->name;
            $imgPath = Yii::$app->basePath . '/../upload/props/';
            $upload_name = $model->file->baseName . '.' . $model->file->extension;
            if (! is_dir($imgPath)) {
                mkdir($imgPath, 0777, true);
            }
            $flg = $model->file->saveAs($imgPath . $upload_name);
            if ($flg) {
                $DwProps = Yii::$app->request->Post('DwProps');
                $model2 = new DwProps();
                $model2->props_available = $DwProps['props_available'];
                $model2->props_category_id = $DwProps['props_category_id'];
                $pid = DwAuthAccount::getAccountPidByAccountid(
                        $DwProps['props_account_id']);
                
                if ($pid == 0) $pid = $DwProps['props_account_id'];
                $model2->props_name = $DwProps['props_name'];
                // echo
                // $DwProps['props_category_id'].'+'.$DwProps['props_name'].'+'.$pid.'+'.$DwProps['props_description'].'+'.$DwProps['props_available'];exit;
                if (! empty($DwProps['props_category_id']) &&
                         ! empty($DwProps['props_name']) && ! empty($pid) &&
                         ! empty($DwProps['props_description']) &&
                         ! empty($DwProps['props_credit'])) {
                    
                    $data = DwProps::find()->where(
                            [
                                    'props_name' => $DwProps['props_name'],
                                    'props_account_pid' => $pid
                            ])->one();
                    if (! empty($data)) {
                        echo "<script type='text/javascript'>var pclassxists='$pclassxists'; alert(pclassxists);location.href='/admin/index.php?r=props/index';</script>";
                        exit();
                    } else {
                        $model2->props_account_id = $DwProps['props_account_id'];
                        $model2->props_account_pid = $pid;
                        $model2->props_description = $DwProps['props_description'];
                        $model2->props_img = '/upload/props/' . $upload_name;
                        $model2->props_credit = $DwProps['props_credit'];
                        $flg2 = $model2->insert(false);
                        if ($flg2) {
                            $this->update_props_redis($pid);
                            $this->redirect([
                                    'index'
                            ]);
                        } else {
                            echo "<script type='text/javascript'>var readd='$readd'; alert(readd);location.href='/admin/index.php?r=props/index';</script>";
                            exit();
                        }
                    }
                } else {
                    echo "<script type='text/javascript'>var operationerror='$operationerror'; alert(operationerror);location.href='/admin/index.php?r=props/index';</script>";
                    exit();
                }
            } else {
                echo "<script type='text/javascript'>var parametererror='$parametererror'; alert(parametererror);location.href='/admin/index.php?r=props/index';</script>";
                exit();
            }
        } else {
            echo "<script type='text/javascript'>var imageerror='$imageerror'; alert(imageerror);location.href='/admin/index.php?r=props/index';</script>";
            exit();
        }
    }

    /**
     * 道具管理
     */
    public function actionEdit () {
        $DwProps = Yii::$app->request->Post('DwProps');
        $id = $DwProps['id'];
        /**
         * 简体中文*
         */
        $nogetinfor = (string) Yii::t('backend', 'No Get Infor');
        $pnamexists = (string) Yii::t('backend', 'Prop Name Exists');
        $failoperations = (string) Yii::t('backend', 'Fail Operations');
        $parametererror = (string) Yii::t('backend', 'Parameter Error');
        if (! empty($id) && ! empty($DwProps['props_name']) &&
                 ! empty($DwProps['props_description']) &&
                 ! empty($DwProps['props_credit'])) { // 编辑
            $model['props_available'] = $DwProps['props_available'];
            // $model['props_category_id'] = $DwProps['props_category_id'];
            $model['props_name'] = $DwProps['props_name'];
            $model['props_description'] = $DwProps['props_description'];
            $model['props_credit'] = $DwProps['props_credit'];
            $data = DwProps::find()->where([
                    'id' => $id
            ])->one(); // 查询要修改的信息
            
            if (empty($data)) {
                echo "<script type='text/javascript'>var nogetinfor='$nogetinfor'; alert(nogetinfor);location.href='/admin/index.php?r=props/index';</script>";
                exit();
            }
            
            // 查重
            $data2 = DwProps::find()->where(
                    [
                            'props_name' => $DwProps['props_name'],
                            'props_account_pid' => $data['props_account_pid']
                    ])
                ->andWhere([
                    '<>',
                    'id',
                    $id
            ])
                ->one();
            
            if (! empty($data2)) {
                echo "<script type='text/javascript'>var pnamexists='$pnamexists'; alert(pnamexists);location.href='/admin/index.php?r=props/index';</script>";
                exit();
            }
            $flg = DwProps::updateAll($model, [
                    'id' => $id
            ]);
            if ($flg) {
                $this->update_props_redis($data['props_account_pid']);
                $this->redirect([
                        'index'
                ]);
            } else {
                echo "<script type='text/javascript'>var failoperations='$failoperations'; alert(failoperations);location.href='/admin/index.php?r=props/index';</script>";
                exit();
            }
        } else {
            echo "<script type='text/javascript'>var parametererror='$parametererror'; alert(parametererror);location.href='/admin/index.php?r=props/index';</script>";
            exit();
        }
    }

    /**
     * 道具 查看
     */
    public function actionView () {
        $id = Yii::$app->request->Post('id');
        if (! empty($id)) {
            $data = DwProps::find()->where([
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
                        'data' => Yii::t('backend', 'No The data')
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
     * 道具 删除
     */
    public function actionProps_del () {
        $id = Yii::$app->request->Post('id');
        if (! empty($id)) {
            $data = DwPropsCategory::find()->where([
                    'id' => $id
            ])->one();
            if (DwProps::findOne($id)->delete()) {
                $return = [
                        'flg' => true,
                        'data' => Yii::t('backend', 'Success Operation')
                ];
                $this->update_props_redis($data['props_account_pid']);
            } else {
                $return = [
                        'flg' => false,
                        'data' => Yii::t('backend', 'Fail Peration')
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
     * 道具 缓存更新
     * pid 道具的父id
     */
    protected function update_props_redis ($pid) {
        // $redis = DwProps::find()->asArray()->all();
        
        // 此父账户下的敏感词缓存
        $redisdata = DwProps::find()->andFilterWhere(
                [
                        'props_account_pid' => $pid
                ])
            ->asArray()
            ->all();
        if (! empty($redisdata)) {
            foreach ($redisdata as $s_key2 => $s_v2) {
                $a3[$s_v2['id']] = $s_v2;
            }
            $redisdata = @json_encode($a3);
        } else {
            $redisdata = '';
        }
        $redis_name = $pid . 'props';
        $redis = Yii::$app->redis->set(md5($redis_name), $redisdata);
        return true;
    }

    /**
     * 道具分类缓存
     */
    protected function update_propscategory_redis ($pid) {
        $data = DwPropsCategory::find()->where([
                'props_account_pid' => $pid
        ])
            ->asArray()
            ->all();
        
        if (! empty($data)) {
            foreach ($data as $s_key => $s_v) {
                $a2[$s_v['id']] = $s_v;
            }
            $value = @json_encode($a2);
            $redis = Yii::$app->redis->set(md5('props_category_' . $pid), 
                    $value);
        } else {
            $value = '';
            $redis = Yii::$app->redis->set(md5('props_category_' . $pid), '');
        }
        
        return true;
    }

    /**
     * 道具分类管理
     */
    public function actionCategory () {
        $model = new DwPropsCategory();
        // $uid = Yii::$app->user->id;
        // 获取当前登录用户有权限的pid
        // $all_account_arr = DwCommentSearch::getAccountPidByUid($uid);
        $all_account_arr = DwAuthAccount::getCurrentAccount(2);
        
        if (! empty($all_account_arr)) {
            // $all_account=implode(',', $all_account_arr['parent_account']);
            // print_r($all_account_arr['parent_account']);
            $query = DwPropsCategory::find()->Where(
                    'props_account_pid in (' . $all_account_arr . ')');
            // 查询当前用户下，有权限的账户数据
            $dataProvider = new ActiveDataProvider(
                    [
                            'query' => $query,
                            'sort' => [
                                    'defaultOrder' => [
                                            'id' => SORT_DESC
                                    ]
                            ]
                    ]);
            return $this->render('category', 
                    [
                            'model' => $model,
                            'dataProvider' => $dataProvider
                    ]);
        } else {
            return $this->render('category', 
                    [
                            'model' => $model,
                            'dataProvider' => ''
                    ]);
        }
        // $account_where =DwCommentSearch::getAccountByUid($uid);
        // print_r($account_where);
    }

    /**
     * 道具分类管理添加
     */
    public function actionCategory_add () {
        $model = new DwPropsCategory();
        $DwPropsCategory = Yii::$app->request->Post('DwPropsCategory');
        $id = $DwPropsCategory['id'];
        /* 简体中文 */
        $datas = (string) Yii::t('backend', 'Name Exists');
        $fail = (string) Yii::t('backend', 'Fail Peration');
        $params = (string) Yii::t('backend', 'Parameter Error');
        if (empty($id)) { // 添加
            $model->props_category_name = $DwPropsCategory['props_category_name'];
            $model->props_account_id = $DwPropsCategory['props_account_id'];
            
            if (! empty($DwPropsCategory['props_category_name']) ||
                     ! empty($DwPropsCategory['props_account_id'])) {
                // 获取父id
                $account_pid = DwAuthAccount::getAccountPidByAccountid(
                        $DwPropsCategory['props_account_id']);
                
                if ($account_pid == 0) $account_pid = $DwPropsCategory['props_account_id'];
                $data = DwPropsCategory::find()->where(
                        [
                                'props_category_name' => $DwPropsCategory['props_category_name'],
                                'props_account_pid' => $account_pid
                        ])->one();
                if (! empty($data)) {
                    echo "<script type='text/javascript'>var datas='$datas'; alert(datas);location.href='/admin/index.php?r=props%2Fcategory';</script>";
                    exit();
                } else {
                    $model->props_account_pid = $account_pid;
                    $flg = $model->insert();
                    $this->update_propscategory_redis($data['pid']);
                    $this->redirect([
                            'category'
                    ]);
                }
            } else {
                echo "<script type='text/javascript'>var params='$params'; alert(params);location.href='/admin/index.php?r=props%2Fcategory';</script>";
                exit();
            }
        } else { // 编辑
            $save['props_category_name'] = $DwPropsCategory['props_category_name'];
            
            if (! empty($save['props_category_name']) && ! empty($id)) {
                $DwSensitiveLevel = DwPropsCategory::findOne($id);
                $data = DwPropsCategory::find()->where(
                        [
                                'props_category_name' => $DwPropsCategory['props_category_name'],
                                'props_account_pid' => $DwSensitiveLevel->props_account_pid
                        ])
                    ->andWhere([
                        '<>',
                        'id',
                        $id
                ])
                    ->one();
                if (! empty($data)) {
                    echo "<script type='text/javascript'>var params='$params'; alert(params);location.href='/admin/index.php?r=props%2Fcategory';</script>";
                    exit();
                }
                if ($DwSensitiveLevel->props_category_name ==
                         $DwPropsCategory['props_category_name']) {
                    $flg = true;
                } else {
                    $save['props_category_name'] = $DwPropsCategory['props_category_name'];
                    $flg = DwPropsCategory::updateAll($save, [
                            'id' => $id
                    ]);
                }
                
                if ($flg) {
                    // 跟新缓存
                    $this->update_propscategory_redis(
                            $DwSensitiveLevel->props_account_pid);
                    
                    $this->redirect([
                            'category'
                    ]);
                } else {
                    echo "<script type='text/javascript'>var fail='$fail'; alert(fail);location.href='/admin/index.php?r=props%2Fcategory';</script>";
                    exit();
                }
            } else {
                echo "<script type='text/javascript'>var fail='$fail'; alert(fail);location.href='/admin/index.php?r=props%2Fcategory';</script>";
                exit();
            }
        }
    }

    /**
     * 道具分类 查看
     */
    public function actionCategory_view () {
        $id = Yii::$app->request->Post('id');
        if (! empty($id)) {
            $data = DwPropsCategory::find()->where([
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
                        'data' => Yii::t('backend', 'No The data')
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
     * 道具分类 删除
     */
    public function actionCategory_del () {
        $id = Yii::$app->request->Post('id');
        if (! empty($id)) {
            $data = DwProps::find()->where([
                    'props_category_id' => $id
            ])
                ->asArray()
                ->one();
            if (! empty($data)) {
                $return = [
                        'flg' => false,
                        'data' => Yii::t('backend', 'Nodatas')
                ];
            } else {
                DwPropsCategory::findOne($id)->delete();
                $this->update_propscategory_redis($data['pid']);
                $return = [
                        'flg' => true,
                        'data' => Yii::t('backend', 'Success Operation')
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
}

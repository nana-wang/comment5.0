<?php
namespace backend\controllers;
use Yii;
use common\models\Comment;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\DwemoticonCategory;
use backend\models\search\DwemoticoncategorySearch;
use backend\models\DwEmoticon;
use backend\models\search\DwCommentSearch;
use backend\models\search\DwemoticonSearch;
use backend\models\DwAuthAccount;
use emoticon\plus\Emotionplus;
use yii\web\UploadedFile;
use backend\models\EntryForm;
use Qiniu\json_decode;

/**
 * CommentController implements the CRUD actions for Comment model.
 */
class EmoticonController extends Controller {

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
     * 表情包管理-列表
     */
    public function actionIndex () {
        $account = Yii::$app->request->get("account");
        $cate_id = Yii::$app->request->get("cate_id");
        $uid = Yii::$app->user->id;
        $query = DwEmoticon::find();
        $issearch = 0; // 是否定有检索条件
        if (! empty($account)) {
            $issearch = 1;
            $query = $query->andWhere([
                    'emoticon_account_id' => $account
            ]);
            $search_flg = true;
            // 表情类型
            $cate = DwemoticonCategory::get_category_redis_byaccountid($account);
        } else {
            // $account_where =DwCommentSearch::getAccountPidByUid($uid);
            $account_where = DwAuthAccount::getCurrentAccount(2);
            if (! empty($account_where)) {
                // $account_where = implode(',',$account_where['parent_account']
                // );
                // 此主账户下的子账户数据
                $query = $query->orWhere(
                        'emoticon_account_pid in (' . $account_where . ')');
                // 此主账户下的数据
                $query = $query->orWhere(
                        'emoticon_account_id in (' . $account_where . ')');
            } else {
                $query = $query->andWhere([
                        'id' => 0
                ]);
            }
            // 表情类型
            $cate = '';
        }
        if (! empty($cate_id)) {
            $issearch = 1;
            $query = $query->andWhere([
                    'emoticon_cate_id' => $cate_id
            ]);
        }
        $dataProvider = new ActiveDataProvider(
                [
                        'query' => $query,
                        'pagination' => [
                                'pagesize' => '20'
                        ],
                        'sort' => [
                                'defaultOrder' => [
                                        'id' => SORT_DESC
                                ]
                        ]
                ]);
        // $searchModel = new DwemoticonSearch();
        // $dataProvider =
        // $searchModel->search(Yii::$app->request->queryParams);
        $model = new DwEmoticon();
        return $this->render('manage', 
                [
                        'model' => $model,
                        'issearch' => $issearch,
                        'cate' => $cate,
                        // 'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'account' => $account,
                        'cate_id' => $cate_id
                ]);
    }

    /**
     * 查看表情
     */
    public function actionEmoticon_view () {
        $id = Yii::$app->request->post("id");
        if (! empty($id)) {
            $data = DwEmoticon::find()->with('catename')
                ->where([
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
                    'data' => 'Parameter error'
            ];
        }
        return json_encode($return);
    }

    /**
     * 表情编辑保存
     */
    public function actionEmoticon_edit () {
        $id = Yii::$app->request->post("id");
        $emoticon_name = Yii::$app->request->post("name");
        $emoticon_cateid = Yii::$app->request->post("cateid");
        
        if (! empty($id) && ! empty($emoticon_name) && ! empty($emoticon_cateid)) {
            $update_data = DwEmoticon::findOne($id);
            $data = DwEmoticon::find()->where(
                    [
                            'emoticon_name' => $emoticon_name,
                            'emoticon_cate_id' => $emoticon_cateid
                    ])
                ->andWhere([
                    '<>',
                    'id',
                    $id
            ])
                ->one();
            if (! empty($data)) {
                $msg = Yii::t('backend', 'Already Exist');
                $return = [
                        'flg' => false,
                        'data' => '【' . $emoticon_name . '】"' . $msg . '"'
                ];
            } else {
                $save['emoticon_name'] = $emoticon_name;
                // $save['emoticon_cate_id'] = $emoticon_cateid;
                $save['emoticon_update_time'] = time();
                $flg = DwEmoticon::updateAll($save, [
                        'id' => $id
                ]);
                if ($flg) {
                    $return = [
                            'flg' => true,
                            'data' => Yii::t('backend', 'Save Success')
                    ];
                    // 更新表情包缓存
                    DwEmoticon::update_emotion_redis(
                            $update_data->emoticon_account_id);
                    return $this->redirect([
                            'index'
                    ]);
                } else {
                    $return = [
                            'flg' => false,
                            'data' => Yii::t('backend', 'Save Fail')
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
     * 删除表情
     */
    public function actionEmoticon_del () {
        $id = Yii::$app->request->get("id");
        if (! empty($id)) {
            $save['emoticon_status'] = 0;
            $del_data = DwEmoticon::findOne($id);
            // $del_data->updateAll($save,['id'=>$id]);
            $del_data->delete();
            // 更新表情包缓存
            DwEmoticon::update_emotion_redis($del_data->emoticon_account_id);
        }
        return $this->redirect([
                'index'
        ]);
    }
    // 上传
    public function actionUpload () {
        if (Yii::$app->request->isPost) {
            $model = new DwEmoticon();
            $postData = Yii::$app->request->post('DwEmoticon');
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->file) {
                $filename = $model->file->name;
                // $zipPath = Yii::$app->basePath.'/static/emoticon/temp/';
                $zipPath = Yii::$app->basePath . '/../upload/' . date('Ymd') .
                         '/emoticon/temp/';
                if (! is_dir($zipPath)) {
                    mkdir($zipPath, 0777, true);
                }
                $model->file->saveAs(
                        $zipPath . $model->file->baseName . '.' .
                                 $model->file->extension);
                // 解压缩
                $this->get_zip_originalsize($filename, $zipPath);
                // 生成json文件
                $dirArr = $this->getDir($zipPath . $model->file->baseName . '/');
                $json = json_encode($dirArr);
                // 入库
                $this->insertData($json, $postData['emoticon_cate_id'], 
                        $postData['emoticon_account_id']);
                // 转移文件
                $this->moveFile($json, $model->file->baseName);
                $this->redirect(array(
                        '/emoticon/index'
                ));
            }
        } else {
            // 跳转控制器
            $this->redirect(array(
                    '/emoticon/index'
            ));
        }
    }
    // 表情包入库
    public function insertData ($json, $cate_id, $account_id) {
        if (empty($json) || empty($cate_id)) {return false;}
        $flag = false;
        $decodeJson = json_decode($json, true);
        if (! empty($decodeJson)) {
            foreach ($decodeJson as $k => $v) {
                $model = new DwEmoticon();
                $facename = explode('.', $v);
                $model->emoticon_cate_id = $cate_id;
                $model->emoticon_name = $facename[0];
                $model->emoticon_url = date('Ymd') . '/emoticon/' . $v;
                $model->emoticon_create_time = time();
                $model->emoticon_update_time = 0;
                $model->emoticon_cate_id = $cate_id;
                $model->emoticon_account_id = $account_id;
                $pid = DwAuthAccount::getAccountPidByAccountid($account_id);
                $model->emoticon_account_pid = $pid;
                $flag = $model->insert(false);
            }
            // 更新表情包缓存
            DwEmoticon::update_emotion_redis($account_id);
        }
        return $flag;
    }

    /**
     * 所属账户等级联动
     *
     * @return mixed $account_id 账户id
     *         $change_area 联动区域class
     *         $check_val 默认选中值
     */
    public function actionCategory_account ($account_id, $check_val) {
        $account = DwemoticonCategory::get_category_redis_byaccountid(
                $account_id);
        return $this->renderPartial('category_account', 
                [
                        'account' => $account,
                        'check_val' => $check_val
                ]);
    }

    /**
     * 表情包缓存更新
     * 
     * @return mixed
     * @return mixed
     */
    // protected function update_emotion_redis(){
    // $redis =
    // DwEmoticon::find()->select('id,emoticon_cate_id,emoticon_name,emoticon_url')->where(['emoticon_status'=>1])->asArray()->all();
    // if( !empty($redis)){
    // foreach ( $redis as $s_key =>$s_v){
    // $a2[$s_v['id']]['id'] = $s_v['id'];
    // $a2[$s_v['id']]['emoticon_cate_id'] = $s_v['emoticon_cate_id'];
    // $a2[$s_v['id']]['emoticon_name'] = $s_v['emoticon_name'];
    // $a2[$s_v['id']]['emoticon_url'] = $s_v['emoticon_url'];
    // }
    // $value = @json_encode($a2);
    // Yii::$app->redis->set('emoticon',$value);
    // }else{
    // $value = '';
    // Yii::$app->redis->set('emoticon','');
    // }
    // return $value;
    // }
    
    // 转移文件
    public function moveFile ($json = '', $dirName = '') {
        if (empty($json) || empty($dirName)) {return false;}
        // $zipPath = Yii::$app->basePath.'/static/emoticon/temp/'.$dirName;
        $zipPath = Yii::$app->basePath . '/../upload/' . date('Ymd') .
                 '/emoticon/temp/' . $dirName;
        $decodeJson = json_decode($json, true);
        if (! empty($decodeJson)) {
            foreach ($decodeJson as $k => $v) {
                $emoticonPath = $zipPath . '/../../';
                if (! is_dir($emoticonPath)) {
                    mkdir($emoticonPath, 0777, true);
                }
                copy($zipPath . '/' . $v, $emoticonPath . $v);
            }
            $this->deldir($zipPath);
        }
    }
    // 删除temp目录的图片，保留zip包
    public function deldir ($dir) {
        // 先删除目录下的文件：
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullpath = $dir . "/" . $file;
                if (! is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    $this->deldir($fullpath);
                }
            }
        }
        closedir($dh);
        // 删除当前文件夹：
        if (rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 分类列表
     */
    public function actionCategory () {
        
        // $searchModel = new DwemoticoncategorySearch();
        // $dataProvider =
        // $searchModel->search(Yii::$app->request->queryParams);
        $query = DwemoticonCategory::find();
        $uid = Yii::$app->user->id;
        // $account_where =DwCommentSearch::getAccountPidByUid($uid);
        $account_where = DwAuthAccount::getCurrentAccount(2);
        
        // 查询当前用户下，有权限的账户数据
        if (! empty($account_where)) {
            // $account_where = implode(',',$account_where['parent_account'] );
            // 此主账户下的子账户数据
            $query = $query->orWhere(
                    'emoticon_account_id in (' . $account_where . ')');
            // 此主账户下的数据
            $query = $query->orWhere(
                    'emoticon_account_pid in (' . $account_where . ')');
        } else {
            $query = $query->andWhere([
                    'id' => 0
            ]);
        }
        $dataProvider = new ActiveDataProvider(
                [
                        'query' => $query,
                        'pagination' => [
                                'pagesize' => '20'
                        ],
                        'sort' => [
                                'defaultOrder' => [
                                        'id' => SORT_DESC
                                ]
                        ]
                ]);
        return $this->render('setting', 
                [
                        'dataProvider' => $dataProvider
                ]);
    }

    /**
     * 表情分类-查看编辑
     *
     * @return mixed
     */
    public function actionCategory_view () {
        $id = Yii::$app->request->post("id");
        if (! empty($id)) {
            $data = DwemoticonCategory::find()->where([
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
                    'data' => 'Parameter error'
            ];
        }
        return json_encode($return);
    }

    /*
     * 添加分类
     */
    public function actionCategory_add () {
        $name = Yii::$app->request->post("name");
        $account = Yii::$app->request->post("account");
        $pid = DwAuthAccount::getAccountPidByAccountid($account);
        $save_pid = $pid;
        if (! empty($name)) {
            if ($pid == 0) {
                $pid = $account;
            }
            $data = DwemoticonCategory::find()->where(
                    [
                            'emoticon_category_name' => $name
                    ])
                ->andWhere(
                    [
                            'or',
                            [
                                    'emoticon_account_id' => $pid
                            ],
                            [
                                    'emoticon_account_pid' => $pid
                            ]
                    ])
                ->asArray()
                ->one();
            if (! empty($data)) {
                $msg = Yii::t('backend', 'Already Exist');
                $return = [
                        'flg' => false,
                        'data' => '【' . $name . '】' . $msg . ''
                ];
            } else {
                $model = new DwemoticonCategory();
                $model->emoticon_category_name = $name;
                $model->emoticon_account_id = $account;
                $model->emoticon_account_pid = $save_pid;
                $model->emoticon_category_create_time = time();
                $flg = $model->insert();
                if ($flg) {
                    $return = [
                            'flg' => true,
                            'data' => Yii::t('backend', 'Add Sucess')
                    ];
                    $this->update_emotioncategory_redis($account);
                    return $this->redirect([
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

    public function findModelemoticon ($id) {
        if (($model = DwEmoticon::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function findModelcategory ($id) {
        if (($model = DwemoticonCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 删除分类
     */
    public function actionCategory_del () {
        $id = Yii::$app->request->post("id");
        // if( !empty($id)){
        // $flg =$this->findModelcategory($id)->delete();
        // $save['emoticon_category_status'] = 0;
        // $flg =$this->findModelcategory($id)->updateAll($save,['id'=>$id]);
        // }
        if (! empty($id)) {
            $data = Dwemoticon::find()->where([
                    'emoticon_cate_id' => $id
            ])
                ->asArray()
                ->one();
            if (! empty($data)) {
                $return = [
                        'flg' => false,
                        'data' => Yii::t('backend', 'Emotion Data')
                ];
            } else {
                $del_data = DwemoticonCategory::findOne($id);
                $account_id = $del_data->emoticon_account_id;
                $del_data->delete();
                $this->update_emotioncategory_redis($account_id);
                return $this->redirect([
                        'category'
                ]);
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
     * 表情分类 - 编辑保存
     *
     * @return mixed
     */
    public function actionCategory_edit () {
        $id = Yii::$app->request->post("id");
        $name = Yii::$app->request->post("name");
        if (! empty($id) && ! empty($name)) {
            $ReportCategory = DwemoticonCategory::findOne($id);
            $account_id = $ReportCategory->emoticon_account_id;
            $pid = $ReportCategory->emoticon_account_pid;
            if ($pid == 0) {
                $pid = $account_id;
            }
            $data = DwemoticonCategory::find()->where(
                    [
                            'emoticon_category_name' => $name
                    ])
                ->andWhere(
                    [
                            'or',
                            [
                                    'emoticon_account_id' => $pid
                            ],
                            [
                                    'emoticon_account_pid' => $pid
                            ]
                    ])
                ->andWhere([
                    '<>',
                    'id',
                    $id
            ])
                ->asArray()
                ->one();
            if (! empty($data)) {
                $msg = Yii::t('backend', 'Emotion Classdate');
                $return = [
                        'flg' => false,
                        'data' => '【' . $name . '】"' . $msg . '"'
                ];
            } else {
                $return = [
                        'flg' => false,
                        'data' => 'Without this data'
                ];
                $save['emoticon_category_name'] = $name;
                $save['emoticon_category_update_time'] = time();
                $flg = DwemoticonCategory::updateAll($save, [
                        'id' => $id
                ]);
                if ($flg) {
                    $return = [
                            'flg' => true,
                            'data' => Yii::t('backend', 'Save Success')
                    ];
                    $this->update_emotioncategory_redis($account_id);
                    return $this->redirect([
                            'category'
                    ]);
                } else {
                    $return = [
                            'flg' => false,
                            'data' => Yii::t('backend', 'Save Fail')
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
     * 表情分类更新缓存
     *
     * @return mixed $account_id 所属账户id
     */
    protected function update_emotioncategory_redis ($account_id) {
        // $redis =
        // DwemoticonCategory::find()->select(['id','emoticon_category_name'])->where(['emoticon_category_status'=>1])->asArray()->all();
        // if( !empty($redis)){
        // foreach ( $redis as $s_key =>$s_v){
        // $a2[$s_v['id']] = $s_v['emoticon_category_name'];
        // }
        // $value = @json_encode($a2);
        // Yii::$app->redis->set('emoticon_category',$value);
        // }else {
        // $value = '';
        // Yii::$app->redis->set('emoticon_category','');
        // }
        // return $value;
        DwemoticonCategory::update_emotion_category_redis($account_id);
    }
    // /**
    // * 表情分类缓存获取
    // *
    // * @return mixed
    // */
    // protected function get_emotioncategory_redis(){
    // $redis = Yii::$app->redis->get('emoticon_category');
    // if( $redis ){
    // $value = json_decode($redis,true);
    // }else{
    // $redis = $this->update_emotioncategory_redis();
    // $value = json_decode($redis,true);
    // }
    // return $value;
    
    // }
    public function actionManage () {
        $do_action = Yii::$app->request->get("do");
        if ($do_action == 'add') {
            return $this->render('manage_add', 
                    [
                            'dataProvider' => $dataProvider
                    ]);
        } else {
            $dataProvider = new ActiveDataProvider(
                    [
                            'query' => Comment::find(),
                            'sort' => [
                                    'defaultOrder' => [
                                            'id' => SORT_DESC
                                    ]
                            ]
                    ]);
            return $this->render('manage', 
                    [
                            'dataProvider' => $dataProvider
                    ]);
        }
    }

    /**
     * Displays a single Comment model.
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
     * Finds the Comment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id            
     * @return Comment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel ($id) {
        if (($model = Comment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /*
     * 遍历文件夹 一层
     */
    public function getDir ($dir) {
        $files = array();
        $filesnames = scandir($dir);
        foreach ($filesnames as $name) {
            if ($name != '.' && $name != '..' && $name != ".DS_Store") {
                if (is_file($dir . $name)) {
                    $files[] = $name;
                }
            }
        }
        return $files;
    }

    /*
     * 解压缩包
     */
    public function get_zip_originalsize ($filename, $path) {
        header("Content-type:text/html;charset=utf-8");
        if (! file_exists($path . $filename)) {
            $msg = Yii::t('backend', 'No File');
            echo $msg;
            exit();
        }
        $filename = iconv("utf-8", "gb2312", $filename);
        $path = iconv("utf-8", "gb2312", $path);
        $resource = zip_open($path . $filename);
        $i = 1;
        while ($dir_resource = zip_read($resource)) {
            if (zip_entry_open($resource, $dir_resource)) {
                // 获取当前项目的名称,即压缩包里面当前对应的文件名
                $file_name = $path . zip_entry_name($dir_resource);
                // 以最后一个“/”分割,再用字符串截取出路径部分
                $file_path = substr($file_name, 0, strrpos($file_name, "/"));
                // 如果路径不存在，则创建一个目录，true表示可以创建多级目录
                if (! is_dir($file_path)) {
                    mkdir($file_path, 0777, true);
                }
                // 如果不是目录，则写入文件
                if (! is_dir($file_name)) {
                    // 读取这个文件
                    $file_size = zip_entry_filesize($dir_resource);
                    // 最大读取6M，如果文件过大，跳过解压，继续下一个
                    if ($file_size < (1024 * 1024 * 6)) {
                        $file_content = zip_entry_read($dir_resource, 
                                $file_size);
                        file_put_contents($file_name, $file_content);
                    } else {
                        $msg = Yii::t('backend', 'File Limit');
                        echo "<p> " . $i ++ . " " . $msg . ", -> " .
                                 iconv("gb2312", "utf-8", $file_name) . " </p>";
                    }
                }
                // 关闭当前
                zip_entry_close($dir_resource);
            }
        }
        // 关闭压缩包
        zip_close($resource);
    }

    public function actionComment () {
        $imgStr = '';
        $cateTag = '';
        $imgArr = array();
        $imgArrbyCate = array();
        
        $cateModel = new DwemoticonCategory();
        // $cate = $cateModel->getCate();
        $cate = $this->get_emotioncategory_redis();
        $emoticonList = DwEmoticon::find()->with('catename')
            ->asArray()
            ->all();
        // $emo= new \EMotionplus();
        foreach ($emoticonList as $k_e => $v_e) {
            // $img = $emo->getEmoticon($v_e, 2);
            $img = Emotionplus::getEmoticon($v_e, 2);
            $imgArrbyCate[$v_e['emoticon_cate_id']][] = $img[0];
            $imgArr[$v_e['id']] = $img[1]; // 用于评论列表
        }
        $i = 0;
        foreach ($imgArrbyCate as $k => $v) {
            if ($i == 0) {
                $classa = 'class="fli"';
                $classb = 'class="fdiv"';
                $i = 1;
            } else {
                $classa = $classb = '';
            }
            $cateTag .= '<li style="cursor:pointer"' . $classa . '>' . $cate[$k] .
                     '</li> ';
            // $cateTag.= '<a href="javascript:void(0)" '.$classa.'
            // onclick="selectCate('.$k.')">'.$cate[$k].'</a> | ';
            $imgStr .= '<div ' . $classb . 'name = "faceCate" id="faceCate_' . $k .
                     '">' . implode('', $v) . '</div>';
        }
        $imgStr = '<ul id="tab">' . $cateTag . '</ul><div id="tab_con">' .
                 $imgStr . "</div>";
        $model = new EntryForm();
        return $this->render('comment', 
                [
                        'model' => $model,
                        'emoticonList' => $imgArr,
                        'img' => $imgStr
                ]);
    }
}

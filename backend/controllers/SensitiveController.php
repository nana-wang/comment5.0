<?php
namespace backend\controllers;
use Yii;
use common\models\Comment;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\DwSensitiveLevel;
use backend\models\DwSensitive;
use backend\models\DwUser;
use backend\models\search\DwCommentSearch;
use backend\models\DwAuthAccount;
use GuzzleHttp\json_encode;
use yii\web\UploadedFile;
use Qiniu\json_decode;

/**
 * SensitiveController implements the CRUD actions for Comment model.
 * tonghui
 */
class SensitiveController extends Controller {

    /**
     * 生成敏感词txt文件*
     */
    protected $sensitive_txt_url = '/sensitive/sensitive.txt';

    /**
     * 生成敏感词词典链接*
     */
    protected $make_sensitive_url = 'http://focus.dwnews.com:9503/sensitive/sensitive.php';
  
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
        $dataProvider = new ActiveDataProvider(
                [
                        'query' => Comment::find(),
                        'sort' => [
                                'defaultOrder' => [
                                        'id' => SORT_DESC
                                ]
                        ]
                ]);
        
        return $this->render('index', 
                [
                        'dataProvider' => $dataProvider
                ]);
    }

    /**
     * Sensitive level setting
     *
     * @return Ambigous <string, string>
     */
    public function actionSetting () {
        $query = DwSensitiveLevel::find();
        $uid = Yii::$app->user->id;
        
        $account_where = DwAuthAccount::getCurrentAccount(2);
        // 查询当前用户下，有权限的账户数据
        if (! empty($account_where)) {
            // 此主账户下的子账户数据
            $query = $query->orWhere(
                    'sensitive_account_id_pid in (' . $account_where . ')');
            // 此主账户下的数据
            $query = $query->orWhere(
                    'sensitive_account_id in (' . $account_where . ')');
        } else {
            $query = $query->andWhere([
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
        return $this->render('setting', 
                [
                        'dataProvider' => $dataProvider
                ]);
    }

    public function actionManage () {
        $sensitive_name = Yii::$app->request->get("sensitive_name");
        $sensitive_level_id = Yii::$app->request->get("sensitive_level_id");
        $sensitive_action = Yii::$app->request->get("sensitive_action");
        $sensitive_operator = Yii::$app->request->get("sensitive_operator");
        $sensitive_account = Yii::$app->request->get("sensitive_account");
        $uid = Yii::$app->user->id;
        $query = DwSensitive::find();
        
        $search_flg = false;
        $level = [];
        if (! empty($sensitive_name)) {
            $query = $query->andFilterWhere(
                    [
                            'like',
                            'sensitive_name',
                            $sensitive_name
                    ]);
            $search_flg = true;
        }
        if (! empty($sensitive_level_id)) {
            $query = $query->andFilterWhere(
                    [
                            'sensitive_level_id' => $sensitive_level_id
                    ]);
            $search_flg = true;
        }
        if (! empty($sensitive_action)) {
            $query = $query->andFilterWhere(
                    [
                            'sensitive_action' => $sensitive_action
                    ]);
            $search_flg = true;
        }
        if (! empty($sensitive_operator)) {
            $account_id = DwUser::getUserIdByName($sensitive_operator);
            $query = $query->andFilterWhere([
                    'sensitive_operator' => $account_id
            ]);
            $search_flg = true;
        }
        
        if (! empty($sensitive_account)) {
            $search_flg = true;
            // $current_account =
            // DwAuthAccount::getCurrentAccountSensitive($sensitive_account);
            // $query = $query->andWhere('sensitive_account in
            // ('.$current_account.')');
            $query = $query->andWhere(
                    [
                            'sensitive_account' => $sensitive_account
                    ]);
            // 对应的账户下的等级
            $level = DwSensitiveLevel::get_senstive_level_redis(
                    $sensitive_account);
        } else {
            // 子账户有权限的id和有权限的子账户父类账户的id
            $current_account = DwAuthAccount::getCurrentAccountSensitive(
                    $sensitive_account);
            if (! empty($current_account)) {
                $query = $query->andWhere(
                        'sensitive_account in (' . $current_account . ')');
            } else {
                // 没数据
                $query = $query->andWhere([
                        'id' => 0
                ]);
            }
        }
        
        $dataProvider = new ActiveDataProvider(
                [
                        'query' => $query,
                        'pagination' => [
                                'pageSize' => 20
                        ],
                        'sort' => [
                                'defaultOrder' => [
                                        'id' => SORT_DESC
                                ]
                        ]
                ]);
        
        $model = new DwSensitive();
        return $this->render('manage', 
                [
                        'dataProvider' => $dataProvider,
                        'level' => $level,
                        'model' => $model,
                        'sensitive_name' => $sensitive_name,
                        'sensitive_level_id' => $sensitive_level_id,
                        'sensitive_account' => $sensitive_account,
                        'sensitive_action' => $sensitive_action,
                        'sensitive_operator' => $sensitive_operator,
                        'search_flg' => $search_flg
                ]);
    }

    /**
     * 敏感词添加
     *
     * @return mixed
     */
    public function actionAdd () {
        $type = Yii::$app->request->post("type");
        $uid = Yii::$app->user->id;
        
        $exist = Yii::t('backend', 'Already Exist'); // 已经存在
        $insertsuccess = Yii::t('backend', 'Insert Success');
        $insertfail = Yii::t('backend', 'Insert Fail');
        if ($type == 'han') {
            // 手动添加
            $sensitive_name = Yii::$app->request->post("sensitive_name");
            $sensitive_replace = Yii::$app->request->post("sensitive_replace");
            $sensitive_level_id = Yii::$app->request->post("sensitive_level_id");
            $sensitive_action = Yii::$app->request->post("sensitive_action");
            $sensitive_account = Yii::$app->request->post("sensitive_account");
            
            if (! empty($sensitive_name) && ! empty($sensitive_account) &&
                     ! empty($sensitive_level_id)) {
                $sensitive_name_array = explode(',', $sensitive_name);
                $str1 = '';
                $add_flg = true;
                $pid = DwAuthAccount::getAccountPidByAccountid(
                        $sensitive_account);
                foreach ($sensitive_name_array as $key => $v) {
                    $check_flg = $this->sensitive_check($sensitive_name, $pid, 
                            $sensitive_account);
                    if ($check_flg) {
                        $str1 .= '【' . $v . "】'" . $exist . "'\n";
                        $add_flg = false;
                    } else {
                        $model = new DwSensitive();
                        $model->sensitive_level_id = $sensitive_level_id;
                        $model->sensitive_action = $sensitive_action;
                        $model->sensitive_name = $v;
                        $model->sensitive_replace = $sensitive_replace;
                        $model->sensitive_operator = Yii::$app->user->identity->id;
                        $model->sensitive_time = time();
                        $model->sensitive_account = $sensitive_account;
                        $model->sensitive_account_pid = $pid;
                        $flg = $model->insert();
                        if ($flg) {
                            $str1 .= '【' . $v . "】'" . $insertsuccess . "'\n";
                        } else {
                            $str1 .= $v . "'" . $insertfail . "'\n";
                            $add_flg = false;
                        }
                    }
                }
                $return = [
                        'flg' => $add_flg,
                        'data' => $str1
                ];
                $this->updateSensitiveRedis($pid, $sensitive_account);
            } else {
                $return = [
                        'flg' => false,
                        'data' => Yii::t('backend', 'Parameter Error')
                ];
            }
            return json_encode($return);
        } elseif ($type == 'system') {
            
            // $data = DwSensitive::find()->asArray()->all();
            // $path = Yii::$app->request->post("path");
            // $path = $_SERVER['DOCUMENT_ROOT'] . $this->sensitive_txt_url;
            // 生成txt文件，1.获取当前登录用户的权限
            $account_id = DwAuthAccount::getAccountByUid($uid);
            
            $account_id_array = explode(',', $account_id);
            $make_txt = '';
            foreach ($account_id_array as $id_k => $id_v) {
                
                $pid = DwAuthAccount::getAccountPidByAccountid($id_v);
                // 读取账户下敏感词缓存
                $id_v_sensitive = DwSensitive::get_senstive_redis($pid, $id_v);
                if ($pid == 0) {
                    $id_v_sensitive = $id_v_sensitive['account_p'];
                } else {
                    $id_v_sensitive = $id_v_sensitive['account'];
                }
                if (! empty($id_v_sensitive)) {
                    $str = true;
                    $txt_name = $pid . '_' . $id_v . '_sensitive';
                    $path = $_SERVER['DOCUMENT_ROOT'] . '/sensitive/' . $txt_name .
                             '.txt';
                    $make_txt .= ',' . $txt_name; // 要生成的词典源文件
                    $f = fopen($path, 'wa');
                    foreach ($id_v_sensitive as $id_v_k => $id_v_v) {
                        if (fwrite($f, $id_v_v['sensitive_name'] . "\n") ===
                                 false) {
                            $str = Yii::t('backend', 'Write Fail'); // 文件写入失败
                        }
                    }
                }
            }
            // $make_account_array = [];
            // // 根据拥有的权限，获取对应的账户下的敏感词，如果是主账户，则使用主账户下的敏感词，如果是子账户，使用主账户+子账户的敏感词
            // foreach ($account_id_array as $id_k => $id_v){
            // $pid = DwAuthAccount::getAccountPidByAccountid($id_v);
            // if( $pid != 0 ){
            // // 说明当前切换账户是子账户,应该返回主账户id+子账户id
            // $make_account_array[$pid]['txt_name']='0_'.$pid.'_sensitive';
            // $make_account_array[$pid]['pid']='0';// 此账户的父账户信息
            // $make_account_array[$pid]['id']=$pid;// 此账户的父账户信息
            // $make_account_array[$id_v]['txt_name']=$pid.'_'.$id_v.'_sensitive';
            // $make_account_array[$id_v]['pid']=$pid;// 此账户信息
            // $make_account_array[$id_v]['id']=$id_v;// 此账户信息
            // }else{
            // // 说明当前切换账户是主账户，应该返回此主账户id
            // $make_account_array[$id_v]['txt_name']=$pid.'_'.$id_v.'_sensitive';
            // $make_account_array[$id_v]['pid']=$pid;// 此账户信息
            // $make_account_array[$id_v]['id']=$id_v;// 此账户信息
            // }
            // }
            
            // foreach ($make_account_array as $make_k => $make_v){
            // // 读取缓存
            // // $pid = DwAuthAccount::getAccountPidByAccountid($id_v);
            // // $id_v_val = DwSensitive::get_senstive_redis($pid, $id_v);
            // $pid = $make_v['pid'];
            // $zid = $make_v['id'];
            // $account_sensitive_val = DwSensitive::get_senstive_redis($pid,
            // $zid);
            // if( $pid == 0 ){
            // $make_account_sensitive =$account_sensitive_val['account_p'];
            // }else{
            // $make_account_sensitive =$account_sensitive_val['account'];
            // }
            // if( !empty($make_account_sensitive)){
            // $account_array_write=[];
            // $str = true;
            // // $txt_name = $pid.'_'.$id_v.'_sensitive';
            // $txt_name =$make_v['txt_name'];
            // $path = $_SERVER['DOCUMENT_ROOT'].'/sensitive/'.$txt_name.'.txt';
            // $make_txt .= ','.$txt_name;
            // $f = fopen($path,'wa');
            // foreach ($make_account_sensitive as $id_v_k => $id_v_v){
            // if(fwrite($f,$id_v_v['sensitive_name']."\n") === false) {
            // $str = Yii::t('backend','Write Fail'); //文件写入失败
            // }
            
            // }
            
            // }
            // }
            $make_txt = trim($make_txt, ',');
            $url = $this->make_sensitive_url . '?&url=' . $make_txt .
                     '&jsoncallback=?';
            if ($str) {
                $return = [
                        'flg' => true,
                        'data' => Yii::t('backend', 'Generate Success'),
                        'url' => $url
                ];
            } else {
                $return = [
                        'flg' => false,
                        'data' => $str
                ];
            }
        } else {
            // 批量上传
            $sensitive_level_id = Yii::$app->request->post("sensitive_level_id");
            $sensitive_action = Yii::$app->request->post("sensitive_action");
            $sensitive_account = Yii::$app->request->post("sensitive_account");
            $pid = DwAuthAccount::getAccountPidByAccountid($sensitive_account);
            $model = new DwSensitive();
            $file = UploadedFile::getInstance($model, 'file');
            $filename = $file->tempName;
            require (dirname(dirname(__FILE__)) . '/assets/PHPExcel.php');
            require (dirname(dirname(__FILE__)) .
                     '/assets/PHPExcel/IOFactory.php');
            require (dirname(dirname(__FILE__)) .
                     '/assets/PHPExcel/Reader/Excel5.php');
            
            $objPHPExcel = \PHPExcel_IOFactory::load($filename);
            // $objReader = \PHPExcel_IOFactory::createReader('Excel5');//use
            // excel2007 for 2007 format
            // $objPHPExcel = $objReader->load($filename);
            // //$filename可以是上传的文件，或者是指定的文件
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow(); // 取得总行数
            $highestColumn = $sheet->getHighestColumn(); // 取得总列数
            $time = time();
            $save_str = '';
            try {
                for ($j = 2; $j <= $highestRow; $j ++) {
                    $name = trim(
                            $objPHPExcel->getActiveSheet()
                                ->getCell("A" . $j)
                                ->getValue()); // 获取A列的值
                    $value = trim(
                            $objPHPExcel->getActiveSheet()
                                ->getCell("B" . $j)
                                ->getValue()); // 获取B列的值
                    $model = new DwSensitive();
                    $check_flg = $this->sensitive_check($name, $pid, 
                            $sensitive_account);
                    if (! $check_flg) {
                        $model->sensitive_level_id = $sensitive_level_id;
                        $model->sensitive_name = $name;
                        $model->sensitive_replace = $value;
                        $model->sensitive_action = $sensitive_action;
                        $model->sensitive_operator = Yii::$app->user->identity->id;
                        $model->sensitive_time = time();
                        $model->sensitive_account = $sensitive_account;
                        $model->sensitive_account_pid = $pid;
                        $model->insert();
                    }
                    // $flg =
                // $model::find()->where(['sensitive_name'=>$name])->one();
                    // if( !empty( $flg )){
                    // $flg->sensitive_name = $name;
                    // $flg->sensitive_replace = $value;
                    // $flg->sensitive_level_id = $sensitive_level_id;
                    // $flg->sensitive_action = $sensitive_action;
                    // $flg->sensitive_time = time();
                    // $flg->update();
                    // }else{
                    // $model->sensitive_level_id = $sensitive_level_id;
                    // $model->sensitive_name = $name;
                    // $model->sensitive_replace = $value;
                    // $model->sensitive_action = $sensitive_action;
                    // $model->sensitive_time = time();
                    // $model->insert();
                    // }
                }
                $this->updateSensitiveRedis($pid, $sensitive_account);
                return $this->redirect([
                        'manage'
                ]);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
        
        return json_encode($return);
    }

    /**
     * 敏感词视图编辑
     *
     * @param integer $id            
     * @return $sensitive_name 敏感词
     *         $pid 添加敏感词的主账户
     *         $sensitive_account 添加敏感词的账户
     *         $id 编辑敏感词的id
     *         int $id 添加，编辑
     */
    public function sensitive_check ($sensitive_name, $pid, $sensitive_account, 
            $id = '') {
        // $account_pid
        // =DwAuthAccount::getAccountPidByAccountid($sensitive_account) ;
        $account_pid = $pid;
        if ($account_pid == 0) {
            $account_pid = $sensitive_account;
        }
        $account_redis = DwSensitive::get_senstive_redis($account_pid, 
                $sensitive_account);
        if (! empty($id)) {
            // 编辑检查
            if (! empty($account_redis['account_p'])) {
                // 父类判断，是否有相同的敏感词
                
                if (isset($account_redis['account_p'][md5($sensitive_name)]) &&
                         $account_redis['account_p'][md5($sensitive_name)]['id'] !=
                         $id) {                    // 有此敏感词
                    return true;}
            }
            if (! empty($account_redis['account'])) {
                // 父类子账户，是否有相同的敏感词
                if (isset($account_redis['account'][md5($sensitive_name)]) &&
                         $account_redis['account'][md5($sensitive_name)]['id'] !=
                         $id) {                    // 有此敏感词
                    return true;}
            }
            return false;
        } else {
            // 添加检查
            if (! empty($account_redis['account_p'])) {
                // 父类判断，是否有相同的敏感词
                if (isset($account_redis['account_p'][md5($sensitive_name)])) {                    // 有此敏感词
                    return true;}
            }
            if (! empty($account_redis['account'])) {
                // 父类子账户，是否有相同的敏感词
                if (isset($account_redis['account'][md5($sensitive_name)])) {                    // 有此敏感词
                    return true;}
            }
            return false;
        }
        
        // if( !empty($id)){
        // $redisdata =
    // DwSensitive::find()->where(['sensitive_name'=>$sensitive_name])
        // ->andWhere(['sensitive_account'=>$account_pid])
        // ->andFilterWhere(['or',['sensitive_account'=>$account_pid,'sensitive_account_pid'=>0],['sensitive_account'=>$sensitive_account]])
        // ->andWhere(['<>', 'id', $id])->asArray()->one();
        // }else{
        // $redisdata =
    // DwSensitive::find()->where(['sensitive_name'=>$sensitive_name])
        // ->andFilterWhere(['or',['sensitive_account'=>$account_pid,'sensitive_account_pid'=>0],['sensitive_account'=>$sensitive_account]])
        // ->asArray()->one();
        // }
        // if( !empty($redisdata)){
        // return true;
        // }else {
        // return false;
        // }
    }

    /**
     * 敏感词视图编辑
     *
     * @param integer $id            
     * @return mixed
     */
    public function actionSensitive_view () {
        $id = Yii::$app->request->post("id");
        if (! empty($id)) {
            $data = DwSensitive::find()->where([
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
     * 敏感词编辑保存
     *
     * @param integer $id            
     * @return mixed
     */
    public function actionSensitive_edit () {
        $id = Yii::$app->request->post("id");
        $sensitive_name = Yii::$app->request->post("sensitive_name");
        $sensitive_replace = Yii::$app->request->post("sensitive_replace");
        $sensitive_level_id = Yii::$app->request->post("sensitive_level_id");
        $sensitive_action = Yii::$app->request->post("sensitive_action");
        $sensitive_account = Yii::$app->request->post("sensitive_account");
        if (! empty($id) && ! empty($sensitive_name)) {
            $pid = DwAuthAccount::getAccountPidByAccountid($sensitive_account);
            $check_flg = $this->sensitive_check($sensitive_name, $pid, 
                    $sensitive_account, $id);
            if ($check_flg) {
                $alreadyexist = Yii::t('backend', 'Already Exist');
                $return = [
                        'flg' => false,
                        'data' => '【' . $sensitive_name . '】"' . $alreadyexist .
                                 '"'
                ];
            } else {
                $save['sensitive_name'] = $sensitive_name;
                $save['sensitive_replace'] = $sensitive_replace;
                // $save['sensitive_level_id'] = $sensitive_level_id;
                // // 暂时隐藏有关账户的编辑
                // $save['sensitive_account'] = $sensitive_account;
                // $save['sensitive_account_pid'] = $pid;
                $save['sensitive_action'] = $sensitive_action;
                $save['sensitive_operator'] = Yii::$app->user->identity->id;
                $save['sensitive_time'] = time();
                $flg = DwSensitive::updateAll($save, [
                        'id' => $id
                ]);
                if ($flg) {
                    $return = [
                            'flg' => true,
                            'data' => Yii::t('backend', 'Success Operation')
                    ];
                    $this->updateSensitiveRedis($pid, $sensitive_account);
                } else {
                    $return = [
                            'flg' => false,
                            'data' => Yii::t('backend', 'Fail Peration')
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
     * 敏感词删除
     *
     * @return mixed
     */
    public function actionSensitive_del () {
        $id = Yii::$app->request->post("id");
        $data = DwSensitive::find()->where([
                'id' => $id
        ])
            ->asArray()
            ->one();
        if (! empty($id) && ! empty($data)) {
            $flg = $this->findModell($id)->delete();
            if ($flg) {
                $return = [
                        'flg' => true,
                        'data' => Yii::t('backend', 'Delete Success')
                ];
                $this->updateSensitiveRedis($data['sensitive_account_pid'], 
                        $data['sensitive_account']);
            } else {
                $return = [
                        'flg' => false,
                        'data' => Yii::t('backend', 'Delete Error')
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
    public function findModelllevel ($id) {
        if (($model = DwSensitiveLevel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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
        if (($model = DwSensitive::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 敏感词等级添加
     *
     * @return mixed
     */
    public function actionLevel_add () {
        $name = Yii::$app->request->post("name");
        $description = Yii::$app->request->post("description");
        $account_id = Yii::$app->request->post("account_id");
        $pid = DwAuthAccount::getAccountPidByAccountid($account_id);
        $save_pid = $pid;
        if (! empty($name)) {
            if ($pid == 0) {
                $pid = $account_id;
            }
            $data = DwSensitiveLevel::find()->where([
                    'sensitive_name' => $name
            ])
                ->andWhere(
                    [
                            'or',
                            [
                                    'sensitive_account_id' => $pid
                            ],
                            [
                                    'sensitive_account_id_pid' => $pid
                            ]
                    ])
                ->asArray()
                ->one();
            if (! empty($data)) {
                $alreadyexist = Yii::t('backend', 'Already Exist');
                $return = [
                        'flg' => false,
                        'data' => '【' . $name . '】"' . $alreadyexist . '"'
                ];
            } else {
                $model = new DwSensitiveLevel();
                $model->sensitive_name = $name;
                $model->sensitive_description = $description;
                $model->sensitive_time = time();
                $model->sensitive_account_id = $account_id;
                $model->sensitive_account_id_pid = $save_pid;
                
                $flg = $model->insert();
                if ($flg) {
                    $return = [
                            'flg' => true,
                            'data' => Yii::t('backend', 'Success Operation')
                    ];
                    // 跟新缓存
                    $this->updateSensitiveLevelRedis($account_id);
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
     * 敏感词等级查看编辑
     *
     * @return mixed
     */
    public function actionLevel_view () {
        $id = Yii::$app->request->post("id");
        if (! empty($id)) {
            $data = DwSensitiveLevel::find()->where([
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
     * 敏感词等级编辑保存
     *
     * @return mixed
     */
    public function actionLevel_edit () {
        $id = Yii::$app->request->post("id");
        $name = Yii::$app->request->post("name");
        $description = Yii::$app->request->post("description");
        
        $sexists = Yii::t('backend', 'Sensitive Exists');
        if (! empty($id) && ! empty($name) && ! empty($description)) {
            $DwSensitiveLevel = DwSensitiveLevel::findOne($id);
            $account_id = $DwSensitiveLevel->sensitive_account_id;
            $pid = $DwSensitiveLevel->sensitive_account_id_pid;
            if ($pid == 0) {
                $pid = $account_id;
            }
            $data = DwSensitiveLevel::find()->where([
                    'sensitive_name' => $name
            ])
                ->andWhere(
                    [
                            'or',
                            [
                                    'sensitive_account_id' => $pid
                            ],
                            [
                                    'sensitive_account_id_pid' => $pid
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
                Yii::t('backend', 'Save Fail');
                $return = [
                        'flg' => false,
                        'data' => '【' . $name . '】"' . $sexistss . '"'
                ];
            } else {
                $save['sensitive_name'] = $name;
                $save['sensitive_description'] = $description;
                $save['sensitive_time'] = time();
                $flg = DwSensitiveLevel::updateAll($save, [
                        'id' => $id
                ]);
                if ($flg) {
                    $return = [
                            'flg' => true,
                            'data' => Yii::t('backend', 'Success Operation')
                    ];
                    // 跟新缓存
                    $this->updateSensitiveLevelRedis(
                            $DwSensitiveLevel->sensitive_account_id);
                } else {
                    $return = [
                            'flg' => false,
                            'data' => Yii::t('backend', 'Fail Peration')
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
     * 敏感词等级删除
     *
     * @return mixed
     */
    public function actionLevel_del () {
        $id = Yii::$app->request->post("id");
        if (! empty($id)) {
            $data = DwSensitive::find()->where([
                    'sensitive_level_id' => $id
            ])
                ->asArray()
                ->one();
            if (! empty($data)) {
                $return = [
                        'flg' => false,
                        'data' => Yii::t('backend', 'Sensitive Data')
                ]; // 此等级下面设有敏感词，请勿删除
            } else {
                $DwSensitiveLevel = DwSensitiveLevel::findOne($id);
                $account_id = $DwSensitiveLevel->sensitive_account_id;
                $flg = $DwSensitiveLevel->delete();
                // 跟新缓存
                $this->updateSensitiveLevelRedis($account_id);
                $return = [
                        'flg' => true,
                        'data' => Yii::t('backend', 'Success Operation')
                ]; // 操作成功
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
     * 所属账户等级联动
     *
     * @return mixed $account_id 账户id
     *         $change_area 联动区域class
     *         $check_val 默认选中值
     */
    public function actionLevel_account ($account_id, $check_val) {
        $pid = DwAuthAccount::getAccountPidByAccountid($account_id);
        if ($pid == 0) {
            $pid = $account_id;
        }
        $account = DwSensitiveLevel::get_senstive_level_redis($pid);
        return $this->renderPartial('level_account', 
                [
                        'account' => $account,
                        'check_val' => $check_val
                ]);
    }

    /**
     * 敏感词等级缓存
     *
     * @return mixed
     */
    public function updateSensitiveLevelRedis ($account_id) {
        $pid = DwAuthAccount::getAccountPidByAccountid($account_id);
        if ($pid == 0) {
            $pid = $account_id;
        }
        $data = DwSensitiveLevel::find()->andWhere(
                [
                        'or',
                        [
                                'sensitive_account_id' => $pid
                        ],
                        [
                                'sensitive_account_id_pid' => $pid
                        ]
                ])
            ->asArray()
            ->all();
        foreach ($data as $s_key => $s_v) {
            $a2[$s_v['id']] = $s_v;
        }
        $value = @json_encode($a2);
        $redis = Yii::$app->redis->set(md5('senstive_level_' . $pid), $value);
        return true;
    }

    /**
     * 敏感词缓存
     *
     * @return mixed $account_pid 所属账户父id
     *         $account_id 所属账户id
     */
    public function updateSensitiveRedis ($pid, $account_id) {
        // $data = DwSensitive::find()->asArray()->all();
        // foreach ( $data as $s_key =>$s_v){
        // $a2[$s_v['sensitive_name']] = $s_v;
        // }
        // $value = @json_encode($a2);
        // $redis = Yii::$app->redis->set('senstive',$value);
        // return true;
        if ($pid == 0) {
            $pid = $account_id;
            // 说明此次更新是主账户，只更新主账户的缓存
            $redisdata_p = DwSensitive::find()->andFilterWhere(
                    [
                            'sensitive_account' => $pid
                    ])
                ->asArray()
                ->all();
            if (! empty($redisdata_p)) {
                foreach ($redisdata_p as $s_key => $s_v) {
                    $a2[md5($s_v['sensitive_name'])] = $s_v;
                }
                $redisdata_p = @json_encode($a2);
            } else {
                $redisdata_p = '';
            }
            $redis = Yii::$app->redis->set(md5('0_' . $pid . '_senstive'), 
                    $redisdata_p);
        } else {
            // 说明此次更新是子账户，只更新子账户的缓存
            if ($account_id != $pid) {
                // 此账户下的敏感词缓存
                $redisdata = DwSensitive::find()->andFilterWhere(
                        [
                                'sensitive_account' => $account_id,
                                'sensitive_account_pid' => $pid
                        ])
                    ->asArray()
                    ->all();
                if (! empty($redisdata)) {
                    foreach ($redisdata as $s_key2 => $s_v2) {
                        $a3[md5($s_v2['sensitive_name'])] = $s_v2;
                    }
                    $redisdata = @json_encode($a3);
                } else {
                    $redisdata = '';
                }
                $redis_name = $pid . '_' . $account_id . '_senstive';
                $redis = Yii::$app->redis->set(md5($redis_name), $redisdata);
            }
        }
        return true;
    }
}

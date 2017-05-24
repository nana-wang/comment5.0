<?php
namespace backend\controllers;
use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use backend\models\DwParameter;
use GuzzleHttp\json_encode;
use Qiniu\json_decode;

/**
 * CommentController implements the CRUD actions for Comment model.
 */
class AccountsetingController extends Controller {

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
     * 参数设置
     */
    public function actionIndex () {
        $model = new DwParameter();
        $dataProvider = new ActiveDataProvider(
                [
                        'query' => DwParameter::find(),
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
                        'data' => '无此数据'
                ];
            }
        } else {
            $return = [
                    'flg' => false,
                    'data' => '参数错误'
            ];
        }
        return json_encode($return);
    }

    /**
     * 道具 缓存更新
     */
    protected function update_parameter_redis () {
        $redis = DwProps::find()->asArray()->all();
        if (! empty($redis)) {
            foreach ($redis as $s_key => $s_v) {}
            $value = @json_encode($a2);
            Yii::$app->redis->set('props', $value);
        } else {
            $value = '';
            Yii::$app->redis->set('props', '');
        }
        return $value;
    }

    /**
     * 道具缓存 获取
     */
    protected function get_parameter_redis () {
        $redis = Yii::$app->redis->get('props');
        if ($redis) {
            $value = json_decode($redis, true);
        } else {
            $redis = $this->update_props_redis();
            $value = json_decode($redis, true);
        }
        return $value;
    }
}

<?php
/**
 * author: Frank
 * Date: 2016/07/27
 * Time: 17:59.
 */
namespace backend\controllers;
use Yii;
use common\models\Config;
use yii\base\Model;
use yii\caching\TagDependency;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use backend\models\DwNotifyCategory;
use GuzzleHttp\json_encode;

class SystemController extends Controller {

    public function actionConfig ($group = 'site') {
        // Yii::$app->config->get('CACHE-MEMCACHE');exit;
        $groups = Yii::$app->config->get('CONFIG_GROUP');
        $dataProvider = new ActiveDataProvider(
                [
                        'query' => Config::find()->where(
                                [
                                        'group' => $group
                                ]),
                        'pagination' => false
                ]);
        return $this->render('config', 
                [
                        'groups' => $groups,
                        'group' => $group,
                        'dataProvider' => $dataProvider
                ]);
    }

    /**
     * format data
     *
     * @param string $group            
     * @return \yii\web\Response
     */
    public function actionStoreConfig ($group = 'site') {
        $dataProvider = new ActiveDataProvider(
                [
                        'query' => Config::find()->where(
                                [
                                        'group' => $group
                                ]),
                        'pagination' => false
                ]);
        $configs = $dataProvider->getModels();
        if (Model::loadMultiple($configs, \Yii::$app->request->post()) &&
                 Model::validateMultiple($configs)) {
            foreach ($configs as $config) {
                /* @var $config Config */
                $config->save(false);
            }
            // 消息模板
            $redis = '';
            $notify_data = DwNotifyCategory::find()->asArray()->all();
            if (! empty($notify_data)) {
                foreach ($notify_data as $key => $v) {
                    $redis[$v['name']] = $v;
                }
            }
            Yii::$app->redis->set(md5('notify_category'), json_encode($redis));
            TagDependency::invalidate(\Yii::$app->cache, 'systemConfig');
            return $this->redirect('config');
        }
    }
}

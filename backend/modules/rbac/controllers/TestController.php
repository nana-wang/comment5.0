<?php
namespace mdm\admin\controllers;

/**
 * DefaultController.
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 *        
 * @since 1.0
 */
class TestController extends \yii\web\Controller {

    /**
     * Action index.
     */
    public function actionIndex () {
        $cache = \Yii::$app->cache;
        $cache['aa'] = '123';
        echo $cache['aa'];
    }
}

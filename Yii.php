<?php
/****
 * @DwNews
 * @2016-07-08
 */
if (is_file(__DIR__ . '/.env')) {
    (new \Dotenv\Dotenv(__DIR__))->load();
}
defined('YII_DEBUG') or define('YII_DEBUG', env('YII_DEBUG'));
defined('YII_ENV') or define('YII_ENV', env('YII_ENV', 'prod'));

class Yii extends \yii\BaseYii {

    public static function getVersion () {
        return '0.1.0';
    }

    public static function powered () {
        return 'Copyright © 2016 dwnews. All rights reserved. ';
    }
}
spl_autoload_register([
        'Yii',
        'autoload'
], true, true);
Yii::$classMap = require (__DIR__ . '/vendor/yiisoft/yii2/classes.php');
Yii::$classMap['mpdf60/mPDF'] = require (__DIR__ . '/vendor/mpdf60/mpdf.php');
Yii::$container = new yii\di\Container();
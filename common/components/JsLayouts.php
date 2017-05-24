<?php
/**
 * @author Frank
 * 后台布局js 调用widgets
 */
namespace common\components;

use common\models\Module;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\caching\DbDependency;
use yii\base\Widget;

class JsLayouts extends Widget
{

    public $mes;

    public function init()
    {
        parent::init();
        //ob_start();
        $this->mes = '111111';
    }

    public function run()
    {
        //$content = ob_get_clean();
        return $this->render('@app/views/emoticon/view');
    }
}
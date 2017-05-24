<?php
/**
 * @author Frank
 * 2016-08-01
 */

namespace common\components;


use yii\base\Component;

class Storage extends Component
{
    public $baseUrl;

    public $basePath;

    public function init()
    {
        parent::init();
        $this->baseUrl = \Yii::getAlias($this->baseUrl);
        $this->basePath = \Yii::getAlias($this->basePath);
    }

    public function path2url($path)
    {
        return $this->baseUrl . DIRECTORY_SEPARATOR . pathinfo($path, PATHINFO_BASENAME);
    }
}
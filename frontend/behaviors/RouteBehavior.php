<?php
namespace frontend\behaviors;
use yii\base\Behavior;
use yii\caching\DbDependency;
use yii\web\Application;
use Yii;

class RouteBehavior extends Behavior {

    public function events () {
        return [
                Application::EVENT_BEFORE_REQUEST => 'beforeRequest'
        ];
    }

    public function beforeRequest ($event) {
    }
}
<?php
/**
 * @author Frank
 * 2016-08-01
 */

namespace common\components;


use yii\base\Object;

class Queue extends Object
{
    /**
     * @param string $queue 队列分类名
     * @param string $className 处理队列的类名
     * @param array $args 参数关联数组
     */
    public function push($queue, $className, $args)
    {
        \Resque::enqueue($queue, $className, $args);
    }

    public function pop()
    {

    }
}
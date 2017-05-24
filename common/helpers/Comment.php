<?php

namespace common\helpers;


use common\models\User;

class Comment
{
    public static function process($data)
    {
        preg_match('/@(\S+?)\s/', $data, $matches);
        if (!empty($matches)) {
            $replyUserName = $matches[1];
            $replyUserId = User::find()->select('id')->where(['username' => $replyUserName])->scalar();
            $data = preg_replace('/(@\S+?\s)/', Html::a('$1', ['/user', 'id' => $replyUserId]), $data);
        }
        return $data;
    }
}
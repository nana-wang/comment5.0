<?php

namespace frontend\models;


use common\models\Reward;
use yii\base\Exception;
use yii\base\Model;
use yii\validators\InlineValidator;

class RewardForm extends Model
{
    public $article_id;
    public $money;
    public $comment;

    public function rules()
    {
        $rules = [
            [['article_id', 'money'], 'required'],
            ['comment', 'string', 'max' => 255],
            ['money', 'compare', 'compareValue' => 0, 'operator' => '>', 'message' => '打赏额必须大于0'],
        ];
        if (!\Yii::$app->user->isGuest) {
            $rules[] = ['money', 'compare', 'compareValue' => \Yii::$app->user->identity->profile->money, 'operator' => '<', 'message' => '打赏额不能大于自身账户余额'];
        }
        return $rules;
    }

    public function attributeLabels()
    {
        return [
            'money' => '金额',
            'comment' => '留言'
        ];
    }

    public function attributeHints()
    {
        return [
            'money' => '(帐号余额:' . \Yii::$app->user->isGuest ? 0 : \Yii::$app->user->identity->profile->money . ')'
        ];
    }

    public function reward()
    {
        if ($this->validate()) {
            $transaction = \Yii::$app->db->beginTransaction();
            try{
                // 打赏者扣钱
                /* @var $profile \common\models\Profile */
                $profile = \Yii::$app->user->identity->profile;
                $result = $profile::getDb()->createCommand('update {{%profile}} set money=money-' . $this->money . ' WHERE id = ' . $profile->id . ' AND money>=' . $this->money)->execute();
                if ($result == 0) {
                    throw new Exception('打赏失败');
                }
                // 作者加钱
                $article = Article::find()->where(['id' => $this->article_id])->one();
                $article->user->profile->updateCounters(['money' => $this->money]);
                $reward = new Reward();
                $reward->article_id = $this->article_id;
                $reward->money = $this->money;
                if($reward->save() === false) {
                    throw new Exception('打赏失败');
                }
                $transaction->commit();
                return true;
            } catch(\Exception $e) {
                $transaction->rollback();
                return false;
            }
        }
    }
}
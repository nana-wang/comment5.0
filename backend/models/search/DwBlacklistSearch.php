<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Blacklist;
use backend\models\search\DwCommentSearch;
use backend\models\DwAuthAccount;

/**
 * DwCommentSearch represents the model behind the search form about `frontend\models\DwComment`.
 */
class DwBlacklistSearch extends Blacklist
{   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['blacklist_uid', 'blacklist_action_uid','blacklist_create','blacklist_level','blacklist_account_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {

        // $uid =   Yii::$app->user->id;
        // 获取当前登录用户有权限的pid
        // $all_account_arr = DwCommentSearch::getAccountPidByUid($uid);    
        $all_account_arr=DwAuthAccount::getCurrentAccount(2);

        // $all_account=implode(',', $all_account_arr['parent_account']);
        //$query = DwComment::find();
        $query = Blacklist::find()->Where('blacklist_account_pid in ('.$all_account_arr.')');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        	'sort' => [
        		 'defaultOrder' => [
        		  'id' => SORT_DESC
        		  ]
        	]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'blacklist_uid' => $this->blacklist_uid,
            'blacklist_level' => $this->blacklist_level,
            'blacklist_account_id' => $this->blacklist_account_id,
            'blacklist_action_uid' => $this->blacklist_action_uid,
        ]);
        
        if( !empty($this->blacklist_create)){
        	$sear_time = strtotime($this->blacklist_create);
        	$query->andFilterWhere(['>', 'blacklist_create', $sear_time]);
        	$query->andFilterWhere(['<', 'blacklist_create', $sear_time+86400]);
        }
        
        return $dataProvider;
    }
    
  
}

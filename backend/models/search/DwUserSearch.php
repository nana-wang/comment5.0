<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\User;


/**
 * DwCommentSearch represents the model behind the search form about `frontend\models\DwComment`.
 */
class DwUserSearch extends User
{   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'safe'],
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
        $query = User::find();
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
            return $dataProvider;
        }

       // $query->andFilterWhere(['like','username'=>$this->username]);
        //$query->andFilterWhere(['like','email',$this->email]);
          $query->andFilterWhere(['and',['like','username',$this->username],['like','email',$this->email]]);
           
        return $dataProvider;
    }
    
  
}

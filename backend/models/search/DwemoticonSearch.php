<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DwEmoticon;

/**
 * FaceSearch represents the model behind the search form about `frontend\models\Face`.
 */
class DwemoticonSearch extends DwEmoticon
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'emoticon_cate_id', 'emoticon_create_time', 'emoticon_update_time'], 'integer'],
            [['emoticon_name', 'emoticon_url'], 'safe'],
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
        $query = DwEmoticon::find()->joinWith('catename')->select("dw_emoticon.*,dw_emoticon_category.emoticon_category_name")->where(['emoticon_status'=>1]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        	'pagination' => [
        		'pageSize' => 20,
        	],
        	'sort' => [
        		'defaultOrder' => [
        			'id' => SORT_DESC
        		]
        	]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'emoticon_cate_id' => $this->emoticon_cate_id,
            'emoticon_create_time' => $this->emoticon_create_time,
            'emoticon_update_time' => $this->emoticon_update_time,
        ]);

        $query->andFilterWhere(['like', 'emoticon_name', $this->emoticon_name])
            ->andFilterWhere(['like', 'emoticon_url', $this->emoticon_url]);

        return $dataProvider;
    }
}

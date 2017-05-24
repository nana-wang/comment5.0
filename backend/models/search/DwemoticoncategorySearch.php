<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DwemoticonCategory;

/**
 * FacecategorySearch represents the model behind the search form about `frontend\models\FaceCategory`.
 */
class DwemoticoncategorySearch extends DwemoticonCategory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'emoticon_category_create_time', 'emoticon_category_update_time'], 'integer'],
            [['emoticon_category_name'], 'safe'],
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
        $query = DwemoticonCategory::find()->where(['emoticon_category_status'=>1]);

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
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'emoticon_category_create_time' => $this->emoticon_category_create_time,
            'emoticon_category_update_time' => $this->emoticon_category_update_time,
        ]);

        $query->andFilterWhere(['like', 'emoticon_category_name', $this->emoticon_category_name]);

        return $dataProvider;
    }
}

<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Report;

/**
 * PostSearch represents the model behind the search form about `app\models\Post`.
 */
class ReportSearch extends Report
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['report_idtype', 'report_uid'], 'required'],
            [['report_from_uid', 'report_create', 'sensitive_time','report_status'], 'integer'],
            [['report_content_title'], 'string', 'max' => 50],
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
     * 管理员搜索，允许按所有字段搜索
     * @param $params
     * @return ActiveDataProvider
     */
    public function adminSearch($params) {
        $this->scenario = 'adminSearch';
        $query = Report::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'report_idtype' => $this->report_idtype,
        ]);

        $query->andFilterWhere(['like', 'report_idtype', $this->report_idtype]);

        return $dataProvider;
    }
    /**
     * 首页搜索
     * @param $params
     * @return ActiveDataProvider
     */
    public function indexSearch($params) {
        $this->scenario = 'indexSearch';
        $this->load($params, $formName='');
        $query = Report::find();
        $query->andFilterWhere(['like', 'report_idtype', $this->report_idtype]);
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

}
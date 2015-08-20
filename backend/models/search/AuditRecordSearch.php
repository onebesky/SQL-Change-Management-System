<?php

namespace backend\models\search;

use common\models\AuditRecord;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class AuditRecordSearch extends AuditRecord {
 
    /**
     * @inheritdoc
     */
    public function rules2()
    {
        return [
            [['application', 'category', 'event', 'created_at'], 'safe'],
        ];
    }
    
    public function rules() {
        return [
            [['event', 'connected_type', 'created_at', 'user_id', 'connected_id'], 'safe'],
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
        $query = AuditRecord::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'connected_type', $this->connected_type]);
        $query->andFilterWhere(['like', 'user_id', $this->user_id]);
        $query->andFilterWhere(['like', 'event', $this->event]);

        return $dataProvider;
    }
}
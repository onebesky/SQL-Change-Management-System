<?php

namespace backend\models\search;

use common\models\ServerConnection;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PageSearch represents the model behind the search form about `common\models\Page`.
 */
class ServerConnectionSearch extends ServerConnection
{
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'connection_string', 'name'], 'safe'],
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
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ServerConnection::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->andWhere('server_connection.active=1');

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'type' => $this->type,
        ]);
        
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'connection_string', $this->connection_string])
            ->andFilterWhere(['like', 'id', $this->id]);

        return $dataProvider;
    }
}
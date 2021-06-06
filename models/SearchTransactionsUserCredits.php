<?php

namespace system\modules\transactions\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use system\modules\transactions\models\TransactionsUserCredits;

/**
 * SearchTransactionsUserCredits represents the model behind the search form of `system\modules\transactions\models\TransactionsUserCredits`.
 */
class SearchTransactionsUserCredits extends TransactionsUserCredits
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'created_by', 'created_user_mode', 'factor'], 'integer'],
            [['credit'], 'number'],
            [['created_at', 'description'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = TransactionsUserCredits::find();

        if ($this->hasLanguage){
            if (!empty( $_GET['lng'])){
                $query=$query->where(['language'=> $_GET['lng']]);
            }else{
                $query=$query->where(['language_parent'=>null]);
            }
        }
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'credit' => $this->credit,
            'uid' => $this->uid,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'created_user_mode' => $this->created_user_mode,
            'factor' => $this->factor,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}

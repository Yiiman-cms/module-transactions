<?php

namespace system\modules\transactions\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use system\modules\transactions\models\TransactionsCoupons;

/**
 * SearchTransactionsCoupons represents the model behind the search form of `system\modules\transactions\models\TransactionsCoupons`.
 */
class SearchTransactionsCoupons extends TransactionsCoupons
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'expire', 'status', 'limit_count', 'mode', 'uid_limit', 'created_by'], 'integer'],
            [['price'], 'number'],
            [['start_from', 'created_at'], 'safe'],
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
        $query = TransactionsCoupons::find();

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
            'price' => $this->price,
            'expire' => $this->expire,
            'status' => $this->status,
            'start_from' => $this->start_from,
            'limit_count' => $this->limit_count,
            'mode' => $this->mode,
            'uid_limit' => $this->uid_limit,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
        ]);

        return $dataProvider;
    }
}

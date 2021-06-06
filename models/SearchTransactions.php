<?php

namespace system\modules\transactions\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use system\modules\transactions\models\Transactions;

/**
 * SearchTransactions represents the model behind the search form of `system\modules\transactions\models\Transactions`.
 */
class SearchTransactions extends Transactions
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'status', 'pay_module_id', 'created_user_mode', 'created_from_uid', 'factor'], 'integer'],
            [['terminal', 'description', 'created_at', 'payed_at', 'terminal_pre_pay_serial', 'terminal_after_pay_serial', 'terminal_final_transaction_serial', 'pay_module'], 'safe'],
            [['price'], 'number'],
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
        $query = Transactions::find();

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
            'uid' => $this->uid,
            'created_at' => $this->created_at,
            'payed_at' => $this->payed_at,
            'status' => $this->status,
            'pay_module_id' => $this->pay_module_id,
            'created_user_mode' => $this->created_user_mode,
            'created_from_uid' => $this->created_from_uid,
            'price' => $this->price,
            'factor' => $this->factor,
        ]);

        $query->andFilterWhere(['like', 'terminal', $this->terminal])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'terminal_pre_pay_serial', $this->terminal_pre_pay_serial])
            ->andFilterWhere(['like', 'terminal_after_pay_serial', $this->terminal_after_pay_serial])
            ->andFilterWhere(['like', 'terminal_final_transaction_serial', $this->terminal_final_transaction_serial])
            ->andFilterWhere(['like', 'pay_module', $this->pay_module]);

        return $dataProvider;
    }
}

<?php

namespace system\modules\transactions\models;

use Yii;
use yii\base\Model;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use system\modules\transactions\models\TransactionsFactor;

/**
 * SearchTransactionsFactor represents the model behind the search form of `system\modules\transactions\models\TransactionsFactor`.
 */
class SearchTransactionsFactor extends TransactionsFactor
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'status', 'uid', 'tax_percent', 'discount_percent','total_price'], 'integer'],
            [['created_at', 'payed_at'], 'safe'],
            [['price', 'tax_price', 'discount_price', 'user_credit'], 'number'],
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
        $query = TransactionsFactor::find();

//        if ($this->hasLanguage) {
//            if (!empty($_GET['lng'])) {
//                $query = $query->where(['language' => $_GET['lng']]);
//            } else {
//                $query = $query->where(['language_parent' => null]);
//            }
//        }
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
        $date = Yii::$app->functions->convertdate($this->created_at, 1);
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,

            'created_by' => $this->created_by,
            'status' => $this->status,
            'uid' => $this->uid,
            'payed_at' => $this->payed_at,
            'price' => $this->price,
            'tax_price' => $this->tax_price,
            'tax_percent' => $this->tax_percent,
            'total_price' => $this->total_price,
            'discount_price' => $this->discount_price,
            'discount_percent' => $this->discount_percent,
            'user_credit' => $this->user_credit,
        ]);
        $query->andWhere('created_at like "'.$date.'%"');
        return $dataProvider;
    }
}

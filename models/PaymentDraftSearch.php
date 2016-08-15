<?php

namespace backend\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\payment\models\Payment;

/**
 * PaymentDraftSearch represents the model behind the search form about `backend\modules\payment\models\Payment`.
 */
class PaymentDraftSearch extends Payment {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'created_at', 'status', 'paid_at', 'seller_id', 'send_by_cash', 'send_by_transfer', 'staff_id'], 'integer'],
            [['data', 'transfer_date'], 'safe'],
            [['send_cash_amount', 'send_transfer_amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($params) {
        $query = Payment::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->where([
            'seller_id' => Yii::$app->user->id,
            'status' => 0]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'paid_at' => $this->paid_at,
            'seller_id' => $this->seller_id,
            'send_by_cash' => $this->send_by_cash,
            'send_by_transfer' => $this->send_by_transfer,
            'send_cash_amount' => $this->send_cash_amount,
            'send_transfer_amount' => $this->send_transfer_amount,
            'transfer_date' => $this->transfer_date,
            'staff_id' => $this->staff_id,
        ]);

        $query->andFilterWhere(['like', 'data', $this->data]);

        return $dataProvider;
    }

}

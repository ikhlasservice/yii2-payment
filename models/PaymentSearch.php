<?php

namespace ikhlas\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ikhlas\payment\models\Payment;

/**
 * PaymentSearch represents the model behind the search form about `ikhlas\payment\models\Payment`.
 */
class PaymentSearch extends Payment {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'created_at', 'paid_at', 'seller_id', 'status'], 'integer'],
            [['data'], 'safe'],
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

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        if (Yii::$app->user->can('seller')) {
            $query->where(['seller_id' => Yii::$app->user->id]);
            $query->andWhere(['!=','status',0]);
        } elseif (Yii::$app->user->can('staff')) {
            $query->where(['status' => [3, 4, 5]]);
        }
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'paid_at' => $this->paid_at,
            'seller_id' => $this->seller_id,
        ]);

        $query->andFilterWhere(['like', 'data', $this->data]);

        return $dataProvider;
    }

}

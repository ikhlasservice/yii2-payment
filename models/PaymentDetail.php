<?php

namespace ikhlas\payment\models;

use Yii;
use backend\modules\credit\models\Contract;

/**
 * This is the model class for table "payment_detail".
 *
 * @property integer $payment_id
 * @property integer $contract_id
 * @property double $amount
 * @property string $note
 * @property integer $reference
 *
 * @property Payment $payment
 * @property Contract $contract
 */
class PaymentDetail extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'payment_detail';
    }

//    public function behaviors() {
//        return [
//            [
//                'class' => AttributeBehavior::className(),
//                'attributes' => [
//                    ActiveRecord::EVENT_BEFORE_INSERT => 'amount',
//                    ActiveRecord::EVENT_BEFORE_UPDATE => 'amount',
//                ],
//                'value' => function ($event) {
//            return implode(',', $this->skill);
//        },
//            ],
//        ];
//    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['contract_id', 'amount'], 'required'],
            [['payment_id', 'reference','period'], 'integer'],
            //[['amount'], 'number'],
            [['contract_id'], 'string', 'max' => 8],
            [['note'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'payment_id' => Yii::t('payment', 'เลขที่ใบนำส่ง'),
            'contract_id' => Yii::t('payment', 'เลขที่สัญญา'),
            'old_balance' => Yii::t('payment', 'ยอดเดิม'),
            'amount' => Yii::t('payment', 'ยอดชำระ'),
            'balance' => Yii::t('payment', 'ยอดคงเหลือ'),
            'note' => Yii::t('payment', 'หมายเหตุ'),
            'reference' => Yii::t('payment', 'หมายเลขอ้างอิง'),
            'period' => Yii::t('payment', 'งวดที่'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayment() {
        return $this->hasOne(Payment::className(), ['id' => 'payment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContract() {
        return $this->hasOne(Contract::className(), ['id' => 'contract_id']);
    }

    #############################################
    /**
     * ลบ
     * @param type $id
     */

    public static function deleteByIDs($id) {
        //print_r($id);
        $model = self::deleteAll(['contract_id' => $id]);
        //return $model->deleteAll();
    }

    /*
     * หาค่างวดครั้งที่เท่าไร
     */

    public static function getPeriodPay($contract_id) {
        $model = \ikhlas\payment\models\PaymentDetail::find()
                ->joinWith('contract')
                ->joinWith('payment')
                ->where([
                    'contract.id' => $contract_id,
                    'payment.status' => 3
                ])
                //->andWhere(['!=', 'payment_id', $this->payment_id])
                ->andWhere(['!=', 'payment_detail.amount', 0])
                ->count();
        $model+=1;
        return $model;
    }

    public function getOldBalances() {
        $periodCount = self::find()
                ->joinWith('contract')
                ->joinWith('payment')
                ->where([
                    'contract.id' => $this->contract_id,
                    'payment.status' => 3
                ])
                ->andWhere(['!=', 'payment_id', $this->payment_id])
                ->sum('payment_detail.amount');
        //echo $periodCount;       
        return $this->contract->totalPayAll - $periodCount;
    }

    /**
     * ยอดคงเหลือ
     */
    public function getBalances() {
        return $this->oldBalances - $this->amount;
    }
    

}

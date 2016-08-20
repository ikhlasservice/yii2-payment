<?php

namespace ikhlas\payment\models;

use Yii;
use common\models\User;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "payment_progress".
 *
 * @property integer $id
 * @property integer $payment_id
 * @property integer $status
 * @property string $comment
 * @property string $data
 * @property integer $created_by
 * @property integer $created_at
 *
 * @property User $createdBy
 * @property Payment $payment
 */
class PaymentProgress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_progress';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payment_id'], 'required'],
            [['payment_id', 'status', 'created_by', 'created_at'], 'integer'],
            [['comment', 'data'], 'string'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['payment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Payment::className(), 'targetAttribute' => ['payment_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('payment', 'รหัสการดำเนินงาน'),
            'payment_id' => Yii::t('payment', 'เลขใบส่งเงิน'),
            'status' => Yii::t('payment', 'สถานะ'),
            'comment' => Yii::t('payment', 'ความคิดเห็น/เหตุผล'),
            'data' => Yii::t('payment', 'ข้อมูล'),
            'created_by' => Yii::t('payment', 'บันทึกโดย'),
            'created_at' => Yii::t('payment', 'บันทึกเมื่อ'),
        ];
    }

    public static function itemsAlias($key) {
        $items = [
            'status' => [
                1 => Yii::t('app', 'อนุมัติ'),
                2 => Yii::t('app', 'ไม่อนุมัติ'),
                //2 => Yii::t('app', 'ยกเลิก'),
            ],
            'statusFinane' => [
                1 => Yii::t('app', 'ถูกต้องครบถ้วน'),
                2 => Yii::t('app', 'ไม่ครบถ้วน'),
                //2 => Yii::t('app', 'ยกเลิก'),
            ],
        ];
        return ArrayHelper::getValue($items, $key, []);
    }

    public function getStatusLabel() {
        $status = ArrayHelper::getValue($this->getItemStatus(), $this->status);
        //$status = ($this->status === NULL) ? ArrayHelper::getValue($this->getItemStatus(), 0) : $status;
        switch ($this->status) {
            case '0' :
                $str = '<span class="label label-warning">' . $status . '</span>';
                break;
            case '1' :
                $str = '<span class="label label-success">' . $status . '</span>';
                break;
            case '2' :
                $str = '<span class="label label-danger">' . $status . '</span>';
                break;
            default :
                $str = $status;
                break;
        }

        return $str;
    }

    public static function getItemStatus() {
        return self::itemsAlias('status');
    }
    
    public static function getItemStatusFinance() {
        return self::itemsAlias('statusFinane');
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayment()
    {
        return $this->hasOne(Payment::className(), ['id' => 'payment_id']);
    }
}

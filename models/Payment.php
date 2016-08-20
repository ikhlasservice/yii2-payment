<?php

namespace ikhlas\payment\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\User;
use backend\modules\credit\models\Contract;

/**
 * This is the model class for table "payment".
 * 
 * เกี่ยวกับการจัดการงานทั้งหมด
 * @property integer $id
 * @property integer $created_at
 * @property integer $status 
 * @property integer $paid_at
 * @property integer $seller_id
 * @property string $data
 * @property integer $send_by_cash
 * @property integer $send_by_transfer
 * @property string $send_cash_amount
 * @property string $send_transfer_amount
 * @property string $transfer_date
 * @property integer $staff_id
 *
 * @property User $seller
 * @property User $staff 
 * @property PaymentDetail[] $paymentDetails
 * @property Contract[] $contracts
 * @package model.payment
 */
class Payment extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'payment';
    }
    
    public function behaviors() {
        return [
            [
                'class' => 'mdm\autonumber\Behavior',
                'attribute' => 'id', // required
                //'group' => $this->id, // optional
                'value' => (substr((date('Y') + 543), 2)) . '-'.(date('n')).'-?', // format auto number. '?' will be replaced with generated number
                //'digit' => 2 // optional, default to null. 
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['transfer_date'], 'required', 'on' => 'confirm'],
            [['created_at', 'status', 'paid_at', 'seller_id', 'staff_id', 'send_by_cash', 'send_by_transfer'], 'integer'],
            [['data'], 'string'],
            //[['send_cash_amount', 'send_transfer_amount'], 'number'],
            [['transfer_date'], 'safe']
        ];
    }

//    public function scenarios() {
//        
//        
//        $scenarios = parent::scenarios();
//        $scenarios['confirm'] = ['transfer_date'];//Scenario Values Only Accepted
//        return $scenarios;
//
//    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('payment', 'เลขที่ใบนำส่ง'),
            'created_at' => Yii::t('payment', 'วันที่'),
            'status' => Yii::t('payment', 'สถานะ'),
            'paid_at' => Yii::t('payment', 'วันที่ส่ง'),
            'seller_id' => Yii::t('payment', 'ตัวแทน'),
            'data' => Yii::t('payment', 'ข้อมูลสำรอง'),
            'send_by_cash' => Yii::t('payment', 'เงินสด'),
            'send_by_transfer' => Yii::t('payment', 'โอนเข้าบัญชีธนาบริษัท'),
            'send_cash_amount' => Yii::t('payment', 'จำนวนเงินสด'),
            'send_transfer_amount' => Yii::t('payment', 'จำนวนเงินโอน'),
            'total_amount' => Yii::t('payment', 'รวมทั้งหมด'),
            'transfer_date' => Yii::t('payment', 'โอนเงิน วันที่'),
            'attach_file' => Yii::t('payment', 'ไฟล์แนบ สำหรับแสดงฐานการโอนเงิน'),
            'staff_id' => Yii::t('payment', 'เจ้าหน้าที่'),
            'paymentTotal' => Yii::t('payment', 'จำนวนเงิน'),
        ];
    }

    public function attributeHints() {
        return [

            'attach_file' => Yii::t('payment', 'รองรับไฟล์ pdf, jpg, png'),
        ];
    }

    public static function itemsAlias($key) {
        $items = [
            'status' => [
                0 => Yii::t('app', 'ร่าง'),
                1 => Yii::t('app', 'เสนอ'),
                2 => Yii::t('app', 'พิจารณา'),
                3 => Yii::t('app', 'อนุมัติ'),
                4 => Yii::t('app', 'ไม่อนุมัติ'),
                5 => Yii::t('app', 'ยกเลิก'),
                6 => Yii::t('app', 'รับเรื่องแล้ว'),
            ],
            'sendBy' => [
                1 => 'เงินสด',
                2 => 'โอนเข้าบัญชีธนาคารบริษัท',
            ],
        ];
        return ArrayHelper::getValue($items, $key, []);
    }

    public function getStatusLabel() {
        $status = ArrayHelper::getValue($this->getItemStatus(), $this->status);
        $status = ($this->status === NULL) ? ArrayHelper::getValue($this->getItemStatus(), 0) : $status;
        switch ($this->status) {
            case '0' :
            case '4' :
            case '5' :
            case NULL :
                $str = '<span class="label label-danger">' . $status . '</span>';
                break;
            case '1' :
            case '2' :
                $str = '<span class="label label-primary">' . $status . '</span>';
                break;
            case '3' :
                $str = '<span class="label label-success">' . $status . '</span>';
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

    public function getSendByLabel() {
        return ArrayHelper::getValue($this->getItemSendBy(), $this->send_by);
    }

    public static function getItemSendBy() {
        return self::itemsAlias('sendBy');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeller() {
        return $this->hasOne(User::className(), ['id' => 'seller_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaff() {
        return $this->hasOne(User::className(), ['id' => 'staff_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentDetails() {
        return $this->hasMany(PaymentDetail::className(), ['payment_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContracts() {
        return $this->hasMany(Contract::className(), ['id' => 'contract_id'])->viaTable('payment_detail', ['payment_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentProgresses() {
        return $this->hasMany(PaymentProgress::className(), ['payment_id' => 'id']);
    }

    /*
     * ใช้ดึงข้อมูลของ Seller ที่ต้องชำระเท่านั้น
     */
    public static function getMustPays() {        
        $model = Contract::find()
                ->joinWith('credit')
                ->where([
                    'credit.seller_id' => Yii::$app->user->id,
                    'contract.status' => 1
                ])
                ->all();
        return $model;
    }

    public function getLicense() {
        return Html::beginTag('div', ['class' => 'row'])
                . Html::beginTag('div', ['class' => 'col-sm-12 text-center', 'style' => ''])
                . Html::tag('p', Html::tag('b', 'ลงชื่อ ') . Html::tag('span', $this->displayname, ['style' => 'border-bottom:1px dotted #888;']))
                . Html::tag('span', '(' . $this->displayname . ')') . '<br />'
                . Html::tag('span', \yii\helpers\BaseInflector::humanize($this->roleName)) . '<br />'
                . Html::tag('span', Yii::$app->formatter->asDate(time()))
                . Html::endTag('div')
                . Html::endTag('div');
    }

    const UPLOAD_FOLDER = 'payment';

    public static function findFiles($pathFile) {
        $files = [];
        $findFiles = \yii\helpers\FileHelper::findFiles($pathFile);
        ksort($findFiles);
        // set pdfs as target folder
        //print_r($findFiles);
        foreach ($findFiles as $index => $file) {
            if (strpos($file, 'thumbnail') === false) {
                $nameFicheiro = substr($file, strrpos($file, '/') + 1);
                $files[$nameFicheiro] = $nameFicheiro;
            }
        }
        return $files ? \yii\helpers\Json::encode($files) : null;
    }

    public function initialPreview($data, $field, $type = 'file') {
        $initial = [];
        $img = Yii::$app->img;
        $files = '';
        if ($data != NULL) {
            $files = \yii\helpers\Json::decode($data);
            ksort($files);
        }
        //$files = '';
        if (is_array($files)) {
            foreach ($files as $key => $value) {
                if ($type == 'file') {
                    if ($img->chkImg(self::UPLOAD_FOLDER . '/' . $this->id, $value)) {
                        $initial[] = \yii\helpers\Html::a(\yii\helpers\Html::img(Yii::$app->img->getUploadUrl(self::UPLOAD_FOLDER . '/' . $this->id) . $value, ['class' => 'file-preview-image']), Yii::$app->img->getUploadUrl(self::UPLOAD_FOLDER . '/' . $this->id) . $value, ['target' => '_blank']);
                    } else {
                        $initial[] = \yii\helpers\Html::a('<object data="' . Yii::$app->img->getUploadUrl(self::UPLOAD_FOLDER . '/' . $this->id) . $value . '" type="application/pdf" width="100%" height="200"></object>', Yii::$app->img->getUploadUrl(self::UPLOAD_FOLDER . '/' . $this->id) . $value, ['target' => '_blank']);
                        //$initial[] = '<object data="'.Yii::$app->img->getUploadUrl(self::UPLOAD_FOLDER . '/' . $this->id) . $value.'" type="application/pdf" width="200" height="160"><p>Alternative text - include a link <a href="myfile.pdf">to the PDF!</a></p></object>';
                    }
                } elseif ($type == 'config') {
                    $initial[] = [
                        'caption' => $value,
                        'width' => '120px',
                        'url' => \yii\helpers\Url::to(['deletefile-ajax', 'id' => $this->id, 'fileName' => $value, 'field' => $field, 'folder' => self::UPLOAD_FOLDER]),
                        'key' => $value
                    ];
                }
            }
        }
        return $initial;
    }

    public function viewPreview($data) {
        $initial = [];
        $img = Yii::$app->img;
        $files = '';
        if ($data != NULL) {
            $files = \yii\helpers\Json::decode($data);
            ksort($files);
        }
        if (is_array($files)) {
            foreach ($files as $key => $value) {
                if ($img->chkImg(self::UPLOAD_FOLDER . '/' . $this->id, $value)) {
                    $initial[] = [
                        'url' => $img->getUploadUrl(self::UPLOAD_FOLDER . '/' . $this->id) . $value,
                        'src' => $img->getUploadThumbnailUrl(self::UPLOAD_FOLDER . '/' . $this->id) . $value,
                        'options' => array('title' => $key)
                    ];
                } else {
                    echo \yii\helpers\Html::a('<object data="' . Yii::$app->img->getUploadUrl(self::UPLOAD_FOLDER . '/' . $this->id) . $value . '" type="application/pdf" width="100%" height="500"></object>', Yii::$app->img->getUploadUrl(self::UPLOAD_FOLDER . '/' . $this->id) . $value, ['target' => '_blank']);
                }
            }
        }
        return $initial;
    }
    
    
    /**
     * ฟังค์ชั่นอ่านค่า
     * @return decimal
     */
    public function getPaymentTotal(){
        $detail = $this->paymentDetails;
        $sum = 0;
        foreach ($detail as $val):
            $sum+=$val->amount;
        endforeach;
        return $sum;
    }

}

<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\widgets\Typeahead;
use yii\widgets\MaskedInput;
use wbraganca\dynamicform\DynamicFormWidget;
use ikhlas\payment\models\PaymentDetail;
use backend\modules\customer\models\Customer;
use yii\bootstrap\Modal;
use ikhlas\payment\models\Payment;

$labelPaymentDetail = new PaymentDetail;
$labelCustomer = new Customer;

$template = '<div>' .
        '<p class="repo-language"><i class="fa fa-info-circle"></i> {{value}}</p>' .
        '<p class="repo-name">{{display}} <i>{{brand}}</i></p><hr style="margin:5px 0" />' .
        '</div>';
?>

<div id="panel-option-values" class="panel panel-default">


    <div class="table-responsive dynamicform_wrapper" style="border: 1px solid #bbb;">
        <table class="table table-bordered table-striped ">
            <thead>
                <tr  style="border-bottom:2px solid #eee;">
                    <th class="text-center text-nowrap" style="width: 50px;" >
                        <?= Yii::t('credit', 'ลำดับที่'); ?>
                    </th>
                    <th class="required text-nowrap" style="width: 50px;">
                        <?= $labelPaymentDetail->getAttributeLabel('contract_id') ?>
                    </th>
                    <th class="text-nowrap" style="width: 100px;">
                        <?= $labelCustomer->getAttributeLabel('fullname') ?>/<?= $labelCustomer->getAttributeLabel('id') ?>
                    </th>
                    <th class="text-nowrap" style="width: 50px;">
                        <?= Yii::t('credit', 'รายการสืนค้า'); ?>
                    </th>
                    <th class="text-right text-nowrap" style="width: 30px;">
                        <?= Yii::t('credit', 'งวดที่'); ?>
                    </th>

                    <th class="text-right text-nowrap" style="width: 50px;">
                        <?= Yii::t('credit', 'ยอดเดิม'); ?>
                    </th>   
                    <th class="text-right text-nowrap" style="width: 80px;">
                        <?= $labelPaymentDetail->getAttributeLabel('amount') ?>
                    </th>
                    <th class="text-right text-nowrap" style="width: 50px;">
                        <?= Yii::t('credit', 'ยอดคงเหลือ'); ?>
                    </th>                    
                    <th class="text-right text-nowrap" style="width: 50px;">
                        <?= $labelPaymentDetail->getAttributeLabel('note') ?>
                    </th>
                </tr>

            </thead>
            <tbody class="form-options-body">
                <?php
                $totalAmount = 0;
                foreach ($modelDetails as $index => $modelOptionValue):
                    $totalAmount += $modelOptionValue->amount;
                    ?>

                    <tr class="form-options-item">
                        <td class="text-right number" >
                            <?= ($index + 1) ?>
                        </td>
                        <td class="text-right contract_id text-nowrap"  style="width: 50px;">
                            <?= $modelOptionValue->contract_id ?>

                        </td>     

                        <td class="fullname text-nowrap">
                            <?= $modelOptionValue->contract->credit->fullname ?><br/>
                            <?= Html::tag('small', $modelOptionValue->contract->credit->customer->id) ?>
                        </td>


                        <td class="products">
                            <?= $modelOptionValue->contract->credit->productsLabel ?>
                        </td>

                        <td class="text-right period">
                            <?= $modelOptionValue->period . '/' . $modelOptionValue->contract->credit->period ?>
                        </td>

                        <td class="text-right text-nowrap oldBalance">
                            <?= Yii::$app->formatter->asDecimal($modelOptionValue->old_balance) ?>
                        </td>

                        <td class="amount text-right">                        
                            <?= Yii::$app->formatter->asDecimal($modelOptionValue->amount, 2)
                            ?>                    
                        </td>

                        <td class="text-right text-nowrap balances">
                            <?= Yii::$app->formatter->asDecimal($modelOptionValue->balance) ?>
                        </td>                  

                        <td class="note">    
                            <?= $modelOptionValue->note ?> 
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>

            <tfoot>
                <tr style="border-top:2px solid #eee;">
                    <th colspan="2" class="text-nowrap tdDecimal">
                        ตัวอักษร
                    </th>
                    <th colspan="3" class="text-center tdDecimal numThai" >
                        <?= $totalAmount ?>
                    </th>
                    <th  class="text-right text-nowrap tdDecimal" >
                        <?= Yii::t('credit', 'รวม'); ?>
                    </th>
                    <th class="text-right totalAmount tdDecimal" >
                        <?= Yii::$app->formatter->asDecimal($totalAmount) ?>
                    </th>
                    <th class="tdDecimal" >
                        <?= Yii::t('credit', 'บาท'); ?>
                    </th>
                    <th class="totalAll text-right tdDecimal" >&nbsp;</th>
                </tr>

                <tr>
                    <td colspan="10" class="text-center">
                        <?= Html::tag('small', 'ขอรับรองว่ารายการข้างต้นเป็นจริง ถูกต้องทุกประการ') ?>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" rowspan="2" class="text-right">
                        <?= Html::tag('span', 'นำส่งเงินโดย') ?>
                    </td>
                    <td >
                        <i class="fa <?= ($model->send_by_cash ? 'fa-check-circle-o ' : 'fa-circle-o') ?>"></i> 
                        <?= $model->getAttributeLabel('send_by_cash') ?>
                    </td>
                    <td colspan="2" class="text-right">
                        จำนวนเงิน
                    </td>
                    <td class="text-right">
                        <?= Yii::$app->formatter->asDecimal($model->send_cash_amount) ?>  
                    </td>
                    <td colspan="4">
                        บาท
                    </td>
                </tr>

                <tr>
                    <td class="text-nowrap">
                        <i class="fa <?= ($model->send_by_transfer ? 'fa-check-circle-o ' : 'fa-circle-o ') ?>"></i> 
                        <?= $model->getAttributeLabel('send_by_transfer') ?>
                    </td>
                    <td colspan="2" class="text-right">
                        จำนวนเงิน
                    </td>
                    <td class="text-right">
                        <?= Yii::$app->formatter->asDecimal($model->send_transfer_amount) ?>                         
                    </td>
                    <td >
                        บาท
                    </td>
                    <td  class="text-right">
                        <?= $model->getAttributeLabel("transfer_date") ?>
                    </td>
                    <td colspan="2" >  

                        <?= Yii::$app->formatter->asDatetime($model->transfer_date); ?>  
                    </td>
                    
                </tr>

                <tr>                    
                    <td colspan="5" class="text-right">
                        <?= Html::tag('label', 'รวม') ?>
                    </td>
                    <td class="text-right">   
                        <?= Html::tag('label', Yii::$app->formatter->asDecimal(($model->send_cash_amount + $model->send_transfer_amount))); ?>   
                    </td>
                    <td colspan="4">

                        <?= Html::tag('label', 'บาท') ?>
                    </td>
                </tr>

<!--                <tr>                    
                    <td colspan="5" class="text-right">
                        <?= $model->getAttributeLabel("transfer_date") ?>
                    </td>
                    <td colspan="5">  

                        <?= Yii::$app->formatter->asDatetime($model->transfer_date); ?>  
                    </td>
                </tr>-->


            </tfoot>
        </table>

    </div>

</div>

<div class="row">
    <div class="col-xs-12 col-sm-4 col-sm-offset-7">
        <?= $model->seller->license ?>
    </div>       
</div>
<hr />
<div class="row">
    <div class="col-xs-12 col-sm-10 col-sm-offset-1">
        <?=Html::tag('label',$model->getAttributeLabel('attach_file'))?>
        <?= dosamigos\gallery\Gallery::widget(['items' => $model->viewPreview($model->attach_file)]); ?>
    </div>       
</div>






<?php
$base = ikhlas\payment\assets\AppAsset::register($this);
$this->registerJsFile($base->baseUrl . '/js/view.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\widgets\Typeahead;
use yii\widgets\MaskedInput;
use wbraganca\dynamicform\DynamicFormWidget;
use backend\modules\payment\models\PaymentDetail;
use backend\modules\customer\models\Customer;
use yii\bootstrap\Modal;
use backend\modules\payment\models\Payment;

$labelPaymentDetail = new PaymentDetail;
$labelCustomer = new Customer;

$template = '<div>' .
        '<p class="repo-language"><i class="fa fa-info-circle"></i> {{value}}</p>' .
        '<p class="repo-name">{{display}} <i>{{brand}}</i></p><hr style="margin:5px 0" />' .
        '</div>';
?>

<div id="panel-option-values" class="panel panel-default">


    <div class="table-responsive dynamicform_wrapper" style="border: 1px solid #bbb;">
        <table class="table table-striped ">
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
                    <th class="text-right text-nowrap" style="width: 50px;">
                        <?= Yii::t('credit', 'ปิดยอด'); ?>
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
                foreach ($modelDetails as $index => $modelDetail):
                    $totalAmount += $modelDetail->amount;
                    ?>

                    <tr class="form-options-item">
                        <td class="text-right number" >
                            <?= ($index + 1) ?>
                        </td>
                        <td class="text-right contract_id text-nowrap"  style="width: 50px;">
                            <?= Html::button($modelDetail->contract_id, ['class' => 'btn btn-xs']); ?>
                            <?= $form->field($modelDetail, "[{$index}]contract_id")->hiddenInput()->label(false); ?>  
                        </td>     

                        <td class="fullname text-nowrap">
                            <?= $modelDetail->contract->credit->fullname ?><br/>
                            <?= Html::tag('small', $modelDetail->contract->credit->customer->id) ?>
                        </td>


                        <td class="products">
                            <?= $modelDetail->contract->credit->productsLabel ?>
                        </td>

                        <td class="text-right period">                            
                            <?= $modelDetail->period . '/' . $modelDetail->contract->credit->period ?>
                            <?= $form->field($modelDetail, "[{$index}]period")->hiddenInput()->label(false); ?>  
                        </td>

                        <td class="text-right text-nowrap oldBalance">
                            <?= Yii::$app->formatter->asDecimal($modelDetail->old_balance) ?>
                            <?= $form->field($modelDetail, "[{$index}]old_balance")->hiddenInput()->label(false); ?>  
                        </td>

                        <td class="text-right text-nowrap offBalance">
                            <?= Html::button(Yii::$app->formatter->asDecimal($modelDetail->contract->offBalance), ['class' => 'btn btn-xs']) ?>
                        </td>

                        <td class="amount">                        
                            <?=
                                    $form->field($modelDetail, "[{$index}]amount")->label(false)
                                    ->widget(MaskedInput::className(), [
                                        'options' => [
                                            'placeholder' => Yii::$app->formatter->asDecimal($modelDetail->contract->credit->totalPay, 2),
                                            'style' => 'width:80px;',
                                        ],
                                        'name' => 'amount' . $index,
                                        'clientOptions' => [
                                            'alias' => 'decimal',
                                            'groupSeparator' => ',',
                                            'autoGroup' => true
                                        ],
                            ]);
                            ?>                    
                        </td>

                        <td class="text-right text-nowrap">
                             <span class="balances">
                            <?= Yii::$app->formatter->asDecimal(($modelDetail->contract->balances - $modelDetail->amount)) ?>
                            </span>
                            <?= $form->field($modelDetail, "[{$index}]balance")->hiddenInput()->label(false); ?>  
                        </td>                  

                        <td class="note">    
                            <?= $form->field($modelDetail, "[{$index}]note")->textInput()->label(false);
                            ?> 
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>

            <tfoot>
                <tr style="border-top:2px solid #eee;">
                    <th colspan="2" class="text-nowrap tdDecimal">
                        ตัวอักษร
                    </th>
                    <th colspan="4" class="text-center tdDecimal numThai" >
                        <?= Yii::$app->formatter->asDecimal($totalAmount) ?>
                    </th>
                    <th  class="text-right text-nowrap tdDecimal" >
                        <?= Yii::t('credit', 'รวม'); ?>
                    </th>
                    <th class="text-right totalAmount tdDecimal" >
                        <?= Yii::$app->formatter->asDecimal($totalAmount) ?>
                    </th>
                    <th class="totalAll text-right tdDecimal" >&nbsp;</th>
                    <th class="totalAll text-right tdDecimal" >&nbsp;</th>
                </tr>

<!--                <tr>
    <td colspan="10" class="text-center">
                <?= Html::tag('small', 'ขอรับรองว่ารายการข้างต้นเป็นจริง ถูกต้องทุกประการ') ?>
    </td>
</tr>-->

                <tr>
                    <td colspan="2" rowspan="2" class="text-right">
                        <?= Html::tag('label', 'นำส่งเงินโดย ' . Html::tag('label', '**', ['class' => 'text-danger'])) ?>
                    </td>
                    <td >
                        <?= $form->field($model, 'send_by_cash')->checkbox(); ?>
                    </td>
                    <td colspan="2" class="text-right">
                        จำนวนเงิน
                    </td>
                    <td >
                        <?=
                                $form->field($model, "send_cash_amount")->label(false)
                                ->widget(MaskedInput::className(), [
                                    'options' => [
                                        'placeholder' => Yii::$app->formatter->asDecimal($totalAmount),
                                        'style' => 'width:80px;',
                                        'disabled' => 'disabled'
                                    ],
                                    'clientOptions' => [
                                        'alias' => 'decimal',
                                        'groupSeparator' => ',',
                                        'autoGroup' => true
                                    ],
                        ]);
                        ?>  
                    </td>
                    <td colspan="4">
                        บาท
                    </td>
                </tr>

                <tr>
                    <td class="text-nowrap">
                        <?= $form->field($model, 'send_by_transfer')->checkbox(); ?>
                    </td>
                    <td colspan="2" class="text-right">
                        จำนวนเงิน
                    </td>
                    <td >
                        <?=
                                $form->field($model, "send_transfer_amount")->label(false)
                                ->widget(MaskedInput::className(), [
                                    'options' => [
                                        'placeholder' => Yii::$app->formatter->asDecimal($totalAmount),
                                        'style' => 'width:80px;',
                                        'disabled' => 'disabled'
                                    ],
                                    'clientOptions' => [
                                        'alias' => 'decimal',
                                        'groupSeparator' => ',',
                                        'autoGroup' => true
                                    ],
                        ]);
                        ?>  
                    </td>
                    <td >
                        บาท
                    </td>
                    <td >
                        <?= $model->getAttributeLabel("transfer_date") ?>
                    </td>
                    <td colspan="3">
                        <?=
                                $form->field($model, "transfer_date")->label(false)
                                ->widget(kartik\widgets\DateTimePicker::className(), [
                                    //'options'=>['data-date-language'=>'th'],
                                    'removeButton' => false,
                                    'pickerButton' => ['icon' => 'time'],
                                    'pluginOptions' => [
                                        'autoclose' => true,
                                        'format' => 'yyyy-mm-dd h:i:s'
                                    ]
                                        ]
                        );
                        ?> 
                    </td>
                </tr>
                

                <tr>                    
                    <td colspan="5" class="text-right">
                        รวม
                    </td>
                    <td class="text-right totalSendAmount">                      
                    </td>
                    <td colspan="4">
                        บาท
                    </td>
                </tr>




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
        <?=
        $form->field($model, 'attach_file[]')->label($model->getAttributeLabel('attach_file'))->hint($model->getAttributeHint('attach_file'))->widget(\kartik\widgets\FileInput::classname(), [
            'options' => [
                //'accept' => 'image/*',
                'multiple' => true,
            ],
            'pluginOptions' => [
                'initialPreview' => $model->initialPreview($model->attach_file, 'docs', 'file'),
                'initialPreviewConfig' => $model->initialPreview($model->attach_file, 'docs', 'config'),
                'allowedFileExtensions' => ['pdf', 'png', 'jpg'],
                'uploadUrl' => Url::to(['uploadajax']),
                'overwriteInitial' => false,
                'initialPreviewShowDelete' => true,
                'showPreview' => true,
                'showRemove' => true,
                'showUpload' => true,
                'uploadExtraData' => [
                    'id' => $model->id,
                    'upload_folder' => Payment::UPLOAD_FOLDER . "/" . $model->id,
                ],
            ],
        ]);
        ?>
    </div>       
</div>


<?php
Modal::begin([
    'header' => Html::tag('h4', 'สัญญา'),
    'id' => 'modalHistory',
    'size' => 'modal-lg'
]);
echo Html::tag('div', '', ['id' => 'modalContent']);
Modal::end();



$this->registerCss(' 
    .checkbox{
     margin:0px 0px;
    }
    .form-group{
    margin-bottom:0px;
    }

');

$base = backend\modules\payment\assets\AppAsset::register($this);
$this->registerJsFile($base->baseUrl . '/js/script.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\modules\payment\models\PaymentProgress;
use yii\widgets\MaskedInput;


if ($model->paymentProgresses):

    foreach ($model->paymentProgresses as $considers):
        ?>
        <div class="box box-widget">
            <div class="box-header with-border">
                <?= $considers->createdBy->displaynameImg ?>
            </div>
            <div class="box-body">
                <?php
                if (isset($considers->status)&& $considers->status!=null){
                    echo Html::tag('p',Html::tag('label', $considers->getAttributeLabel('status') . '&nbsp; ').$considers->statusLabel);
                }

                if (isset($considers->comment)&&$considers->comment) {
                    echo Html::tag('p',Html::tag('label', '<i class="fa fa-comment"></i> ' . $considers->getAttributeLabel('comment') . ' ').$considers->comment);
                }
                ?>
            </div>


            <div class="box-footer box-comments">
                <div class="box-comment">
                    <div class="comment-text"> 
                        <span class="username">                            
                            <span class="text-muted pull-right"> 
        <?= Html::tag('label', '<i class="fa fa-clock-o"></i> ' . $considers->getAttributeLabel('created_at')) ?>
                                <?= Yii::$app->formatter->asDatetime($considers->created_at) ?>
                            </span>
                        </span>
                    </div><!-- /.comment-text -->
                </div><!-- /.box-comment -->
            </div>

        </div>
        <?php
    endforeach;
endif;
######### Staff ################

if (Yii::$app->user->can('staff')&& in_array($model->status, [1,2])):
    $form = ActiveForm::begin();
    echo $form->field($model, "id")->label(false)->hiddenInput();
    ?>
    <div class="box box-widget">
        <div class="box-header with-border">
    <?= common\models\User::getThisUser()->displaynameImg ?>
        </div>
        <div class="box-body">
    <?= Html::tag('b', 'ส่วนของเจ้าที่หน้าที่ผู้อนุมัติ'); ?>
            <?= Html::beginTag('blockquote'); ?>
            <?= $form->field(new PaymentProgress, 'status')->radioList(PaymentProgress::getItemStatus(), ['prompt' => '']) ?>                          
            <?= $form->field(new PaymentProgress, 'comment')->textarea() ?>                          


    <?= Html::endTag('blockquote'); ?>
        </div>



        <div class="box-footer box-comments">
            <div class="box-comment">
                <div class="comment-text"> 
                    <div class="form-group">               
    <?= Html::submitButton(Yii::t('system', 'บันทึก'), ['class' => 'btn btn-success btn_confirm', 'name' => 'btnConfirm']) ?> 

                        <?= Html::a(Yii::t('system', 'ยกเลิก'), ['create', 'id' => $model->id], ['class' => 'btn btn-link']) ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php
    ActiveForm::end();
endif;

######### Finance ################

if (Yii::$app->user->can('finance')&& in_array($model->status, [1,2])):
    $form = ActiveForm::begin();
    echo $form->field($model, "id")->label(false)->hiddenInput();
    ?>
    <div class="box box-widget">
        <div class="box-header with-border">
    <?= common\models\User::getThisUser()->displaynameImg ?>
        </div>
        <div class="box-body">
    <?= Html::tag('b', 'ส่วนของเจ้าหน้าที่การเงินตรวจสอบ'); ?>
            <?= Html::beginTag('blockquote'); ?>
            <?= $form->field(new PaymentProgress, 'status')->radioList(PaymentProgress::getItemStatusFinance(), ['prompt' => ''])->label('การตรวจสอบหลักฐานการนำส่งเงิน') ?>                          
            <?= $form->field(new PaymentProgress, 'comment')->textarea() ?>                          


    <?= Html::endTag('blockquote'); ?>
        </div>



        <div class="box-footer box-comments">
            <div class="box-comment">
                <div class="comment-text"> 
                    <div class="form-group">               
    <?= Html::submitButton(Yii::t('system', 'บันทึก'), ['class' => 'btn btn-success btn_confirm', 'name' => 'btnConfirm']) ?> 

                        <?= Html::a(Yii::t('system', 'ยกเลิก'), ['create', 'id' => $model->id], ['class' => 'btn btn-link']) ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php
    ActiveForm::end();
endif;

?>

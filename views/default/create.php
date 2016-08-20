<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model ikhlas\payment\models\Payment */

$this->title = Yii::t('payment', 'สร้างใบนำส่ง');
$this->params['breadcrumbs'][] = ['label' => Yii::t('payment', 'การชำระเงิน'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?><?php
    $form = ActiveForm::begin([
                'options' => [
                    'id' => 'dynamic-form',
                    'enctype' => 'multipart/form-data'
    ]]);
    echo $form->field($model, 'created_at')->label(false)->hiddenInput();
    ?>
<div class='box box-info'>

    <div class='box-body pad'>
        <div class="row"> 
            <div class="col-xs-6 col-sm-5 "> 
                <?= Html::img(Yii::$app->img->getUploadUrl() . "logo_form.png", ['width' => '100%']) ?>
            </div>
            <div class="col-xs-6 col-sm-5 col-sm-offset-2"> 
                <div class="row">

                    <div class="col-xs-9 col-sm-8 text-right">
                        <?= Html::tag('label', $model->getAttributeLabel('id')) ?>                 
                    </div>
                    <div class="col-xs-3 col-sm-4" style="padding-left: 0px;">    
                        <?= Html::tag('span', '&nbsp;' . $model->id . '&nbsp;', ['class' => 'border-bottom-dotted']) ?>
                    </div>


                    <div class="col-xs-9 col-sm-8 text-right">
                        <?= Html::tag('label', $model->getAttributeLabel('created_at')) ?>                    
                    </div>
                    <div class="col-xs-3 col-sm-4" style="padding-left: 0px;">
                        <?= Yii::$app->formatter->asDate($model->created_at, 'php:d M Y') ?>
                    </div>


                    <div class="col-xs-9 col-sm-8 text-right">
                        <?= Html::tag('label', $model->getAttributeLabel('status')) ?>
                    </div>
                    <div class="col-xs-3 col-sm-4" style="padding-left: 0px;">
                        <?= $model->statusLabel ?>
                    </div>
                </div>
            </div>
        </div>
        <hr />
<!--        <h3 class='box-title text-center'><?= Yii::t('customer', 'แบบฟอร์มยืนขอสินเชื่อ') ?></h3>-->
        <?=
        $this->render('_form', [
            'form' => $form,
            'model' => $model,
            'seller' => $seller,
            'modelDetails' => $modelDetails
        ])
        ?>

    </div><!--box-body pad-->
    <div class="box-footer pad">
        <div class="row">
            <div class="col-sm-11 col-sm-offset-1">
                <?=Html::tag('p',Html::tag('label','**',['class'=>'text-danger']).' โปรดตรวจสอบการกรอกการนำโดยโอนเงิน')?>
            </div>
            <div class="col-sm-11 col-sm-offset-1">
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('customer', 'บันทึก'), ['class' => 'btn btn-primary', 'name' => 'save']) ?>
                    <?= Html::submitButton(Yii::t('customer', 'ยืนใบนำส่ง'), ['class' => 'btn btn-success', 'name' => 'send', 'disabled' => 'disabled']) ?>        
                </div>
            </div>
            
        </div>

    </div>


    
</div><!--box box-info-->
<?php ActiveForm::end(); ?>

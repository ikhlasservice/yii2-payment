<?php

use yii\helpers\Html;
$this->title = Yii::t('payment', 'เลขที่ใบนำส่ง {id}',['id'=>$model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('payment', 'Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
        $this->render('view/_view', [
            'form' => $form,
            'model' => $model,
            'modelDetails' => $model->paymentDetails
        ])
        ?>

    </div><!--box-body pad-->
    <!--    <div class="box-footer pad">
            <div class="row">
                <div class="col-sm-11 col-sm-offset-1">
                    <div class="form-group">
    <?= Html::a(Yii::t('payment', 'แก้ไข'), ['create', 'id' => $model->id], ['class' => 'btn btn-primary', 'name' => 'edit']) ?>
    <?= Html::submitButton(Yii::t('payment', 'ยืนยัน'), ['class' => 'btn btn-success', 'name' => 'confirm',]) ?>        
                    </div>
                </div>
            </div>
    
        </div>-->



</div><!--box box-info-->


<?php
echo $this->render('/progress/viewComment', [
    'model' => $model
]);
?>

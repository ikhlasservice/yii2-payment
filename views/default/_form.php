<?php

use yii\helpers\Html;
use backend\modules\persons\models\Person;

/* @var $this yii\web\View */
/* @var $model backend\modules\payment\models\Payment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-form">

    

<!--    <div class="row"> 
        <div class="col-xs-4 col-xs-offset-8 col-sm-3 col-sm-offset-9">
            <label><?= $model->getAttributeLabel('created_at') ?></label>
            <?= Yii::$app->formatter->asDate($model->created_at, 'php:d M Y') ?>
        </div>
    </div> /.row -->

    <div class="row"> 
        <div class="col-xs-3 col-sm-2 text-right">
            <label><?= $seller->getAttributeLabel('id') ?></label>
        </div><!-- /.col-lg-3 -->
        <div class="col-xs-9 col-sm-6 customer_id">
            <?= $seller->id ?>
        </div><!-- /.col-lg-6 -->
    </div><!-- /.row -->

    <div class="row">
        <div class="col-xs-3 col-sm-2 text-right">
            <label><?= (new Person())->getAttributeLabel('fullname') ?></label>
        </div><!-- /.col-lg-3 -->
        <div class="col-xs-9 col-sm-10 fullname">
            <?= $seller->person->fullname ?>
        </div><!-- /.col-lg-3 -->
    </div><!-- /.row -->



    <div class="row"> 
        <div class="col-xs-3 col-sm-2 text-right">
            <label><?= $seller->person->getAttributeLabel('address_contact') ?></label>
        </div>
        <div class="col-xs-5 col-sm-4">
            <?= $seller->person->addressContact ?>
        </div>
    </div><!-- /.row -->

    <div class="row"> 
        <div class="col-xs-3 col-sm-2 text-right">
            <label><?= $seller->getAttributeLabel('email') ?></label>
        </div>
        <div class="col-xs-5 col-sm-4">
            <?= $seller->person->email ?>
        </div>
    </div><!-- /.row -->

    <div class="row"> 
        <div class="col-xs-3 col-sm-2 text-right">
            <label><?= $seller->person->getAttributeLabel('phone') ?></label>
        </div>
        <div class="col-xs-9 col-sm-10">
            <?= $seller->person->phone ?>
        </div>
    </div><!-- /.row -->

    <!--####################################################-->
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <br />
           
                <?=
                $this->render('_form_detail', [
                    'form' => $form,
                    'model' => $model,
                    'modelDetails' => $modelDetails
                ])
                ?>
            
        </div>
    </div>

    <!--####################################################-->
    

    

</div>

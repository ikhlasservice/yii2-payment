<?php

use yii\helpers\Html;
use backend\modules\persons\models\Person;

/* @var $this yii\web\View */
/* @var $model ikhlas\payment\models\Payment */
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
            <label><?= $model->getAttributeLabel('seller_id') ?></label>
        </div><!-- /.col-lg-3 -->
        <div class="col-xs-9 col-sm-6 customer_id">
            <?= $model->seller_id ?>
        </div><!-- /.col-lg-6 -->
    </div><!-- /.row -->

    <div class="row">
        <div class="col-xs-3 col-sm-2 text-right">
            <label><?= (new Person())->getAttributeLabel('fullname') ?></label>
        </div><!-- /.col-lg-3 -->
        <div class="col-xs-9 col-sm-10 fullname">
            <?= $model->seller_id ? $model->seller->person->fullname : '' ?>
        </div><!-- /.col-lg-3 -->
    </div><!-- /.row -->



    <div class="row"> 
        <div class="col-xs-3 col-sm-2 text-right">
            <label><?= $model->seller->person->getAttributeLabel('address_contact') ?></label>
        </div>
        <div class="col-xs-5 col-sm-4">
            <?= $model->seller->person->addressContact ?>
        </div>
    </div><!-- /.row -->

    <div class="row"> 
        <div class="col-xs-3 col-sm-2 text-right">
            <label><?= $model->seller->getAttributeLabel('email') ?></label>
        </div>
        <div class="col-xs-5 col-sm-4">
            <?= $model->seller->email ?>
        </div>
    </div><!-- /.row -->

    <div class="row"> 
        <div class="col-xs-3 col-sm-2 text-right">
            <label><?= $model->seller->person->getAttributeLabel('phone') ?></label>
        </div>
        <div class="col-xs-9 col-sm-10">
            <?= $model->seller->person->phone ?>
        </div>
    </div><!-- /.row -->

    <!--####################################################-->
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <br />
           
                <?=
                $this->render('_view_detail', [
                    'form' => $form,
                    'model' => $model,
                    'modelDetails' => $modelDetails
                ])
                ?>
            
        </div>
    </div>

    <!--####################################################-->
    

    

</div>

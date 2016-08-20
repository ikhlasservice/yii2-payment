<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model ikhlas\payment\models\PaymentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'paid_at') ?>

    <?= $form->field($model, 'seller_id') ?>

    <?= $form->field($model, 'data') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('payment', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('payment', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

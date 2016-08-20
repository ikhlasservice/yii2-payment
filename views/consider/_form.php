<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model ikhlas\payment\models\Payment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'paid_at')->textInput() ?>

    <?= $form->field($model, 'seller_id')->textInput() ?>

    <?= $form->field($model, 'data')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'send_by_cash')->textInput() ?>

    <?= $form->field($model, 'send_by_transfer')->textInput() ?>

    <?= $form->field($model, 'transfer_date')->textInput() ?>

    <?= $form->field($model, 'staff_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('payment', 'Create') : Yii::t('payment', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

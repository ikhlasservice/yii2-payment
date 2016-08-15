<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\payment\models\PaymentProgress */

$this->title = Yii::t('payment', 'Update {modelClass}: ', [
    'modelClass' => 'Payment Progress',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('payment', 'Payment Progresses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('payment', 'Update');
?>
<div class='box box-info'>
    <div class='box-header'>
        <h3 class='box-title'><?= Html::encode($this->title) ?></h3>
    </div><!--box-header -->

    <div class='box-body pad'>
        <div class="payment-progress-update">

            <!--<h1><?= Html::encode($this->title) ?></h1>-->
            
            <?= $this->render('_form', [
            'model' => $model,
            ]) ?>

        </div>
    </div><!--box-body pad-->
</div><!--box box-info-->

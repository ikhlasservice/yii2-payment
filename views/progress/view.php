<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model ikhlas\payment\models\PaymentProgress */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('payment', 'Payment Progresses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class='box box-info'>
    <div class='box-header'>
        <h3 class='box-title'><?= Html::encode($this->title) ?></h3>
    </div><!--box-header -->

    <div class='box-body pad'>
        <div class="payment-progress-view">

            <!--<h1><?= Html::encode($this->title) ?></h1>-->

            <p>
                <?= Html::a(Yii::t('payment', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('payment', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                'confirm' => Yii::t('payment', 'Are you sure you want to delete this item?'),
                'method' => 'post',
                ],
                ]) ?>
            </p>

            <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                        'id',
            'payment_id',
            'status',
            'comment:ntext',
            'data:ntext',
            'created_by',
            'created_at',
            ],
            ]) ?>

        </div>
    </div><!--box-body pad-->
</div><!--box box-info-->

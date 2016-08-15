<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\payment\models\PaymentProgressSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('payment', 'Payment Progresses');
$this->params['breadcrumbs'][] = $this->title;
?>

    <div class='box box-info'>
        <div class='box-header'>
            <h3 class='box-title'><?= Html::encode($this->title) ?></h3>
        </div><!--box-header -->

        <div class='box-body pad'>
            <div class="payment-progress-index">
            
            <!--<h1><?= Html::encode($this->title) ?></h1>-->
                                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                        <p>
                <?= Html::a(Yii::t('payment', 'Create Payment Progress'), ['create'], ['class' => 'btn btn-success']) ?>
            </p>
                                        <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
        'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                            'id',
            'payment_id',
            'status',
            'comment:ntext',
            'data:ntext',
            // 'created_by',
            // 'created_at',

                ['class' => 'yii\grid\ActionColumn'],
                ],
                ]); ?>
                                </div>
    </div><!--box-body pad-->
</div><!--box box-info-->

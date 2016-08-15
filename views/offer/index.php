<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\payment\models\RepairOfferSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('payment', 'รายการเสนอนำใบส่ง');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class='box box-info'>
    <div class='box-header'>
        <h3 class='box-title'><?= Html::encode($this->title) ?></h3>
    </div><!--box-header -->

    <div class='box-body pad'>
        <div class="payment-index">


            <?php Pjax::begin(); ?>                           
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    //'created_at:datetime',
                    [
                        'attribute' => 'status',
                        'filter' => \backend\modules\payment\models\Payment::getItemStatus(),
                        'format' => 'html',
                        'value' => 'statusLabel'
                    ],
                    'paid_at:datetime',
                    // 'data:ntext',
                    // 'send_by_cash',
                    // 'send_by_transfer',
                    // 'send_cash_amount',
                    // 'send_transfer_amount',
                    // 'transfer_date',
                    // 'staff_id',
                    [
                        'content' => function($model) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span> ดูรายละเอียด', ['view', 'id' => $model->id], ['class' => 'btn btn-primary']);
                        },
                            ],
                        ],
                    ]);
                    ?>
                    <?php Pjax::end(); ?>        
        </div>
    </div><!--box-body pad-->
</div><!--box box-info-->

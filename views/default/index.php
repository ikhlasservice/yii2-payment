<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\payment\models\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('payment', 'รายการทั้งหมด');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class='box box-info'>
    <div class='box-header'>
        <h3 class='box-title'><?= Html::encode($this->title) ?></h3>
    </div><!--box-header -->

    <div class='box-body pad'>
        <?php
        Html::dropDownList(\backend\modules\payment\models\Payment::getItemStatus());
        ?>


        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'id',
                //'paid_at',
                [
                    'attribute' => 'paymentTotal',
                    'format' => ['decimal',2],
                    'value' => 'paymentTotal',
                    'contentOptions'=>['class'=>'text-right'],
                    
                ],
                [
                    'attribute' => 'seller.displayname',
//                    'filter' => \backend\modules\payment\models\Payment::getItemStatus(),
//                    'format' => 'html',
//                    'value' => 'statusLabel'
                    'visible'=>Yii::$app->user->can('staff')
                ],
                //'data:ntext',
                [
                    'attribute' => 'status',
                    'filter' => \backend\modules\payment\models\Payment::getItemStatus(),
                    'format' => 'html',
                    'value' => 'statusLabel'
                ],
                'created_at:datetime',
                [
                    'content' => function($model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span> ดูรายละเอียด', ['view', 'id' => $model->id], ['class' => 'btn btn-primary']);
                    },
                        ],
                    //['class' => 'yii\grid\ActionColumn'],
                    ],
                ]);
                ?>


    </div><!--box-body pad-->
</div><!--box box-info-->

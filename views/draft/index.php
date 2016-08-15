<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\payment\models\PaymentDraftSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('payment', 'ร่างใบนำส่ง');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class='box box-info'>
    <div class='box-header'>
        <h3 class='box-title'><?= Html::encode($this->title) ?></h3>
    </div><!--box-header -->

    <div class='box-body pad'>
        <div class="payment-index">

            <?php Pjax::begin(); ?>                            
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    'created_at:datetime',
                    // 'data:ntext',
                    // 'send_by_cash',
                    // 'send_by_transfer',
                    // 'send_cash_amount',
                    // 'send_transfer_amount',
                    // 'transfer_date',
                    // 'staff_id',
                    [
                        //'label'=>'',
                        'content' => function($model) {
                            return \backend\widgets\BtnGroup::widget([
                                        'header' => [
                                            'label' => '<span class="glyphicon glyphicon-pencil"></span> แก้ไข',
                                            'router' => ['/payment/default/create', 'id' => $model->id],
                                            'options' => ['class' => 'btn btn-danger'],
                                        ],
                                        'button' => [
                                            'options' => [
                                                'class' => 'btn btn-danger dropdown-toggle'
                                            ]
                                        ],
                                        'sub' => [
                                            [
                                                'label' => '<span class="glyphicon glyphicon-eye-open"></span> ดู',
                                                'router' => ['/credit/default/view', 'id' => $model->id]
                                            ],
                                            [
                                                'label' => '<span class="glyphicon glyphicon-trash"></span> ลบ',
                                                'router' => ['/credit/default/delete', 'id' => $model->id],
                                                'options' => [
                                                    'title' => "Delete",
                                                    'aria-label' => "Delete",
                                                    'data-confirm' => "Are you sure you want to delete this item?",
                                                    'data-method' => "post"
                                                ]
                                            ],
                                        ]
                            ]);
                        },
                                'visible' => Yii::$app->user->can('seller'),
                            ],
                        ],
                    ]);
                    ?>
        <?php Pjax::end(); ?>        </div>
    </div><!--box-body pad-->
</div><!--box box-info-->

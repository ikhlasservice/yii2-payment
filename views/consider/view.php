<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\payment\models\Payment */

$this->title = Yii::t('payment', 'เลขที่ใบนำส่ง {id}',['id'=>$model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('payment', 'Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?=$this->render('/default/detail',['model'=>$model])?>

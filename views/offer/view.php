<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model ikhlas\payment\models\Payment */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('payment', 'Payments'), 'url' => ['/payment']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('payment', 'เสนอใบนำส่ง'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?=$this->render('/default/detail',['model'=>$model])?>

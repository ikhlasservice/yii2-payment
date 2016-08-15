<?php

use yii\helpers\Html;
use yii\helpers\BaseStringHelper;
use yii\bootstrap\Modal;

/* @var $this \yii\web\View */
/* @var $content string */

$controller = $this->context;
//$menus = $controller->module->menus;
//$route = $controller->route;
?>
<?php $this->beginContent('@app/views/layouts/main.php') ?>

<div class="row">
    <div class="col-md-3 hidden-print">

        <?php if (Yii::$app->user->can('seller')): ?>
            <?= Html::a('<i class="fa fa-plus-circle"></i> ' . Yii::t('credit', 'สร้างใบนำส่ง'), ['/payment/default/create'], ['class' => 'btn btn-success btn-block margin-bottom create-credit']) ?>




            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        ระบบการชำระเงิน
                    </h3>

                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding">

                    <?php
                    $nav = new common\models\Navigate();
                    echo dmstr\widgets\Menu::widget([
                        'options' => ['class' => 'nav nav-pills nav-stacked'],
                        //'linkTemplate' =>'<a href="{url}">{icon} {label} {badge}</a>',
                        'items' => $nav->menu(7),
                    ])
                    ?>

                </div>
                <!-- /.box-body -->
            </div>
        <?php endif; ?>

        <?php
        if (Yii::$app->user->can('staff')):
            ?>
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        ระบบการชำระเงิน
                    </h3>

                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding">

                    <?php
                    $nav = new common\models\Navigate();
                    echo dmstr\widgets\Menu::widget([
                        'options' => ['class' => 'nav nav-pills nav-stacked'],
                        //'linkTemplate' =>'<a href="{url}">{icon} {label} {badge}</a>',
                        'items' => $nav->menu(7),
                    ])
                    ?>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /. box -->
        <?php endif; ?>


        <?php
        if (Yii::$app->user->can('finance')):
            ?>
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        ระบบการชำระเงิน
                    </h3>

                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding">

                    <?php
                    $nav = new common\models\Navigate();
                    echo dmstr\widgets\Menu::widget([
                        'options' => ['class' => 'nav nav-pills nav-stacked'],
                        //'linkTemplate' =>'<a href="{url}">{icon} {label} {badge}</a>',
                        'items' => $nav->menu(7),
                    ])
                    ?>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /. box -->
        <?php endif; ?>

    </div>
    <!-- /.col -->


    <div class="col-md-9">
        <?= $content ?>
        <!-- /. box -->
    </div>
    <!-- /.col -->


</div>

<?php $this->endContent(); ?>

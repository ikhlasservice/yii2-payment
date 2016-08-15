<?php

namespace backend\modules\payment;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\payment\controllers';

    public function init()
    {
        $this->layout = 'left-menu.php';
        parent::init();

        // custom initialization code goes here
    }
}

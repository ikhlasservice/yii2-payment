<?php

namespace ikhlas\payment;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'ikhlas\payment\controllers';

    public function init()
    {
        $this->layout = 'left-menu.php';
        parent::init();

        // custom initialization code goes here
    }
}

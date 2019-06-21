<?php

require_once '../vendor/autoload.php';

include '../Application/config.php';


use Application\Controllers\ApplicationController;

$app = new ApplicationController();
$app->Start();



<?php
error_reporting(1);

define ('MAIN_CONTROLLER', 'main');

include('controller.php');
include ('app.php');
include ('view.php');
var_dump($_GET);
new App;
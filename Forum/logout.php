<?php
require_once('inc/classes.php');
$atrz=new Authorization();
$atrz->LogOut();
$home_url='http://'.$_SERVER['HTTP_HOST'].'/forum/index.php?';
header('Location:'.$home_url);
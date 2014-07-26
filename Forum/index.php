<?php
session_start();
require_once('inc/classes.php');
echo $_SESSION['id_user'];
echo $_SESSION['username'];
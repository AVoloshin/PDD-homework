<?php
require_once ('inc/classes.php');
if(isset($_POST['submit'])){
    if(isset($_POST['username']) && isset($_POST['password'])){
        $username=$_POST['username'];
        $password=$_POST['password'];
        $atrz=new Authorization();
        $login=$atrz->LogIn($username,$password);
        if($login==true){
            $home_url='http://'.$_SERVER['HTTP_HOST'].'/forum/index.php?';
            header('Location:'.$home_url);
        }else{
            $home_url='http://'.$_SERVER['HTTP_HOST'].'/forum/tpl/form_login.html?';
            header('Location:'.$home_url);
        }
    }
}
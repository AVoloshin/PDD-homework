<?php
session_start();
require_once ('inc/classes.php');
if(isset ($_SESSION['id_user'])){
    $profile= new Profile();
    $arr_var=$profile->viewProfile($_SESSION['id_user']);
    $create_form= new CreateForm();
    $get_tpl=$create_form->getTPL('form_editprofile');
    $arr_lab=$create_form->arrayLabels();
    $process_tpl= new ProcessTPL();
    $html=$process_tpl->processTemplace($arr_lab,$arr_var,$get_tpl);

    if(isset($_POST['submit'])){
        if(isset($_POST['last_name'],$_POST['first_name'],$_POST['gender'],$_POST['date_birth'])){

            $update=$profile->editProfile($_SESSION['id_user'],$_POST['last_name'],$_POST['first_name'],$_POST['gender'],$_POST['date_birth']);
            $home_url='http://'.$_SERVER['HTTP_HOST'].'/forum/view_profile.php?';
            header('Location:'.$home_url);
        }
    }else{echo($html);}
}else{
    $home_url='http://'.$_SERVER['HTTP_HOST'].'/forum/tpl/form_login.html?';
    header('Location:'.$home_url);
    exit;
}
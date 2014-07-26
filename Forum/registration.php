<?php
session_start();
require_once ('inc/classes.php');
if(isset($_POST['submit'])){
    if(isset($_POST['username'])&& isset($_POST['password'])&& isset($_POST['repassword'])&& isset($_POST['captcha'])){

        $username=$_POST['username'];
        $password=$_POST['password'];
        $repassword=$_POST['repassword'];
        $captcha=trim(sha1($_POST['captcha']));

        $validation = new Validation();
        $valid_captcha = $validation->validCaptcha($captcha);
        $valid_name = $validation->validName($username);

        if($valid_name==1){
            $reg=new SignUp();
            $write_data=$reg->writeData($username,$password,$repassword);
            if($write_data==true &&  $valid_captcha==true){
                $home_url='http://'.$_SERVER['HTTP_HOST'].'/forum/index.php?';
                header('Location:'.$home_url);
                exit;
            }else{
                $home_url='http://'.$_SERVER['HTTP_HOST'].'/forum/tpl/form_registration.html?';
                header('Location:'.$home_url);
                exit;
            }
        }
    }
}
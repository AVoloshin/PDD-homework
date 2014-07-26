<?php
include('inc/crtForm.php');
include('inc/getListClass.php');
include('inc/pagClass.php');
include('inc/validateForm.php');
session_start();
if(isset($_GET['id'])){
    $post_topic=$_GET['id'];
    $html="<div class='posts'>";
    $getlist=new getList();
    $list=$getlist->getPostsList($post_topic);
    $postList=$getlist->readPostList($list);
    $html.=$postList."</div>";
    $html.=paginator::getPaginator($list);
    if(isset($_POST['submit'])){
        $validation=new validateForm();
        $errors = array(); // set the errors array to empty, by default
        $rules = array();
        $rules[] = "length>=10,textarea,Пожалуйста введите описание темы не менее 10 символов длинной.";
        $rules[] = "captcha,captcha,Введен неверный результат выражения.";
        $errors = $validation->validateFields($_POST, $rules);
        if(!empty($errors)){
            echo "<div class='error'>Пожалуйста исправьте следующие ошибки:\n<ul>";
            foreach ($errors as $error)
                echo "<li>$error</li>\n";
            echo "</ul></div>";
        }
        else{
            $dbase=new dbDataProcessing();
            $dbase->writeToDb('posts');

            $home_url=paginator::generateUrl($post_topic, $_GET['page'], '/forum/posts.php');
            header('Location:'.$home_url);
        }
    }
    $html.="<div class='form'>";
    $form=new myForm();
    $form->myTextArea('textarea','textarea',15,30);
    $form->genCaptcha();
    $form->crtInput('submit','submit','Отправить сообщение');
    $form->crtForm('POST');
    $html.=$form->form;
    $html.="</div>";
    echo $html;
    }

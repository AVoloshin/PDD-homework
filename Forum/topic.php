<?php
include('inc/crtForm.php');
include('inc/validateForm.php');
include('inc/pagClass.php');
include('inc/getListClass.php');

$html="<div class='topic'>";
$getlist=new getList();
$list=$getlist->getTopicsList();
$topiclist=$getlist->readTopicList($list);
$html.=$topiclist;"</div>";
$html.=paginator::getPaginator($list);
if(isset ($_POST['submit'])){
    $validation=new validateForm();
    $errors = array(); // set the errors array to empty, by default
    $rules = array();
    $rules[] = "required,topic_name,Пожалуйста, введите Ваше имя.";
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
        $id=$dbase->writeToDb('topics');
        var_dump($id);
        $home_url=paginator::generateUrl($id['id_topic'], $_GET['page'], '/forum/posts.php');
        header('Location:'.$home_url);
    }
}
if(isset ($_SESSION['id_user'])){
    $html.="<div class='form'>";
    $form=new myForm();
    $form->myInput('text','topic_name');
    $form->myTextArea('textarea','textarea',15,30);
    $form->genCaptcha();
    $form->crtInput('submit','submit','Создать тему');
    $form->crtForm('POST');
    $html.=$form->form;
    $html.="</div>";
    echo $html;
}
elseif(!(isset ($_SESSION['id_user']))){
    $html.="Для создания темы выполните <a href=login.php>вход</a> под своим логином или <a href=register.php>зарегестрируйтесь</a>!";
    echo $html;
}


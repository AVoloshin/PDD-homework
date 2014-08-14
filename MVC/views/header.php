<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Main / Index</title>
</head>
<body>
<div class="auth-block">
    <?php
        if($this->session->isLoggedIn()){
            echo $this->session->getName();
            echo '<a href="auth/logout">Выйти</a>';
        }
        else{
            echo '<a href="auth/login">Войти</a>';
        }
    ?>
</div>
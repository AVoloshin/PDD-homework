<?php
class View{

    public function __construct(){

    }

    public function includeView($name){
        require('views/header.php');
        require('views/'.$name.'.php');
        require('views/footer.php');
    }
}
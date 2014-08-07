<?php
class View{

    public function __construct(){

    }

    public function includeView($name){
        require_once('views/'.$name.'.php');
    }
}
<?php

class Controller {

    protected $view;
    protected $session;

    public function __construct(){

        $this->view=new View();
        $session=new SessionModel();
        $this->session=$session;
        $this->view->session=$this->session;
    }

    public function indexAction(){

        echo __CLASS__.'->'.__METHOD__.'<br/>';
    }

    public function redirect($url){
        header('Location:'.$url);
        die;
    }
}
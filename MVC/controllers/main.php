<?php

class MainController extends Controller{

    public function __construct(){
        parent::__construct();
        echo __CLASS__;
    }

    public function indexAction(){
        $this->view->includeView('main/index');
    }

    public function testAction ($param1=NULL, $param2=NULL){
        $this->view->param1=$param1;
        $this->view->param2=$param2;
        $this->view->includeView('main/test');
    }
}
<?php

class MainController extends Controller{

    public function __construct(){
        parent::__construct();
        echo __CLASS__;
    }

    public function indexAction(){
        $this->view->includeView('main/index');
    }
}
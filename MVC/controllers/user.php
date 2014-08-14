<?php

class UserController extends Controller{

    public function indexAction(){
        $this->view->users=UserModel::getUserList();
        $this->view->includeView('user/index');
    }
}
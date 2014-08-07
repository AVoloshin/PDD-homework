<?php

class UserController extends Controller{

    public function indexAction(){
        require_once('models/user.php');
        $this->view->users=UserModel::getUserList();
        $this->view->includeView('user/index');
    }
}
<?php
class AuthController extends Controller{

    public function loginAction(){
        if($this->session->isLoggedIn()){
            $this->redirect(APP_BASE_URL);
        }
        $this->view->msg;
        $this->view->login=isset($_POST['login'])?$_POST['login']:'';
        $this->view->password=isset($_POST['password'])?$_POST['password']:'';
        if(!empty($_POST)){
            if($this->session->login($this->view->login,$this->view->password)){
                $this->redirect(APP_BASE_URL);
            }
            else{
                $this->view->msg='Ошибка авторизации';
            }
        }
        $this->view->includeView('auth/login');
    }

    public function logoutAction(){
        $this->session->logout();
        $this->redirect(APP_BASE_URL);
    }
}
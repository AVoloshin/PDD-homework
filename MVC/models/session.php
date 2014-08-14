<?php
class SessionModel extends Model{

    protected $user;

    public function __construct (){

        session_start();
        $userId=isset($_SESSION['userId'])?$_SESSION['userId']:null;
        $this->user=UserModel::findBy(array('id'=>$userId));
    }

    public function isLoggedIn(){
        return isset($_SESSION['userId']);
    }

    public function getName(){
        return $this->isLoggedIn()?$_SESSION['username']:'';
    }

    public function login($login,$password){
        $this->user=UserModel::findBy(array('login'=>$login,'authkey'=>$password));
        return (bool) $this->user;
    }

    public function logout(){
        session_destroy();
        $this->user=null;
    }

    public function __destruct(){
        if($this->user){
            $_SESSION['userId']=$this->user->id;
            $_SESSION['username']=$this->user->name;
        }
    }


}
<?php
include('model.php');

class UserModel extends Model{

    public function __construct(){
        parent::_construct;
    }

    public static function getUserList(){
        $userList=array('Валера','Антоха','Володя','Иннокентий','Никита');
        return $userList;
    }
}
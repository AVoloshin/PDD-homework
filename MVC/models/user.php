<?php
class UserModel extends Model{

    protected static $userList = array(
        array('id'=> 1,'login'=>'admin', 'authkey'=>'admin', 'name'=>'Главный администратор'),
        array('id' => 2, 'login' => 'demo', 'authkey' => 'demo', 'name' => 'Демо пользователь'),
        array('id' => 3, 'login' => 'user', 'authkey' => 'user', 'name' => 'Пользователь, просто пользователь'),
        array('id' => 4, 'login' => 'test', 'authkey' => 'test', 'name' => 'Тестовый пользователь'),
        array('id' => 5, 'login' => 'root', 'authkey' => 'R00t', 'name' => 'Самый самый главный'),
    );

    public function __construct(array $properties=array()){
        parent::__construct();

        foreach ($properties as $name=>$val) {
            $this->$name=$val;
        }

    }

    public static function getUserList(){
        $arr=self::$userList;
        array_walk($arr,function (&$val,$key){
           unset($val['authkey']);
        });
        return $arr;
    }

    public static function findBy(array $condition){
        foreach (self::$userList as $uData) {
            $flag=true;
            foreach($condition as $key=>$val){
                if(!isset($uData[$key]) || $uData[$key]!=$val){
                    $flag=false;
                    break;
                }
            }
            if($flag==true){
                return new self($uData);
            }
        }
        return null;
    }
}
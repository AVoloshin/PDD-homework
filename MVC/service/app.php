<?php
class App{
    public function __construct(){
        $url=isset($_GET['url'])?trim($_GET['url']):MAIN_CONTROLLER;
        $urlParts=explode('/',rtrim($url,'/'));
        $actionParams=array_slice($urlParts, 2);

        $controllerName=$urlParts[0].'Controller';
        $controller=new $controllerName;

        $action=isset($urlParts[1])?$urlParts[1].'Action':'indexAction';

        if(method_exists($controller,$action)){
            call_user_func_array(array($controller,$action),$actionParams);
        }
        else{
            throw new Exception('Метод не найден');
        }
    }
}
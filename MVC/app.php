<?php
class App{
    public function __construct(){
        var_dump($_GET['url']);
        $url=isset($_GET['url'])?trim($_GET['url']):MAIN_CONTROLLER;
        var_dump($url);
        $urlParts=explode('/',rtrim($url,'/'));

        $controllerName=$urlParts[0].'Controller';
        $controllerFilePath=('controllers/'.$urlParts[0].'.php');

        if(file_exists($controllerFilePath)){
            include_once($controllerFilePath);
        }
        else{
            throw new Exception('Controller not found');
        }

        $controller=new $controllerName;

        if(isset($urlParts[1])){
            $action=$urlParts[1].'Action';
        }
        else{
            $action='indexAction';
        }

        if(method_exists($controller,$action)){
            $controller->$action();
        }
        else{
            throw new Exception('Метод не найден');
        }
    }
}
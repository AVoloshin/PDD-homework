<?php

spl_autoload_register(function ($class){
    $modelFlag=strpos($class, 'Model');
    if($modelFlag===FALSE){
       return FALSE;
    }
    if($modelFlag=='Model'){
        require_once('service/model.php');
        return true;
    }
    $modelName=str_replace('model', '', strtolower($class));
    $modelFile='models/'.$modelName.'.php';
    if(file_exists($modelFile)){
        require_once($modelFile);
        return true;
    }
});

spl_autoload_register(function($class){
    $ctrlFlag=strpos($class, 'Controller');
    if($ctrlFlag===FALSE){
        return FALSE;
    }
    if($ctrlFlag=='Controller'){
        require_once('service/controller.php');
    }
    $ctrlName=str_replace('controller', '', strtolower($class));
    $ctrlFile='controllers/'.$ctrlName.'.php';
    if(file_exists($ctrlFile)){
        require_once ($ctrlFile);
        return true;
    }
});

spl_autoload_register(function($class){
    $classFile='service/'.$class.'.php';
    if(file_exists($classFile)){
        require_once ($classFile);
        return TRUE;
    }
});
#!/usr/bin/php
<?php
    $user = isset($argv[1]) ? $argv[1] : 'Anonymous';
    $str1='';
     $str1.="$user купи слона \n";
     echo $str1;
$answer=trim(fgets(STDIN));
while ($answer !=='exit'){
    $str2="$user, все говорят $answer, a ты возьми и купи \n";
    echo $str2;
    $answer=trim(fgets(STDIN));
    if($answer==="exit"){
        exit;
    }
}

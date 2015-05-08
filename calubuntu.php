<?php
echo `clear`;
$html='';
date_default_timezone_set('Europe/Minsk');
$month=date('n');
$mname=date('F');
$year=date('Y');
$day1=date('w',mktime(0,0,0,$month,1,$year));
$today=date('j');
$mday=date('j',mktime(0,0,0,$month,1,$year));
for($i=0;$i<=23;$i++) {
    $html.= "-";
    }

$html.="\n|       "."$mname $year"."       |\n";
$html.="| Ïí Âò Ñð ×ò Ïò Ñá Âñ |\n|";
for($i=0;$i<($day1-1);$i++){
    $html.="   ";
}
for($i=0;$i<=(7-$day1);$i++){
    if($mday==$today){
        $html.="  \033[42 \033[31$mday";
    }
    else{
        $html.="  $mday";
    }
    $mday++;
}
$html.=" |\n|";
$k=0;
for($j=$mday;$j<=date('t');$j++){
    if($k<7) {
        $k++;
    }
    elseif($k=7){
        $html.=" |\n|";
        $k=0;
        $k++;
    }
    if($j==$today){
        if($j<10){
            $html.= " \033[42m \033[31m$j\033[0m";
        }
        elseif($j>=10){
            $html.= " \033[42m \033[31m$j\033[0m";
        }
    }
    elseif($j!=$today) {
        if($j < 10){
            $html .= "  $j";
        }
        elseif($j >= 10){
            $html .= " $j";
        }
    }
}
$html.=" |\n";
for($i=0;$i<=23;$i++) {
    $html.= "-";
}
$html.="\n";
echo $html;

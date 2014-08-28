<?php
class ModelToolMyExchange extends Model{

    public function unZip($fileName){
        if(file_exists($fileName)){

            $rar=rar_open($fileName);
            if($rar===false){
                die('Не удалось открыть архив');
            }
            $entries=rar_list($rar);

            if($entries===false){
                die('Файл в архиве не найден');
            }
            foreach($entries as $entry){
                $entry->extract(DIR_CACHE);
            }
            /*
            if($rez===false){
                die('Не удалось извлечь файл');
            };*/
            rar_close($rar);

            return true;
        }
    return false;
    }
    public function readToXml(){
        $fileName=DIR_CACHE.'webdata/import.xml';
        $xmlStr=file_get_contents($fileName);
        $xml=new SimpleXMLElement($xmlStr);

        return $xml;
    }

    /*private function selectObject(){
        
    }*/


}
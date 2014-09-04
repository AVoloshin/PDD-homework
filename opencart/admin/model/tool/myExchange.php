<?php
class ModelToolMyExchange extends Model{

    public function unZip($fileName){
        if(file_exists($fileName)){

            $rar=rar_open($fileName);
            if($rar===false){
                return false;
                echo 'Не удалось открыть архив';
            }
            $entries=rar_list($rar);

            if($entries===false){
                return false;
                echo 'Файл в архиве не найден';
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
    public function readToXML($file){
        $fileName=DIR_CACHE.'webdata/'.$file.'.xml';
        $xmlStr=file_get_contents($fileName);
        return $xmlStr;
    }

    public function createXML($xmlStr){
        $xml=new SimpleXMLElement($xmlStr);
        return $xml;
    }

    public function selectGoods($xml){
        $arrayGoods=array();
        $goods=$xml->xpath('Каталог/Товары/Товар');
        foreach($goods as $good){
            $images=array();
            if(count($good->{'Картинка'})>1){
                foreach($good->{'Картинка'} as $img){
                    $images[]=(string) $img;
                }
            }
            else $images=(string) $good->{'Картинка'};
            $array=array(
            'category'=>(string) $good->{'Группы'}->{'Ид'},
            'id'=>(string) $good->{'Ид'},
            'name'=>(string) $good->{'Наименование'},
            'art'=>(string) $good->{'Артикул'},
            'desc'=>(string) $good->{'Описание'},
            'img'=>$images
            );
            $arrayGoods[]=$array;
        }
        return $arrayGoods;
    }

    public function selectPrices($xml){
        $arrayGoods=array();
        $goods=$xml->xpath('ПакетПредложений/Предложения/Предложение');

        foreach($goods as $good){
            $prices=array();
            foreach($good->{'Цены'}->{'Цена'}->{'ЦенаЗаЕдиницу'} as $price){
                $prices[]=(string) $price;
            }
            $array=array(
                'id'=>(string)$good->{'Ид'},
                'quantity'=>(int)$good->{'Количество'},
                'price'=>$prices[0]
            );
            $arrayGoods[]=$array;
        }
        return $arrayGoods;
    }

    /**
     * @param $file - имя файла для парсинга ('import' или 'offers')
     * @return array - архив с данными о товаре
     */
    public function processXML($file){
        $xmlStr=$this->readToXML($file);
        $xml=$this->createXML($xmlStr);
        if($file=='import'){
            $array=$this->selectGoods($xml);
        }
        elseif($file=='offers'){
            $array=$this->selectPrices($xml);
        }
        return $array;
    }

    public function setGoodInfo(){
        $goods=array();
        $import=$this->processXML('import');
        $offers=$this->processXML('offers');
        for($i=0;$i<count($offers);$i++){
            if($import[$i]['id']===$offers[$i]['id']){
                $goods[]=array_merge($import[$i],$offers[$i]);
            }
            elseif($import[$i+1]['id']===$offers[$i]['id']) {
                $goods[]=array_merge($import[$i+1],$offers[$i]);
            }
        }
        return $goods;
    }

    public function PDO_connect($db,$host,$user,$password){
        try{
            $db=new PDO("mysql:host=$host;dbname=$db", $user,$password);
            $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $db->exec("set names utf8");
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }

        return $db;
    }

    public function importToDb(PDO $db, $goods){
        foreach($goods as $good){
            $stmt=$db->prepare("INSERT INTO oc_product (model,quantity,sku,upc,ean,jan,isbn,mpn,location,manufacturer_id,tax_class_id,stock_status_id,image,price,date_available)
            VALUES(:model,:quantity,:sku,:upc,:ean,:jan,:isbn,:mpn,:location,:manufacturer_id,:tax_class_id,:stock_status_id,:image,:price,:date_available)");

            $sku=$upc=$ean=$jan=$isbn=$mpn=$location='';
            $manufacturer_id=$tax_class_id=0;
            $stmt->bindParam(':model',$good['art']);
            $stmt->bindParam(':sku',$sku);
            $stmt->bindParam(':upc',$upc);
            $stmt->bindParam(':ean',$ean);
            $stmt->bindParam(':jan',$jan);
            $stmt->bindParam(':isbn',$isbn);
            $stmt->bindParam(':mpn',$mpn);
            $stmt->bindParam(':location',$location);
            $stmt->bindParam(':manufacturer_id',$manufacturer_id);
            $stmt->bindParam(':tax_class_id',$tax_class_id);

            $qnt=isset($good['quantity'])?$good['quantity']:1;
            $stmt->bindParam(':quantity',$qnt);

            $stock_status_id=isset($good['stock_status_id'])?$good['stock_status_id']:7;
            $stmt->bindParam(':stock_status_id',$stock_status_id);

            if(count($good['img'])<=1){
                $stmt->bindParam(':image',$good['img']);
            }
            else $stmt->bindParam(':image',$good['img'][0]);

            $price=isset($good['price'])?$good['price']:0;
            $stmt->bindParam(':price',$price);

            $date=date("Y-m-d H:i:s");
            $stmt->bindParam(':date_available',$date);

            $stmt->execute();
        }
        $db=null;
        return true;

    }

    public function processDb($goods){
        $db=$this->PDO_connect('opencart','localhost','root','7233992');
        if($this->importToDb($db,$goods)){
            $success='Успешно';
        }
        else $success='Не удалось';
        return $success;
    }

}
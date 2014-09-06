<?php
class ModelToolMyExchange extends Model{

    public function unRar($fileName){
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
            rar_close($rar);

            return true;
        }
        return false;
    }
    public function unZip($filename){
        $zip=new ZipArchive();
        $res=$zip->open($filename);
        if($res===TRUE){
            $zip->extractTo(DIR_CACHE);
            $zip->close();
        }
        else {
            $error='Ошибка. Код:'.$res;
            return $error;
        }
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
                    $images[]= DIR_CACHE.'webdata/'.(string) $img;
                }
            }
            else $images= DIR_CACHE.'webdata/'.(string) $good->{'Картинка'};

            foreach($good->{'ЗначенияРеквизитов'}->{'ЗначениеРеквизита'} as $value){
                if((string) $value->{'Наименование'}==='ВидНоменклатуры'){
                    $category=(string) $value->{'Значение'};
                }
            }

            $array=array(
            'category_name'=>$category,
            'category'=>(string) $good->{'Группы'}->{'Ид'},
            'id'=>(string) $good->{'Ид'},
            'name'=>(string) $good->{'Наименование'},
            'model'=>(string) $good->{'Артикул'},
            'desc'=>(string) $good->{'Описание'},
            'img'=>$images
            );
            $arrayGoods[]=$array;
        }
        return $arrayGoods;
    }

    public function selectCategories($xml){
        $arrayCategories=array();
        $cats=$xml->xpath('Классификатор/Группы/Группа');

        foreach($cats as $category){
            $array=array(
                'category'=>(string) $category->{'Наименование'},
                'id'=>(string) $category->{'Ид'}
            );
            $arrayCategories[]=$array;
        }
        return $arrayCategories;
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

    public function getAllData(){
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

    public function productData($goods){

        unset ($goods['categories']);
        $allGoods=array();

        foreach($goods as $good){

            $data=array(
                'model'=>$good['model'],
                'sku'=>'',
                'upc'=>'',
                'ean'=>'',
                'jan'=>'',
                'isbn'=>'',
                'mpn'=>'',
                'location'=>'',
                'quantity'=>$good['quantity'],
                'stock_status_id'=>1,
                'name'=>$good['name'],
                'manufacturer_id'=>0,
                'price'=>$good['price'],
                'tax_class_id'=>7,
                'date_available'=>date("Y-m-d H:i:s"),
                'status'=>'',
                'product_description'=>$good['desc'],
                'store_id'=>0,
                'product_category'=>$good['category_name']
            );
            if(count($good['img'])<=1){
                $data['image']=$good['img'];
            }
            else {
                $images=array_slice($good['img'],1);
                $data['product_image']=$images;
            }
            $allGoods[]=$data;
        }
        return $allGoods;
    }

    public function categoryData($arrayCategories){
        $allCats=array();

        foreach($arrayCategories as $category){
            $data=array(
                'top'=>'',
                'column'=>0,
                'status'=>1,
                'id'=>$category['id'],
                'category'=>$category['category'],
                'parent_id'=>0

            );
            $allCats[]=$data;
        }
        return $allCats;
    }

    public function addProduct($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', price = '" . (float)$data['price'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "', date_added = NOW()");

        $product_id = $this->db->getLastId();
        $language_id = 1;

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE product_id = '" . (int)$product_id . "'");
        }

        $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($data['name']) . "', meta_keyword = '', meta_description = '', description = '" . $this->db->escape($data['product_description']) . "', tag = '', seo_title = '', seo_h1 = ''");

        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$data['store_id'] . "'");

        if (isset($data['product_image'])) {
            $sort_order=0;
            foreach ($data['product_image'] as $product_image) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(html_entity_decode($product_image, ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$sort_order . "'");
                $sort_order++;
            }
        }

        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = (SELECT t1.category_id FROM " . DB_PREFIX . "category t1 LEFT JOIN " . DB_PREFIX . "category_description t2 ON (t1.category_id=t2.category_id) WHERE t2.`name`='".$data['product_category']."')");

        $this->cache->delete('product');
    }

    public function addCategory($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "category SET `top` = '" . (isset($data['top']) ? (int)$data['top'] : 1) . "', `column` = '" . (int)$data['column'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");

        $category_id = $this->db->getLastId();

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE category_id = '" . (int)$category_id . "'");
        }

        $language_id = 1;

        $this->db->query("INSERT INTO `" . DB_PREFIX . "category_description` SET `category_id` = '" . (int)$category_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $data['category'] . "', `meta_keyword` = '', `meta_description` = '', `description` = '', `seo_title` = '', `seo_h1` = ''");

        $level = 0;

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY `level` ASC");

        foreach ($query->rows as $result) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");
            $level++;
        }

        $this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', `level` = '" . (int)$level . "'");

        $store_id = 0;

        $this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");

    }

    public function clearDb(){
        $this->db->query("DELETE t1, t2, t3, t4, t5, t6, t7, t8, t9, t10, t11, t12, t13, t14, t15 FROM " . DB_PREFIX . "product t1
            LEFT JOIN " . DB_PREFIX . "product_attribute t2 ON(t1.product_id=t2.product_id)
            LEFT JOIN " . DB_PREFIX . "product_description t3 ON(t1.product_id=t3.product_id)
            LEFT JOIN " . DB_PREFIX . "product_discount t4 ON(t1.product_id=t4.product_id)
            LEFT JOIN " . DB_PREFIX . "product_image t5 ON(t1.product_id=t5.product_id)
            LEFT JOIN " . DB_PREFIX . "product_option t6 ON(t1.product_id=t6.product_id)
            LEFT JOIN " . DB_PREFIX . "product_option_value t7 ON(t1.product_id=t7.product_id)
            LEFT JOIN " . DB_PREFIX . "product_related t8 ON(t1.product_id=t8.product_id)
            LEFT JOIN " . DB_PREFIX . "product_reward t9 ON(t1.product_id=t9.product_id)
            LEFT JOIN " . DB_PREFIX . "product_special t10 ON(t1.product_id=t10.product_id)
            LEFT JOIN " . DB_PREFIX . "product_to_category t11 ON(t1.product_id=t11.product_id)
            LEFT JOIN " . DB_PREFIX . "product_to_download t12 ON(t1.product_id=t12.product_id)
            LEFT JOIN " . DB_PREFIX . "product_to_layout t13 ON(t1.product_id=t13.product_id)
            LEFT JOIN " . DB_PREFIX . "product_to_store t14 ON(t1.product_id=t14.product_id)
            LEFT JOIN " . DB_PREFIX . "review t15 ON(t1.product_id=t15.product_id)
        ");

        $this->db->query("DELETE t1, t2, t3, t4 FROM " . DB_PREFIX . "category t1
        LEFT JOIN ". DB_PREFIX . "category_description t2 ON (t1.category_id=t2.category_id)
        LEFT JOIN ". DB_PREFIX . "category_path t3 ON (t1.category_id=t3.category_id)
        LEFT JOIN ". DB_PREFIX . "category_to_store t4 ON (t1.category_id=t4.category_id)");
        //WHERE  t1.`category_id`=58
    }

}
<?php
class ControllerModule1CExchange extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('module/1CExchange');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()){

            if(move_uploaded_file($_FILES['uploadfile']['tmp_name'], DIR_IMAGE.$_FILES['uploadfile']['name'])) {
                $this->model_setting_setting->editSetting('1CExchange', $this->request->post);
                $this->load->model('tool/1CExchange'); //загружаем модель

                $file=DIR_IMAGE.$_FILES['uploadfile']['name'];
                $this->res=$this->load->model_tool_1CExchange->unZip($file); //распаковываем загруженный файл
                if($this->res!==TRUE){
                    $this->data['zip_error']=$this->res;
                }

                $this->xmlstr=$this->load->model_tool_1CExchange->readToXML('import');          //
                $this->xml=$this->load->model_tool_1CExchange->createXML($this->xmlstr);        //собираем данные
                $this->cats=$this->load->model_tool_1CExchange->selectCategories($this->xml);   //о категориях
                $this->catData=$this->load->model_tool_1CExchange->categoryData($this->cats);   //
                $this->goods=$this->load->model_tool_1CExchange->getAllData();
                $this->allGoods=$this->load->model_tool_1CExchange->productData($this->goods);  //собираем данные о товарах

                if(isset ($_POST['cleardb'])){                                                  //проверяем, надо ли очищать БД
                    $this->load->model_tool_1CExchange->clearDb();

                    foreach ($this->catData as $category) {
                        $this->load->model_tool_1CExchange->addCategory($category);             //очищаем и добавляем категории и товары
                    }                                                                           //

                    foreach($this->allGoods as $good){
                       $this->load->model_tool_1CExchange->addProduct($good);
                    }
                }
                else {
                    foreach($this->catData as $category){                                       //если не надо очищать - обновляем данные о товарах и категориях
                        $flag=$this->load->model_tool_1CExchange->selectCategory($category);    //если такие уже есть в БД, или добавляем новые
                        if($flag==true){                                                        //
                            $this->load->model_tool_1CExchange->updateCategory($category);
                        }
                        elseif($flag==false){
                            $this->load->model_tool_1CExchange->addCategory($category);
                        }
                    }

                    foreach($this->allGoods as $good){
                        $flag=$this->load->model_tool_1CExchange->selectProduct($good);
                        if($flag==true){
                            $this->load->model_tool_1CExchange->updateProduct($good);
                        }
                        elseif($flag==false){
                            $this->load->model_tool_1CExchange->addProduct($good);
                        }
                    }
                }

                $this->data['text_complete']=$this->language->get('text_complete');
                $this->session->data['success'] = $this->language->get('text_success');

            }
        }

        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['entry_upload'] = $this->language->get('entry_upload');
        $this->data['entry_checkbox'] = $this->language->get('entry_checkbox');
        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        $this->breadcrumbs();

        $this->data['action'] = $this->url->link('module/1CExchange', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['modules'] = array();

        if (isset($this->request->post['1CExchange_module'])) {
            $this->data['modules'] = $this->request->post['1CExchange_module'];
        } elseif ($this->config->get('1CExchange_module')) {
            $this->data['modules'] = $this->config->get('1CExchange_module');
        }

        $this->load->model('design/layout');
        $this->data['layouts'] = $this->model_design_layout->getLayouts();

        $this->template = 'module/1CExchange.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
        $this->response->setOutput($this->render());
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'module/1CExchange')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    private function breadcrumbs(){
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_module'),
            'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('module/1CExchange', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );
    }
}
?>
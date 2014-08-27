<?php
class ControllerModuleMyExchange extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('module/myExchange');
        $this->document->setTitle($this->language->get('heading_title'));
        /*$this->load->model('setting/setting');*/

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()){
        if(move_uploaded_file($_FILES['uploadfile']['tmp_name'], DIR_DOWNLOAD.$_FILES['uploadfile']['name'])) {
            /*$this->model_setting_setting->editSetting('myExchange', $this->request->post);*/

            $this->session->data['success'] = $this->language->get('text_success');
            $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));


        }
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['entry_upload'] = $this->language->get('entry_upload');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');
        $this->data ['vdump'] = var_dump($_FILES);

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

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
            'href'      => $this->url->link('module/myExchange', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['action'] = $this->url->link('module/myExchange', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['modules'] = array();

        if (isset($this->request->post['myExchange_module'])) {
            $this->data['modules'] = $this->request->post['myExchange_module'];
        } elseif ($this->config->get('myExchange_module')) {
            $this->data['modules'] = $this->config->get('myExchange_module');
        }
        $this->load->model('design/layout');

        $this->data['layouts'] = $this->model_design_layout->getLayouts();

        $this->template = 'module/myExchange.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
        $this->response->setOutput($this->render());
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'module/myExchange')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}
?>
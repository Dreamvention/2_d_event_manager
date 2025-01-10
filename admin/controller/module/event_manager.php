<?php
namespace Opencart\Admin\Controller\Extension\EventManager\Module;

class EventManager extends \Opencart\System\Engine\Controller {

    private $codename = 'event_manager';
    private $route = 'extension/event_manager/module/event_manager';
    private $route_event = 'extension/event_manager/event/event_manager';
    private $config_file = 'event_manager';
    private $extension = array();
    private $store_id = 0;
    private $error = array();

    public function __construct($registry) {
        parent::__construct($registry);
        $this->store_id = (isset($this->request->get['store_id'])) ? $this->request->get['store_id'] : 0;

        $this->load->model($this->route);
        $this->load->language($this->route);
        $this->load->model('setting/event');
    }

    public function index() {

        $this->config->load('../../extension/event_manager/system/config/event_manager');
        $this->load->model('setting/setting');
        $this->load->model('setting/store');

        $user_token = '&user_token=' . $this->session->data['user_token'];

        //save post
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $this->model_setting_setting->editSetting($this->codename, $this->request->post, $this->store_id);
            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', $user_token, true));
        }

        // styles and scripts
        $this->document->addScript(HTTP_CATALOG. 'extension/event_manager/admin/view/javascript/d_bootstrap_tagsinput/bootstrap-tagsinput.js');
        $this->document->addStyle(HTTP_CATALOG. 'extension/event_manager/admin/view/javascript/d_bootstrap_tagsinput/bootstrap-tagsinput.css');

        $url_params = array();
        $url = '';

        if(isset($this->response->get['store_id'])){
            $url_params['store_id'] = $this->store_id;
        }

        if(isset($this->response->get['config'])){
            $url_params['config'] = $this->response->get['config'];
        }

        //Customer
        if (isset($this->request->get['filter_code'])) {
            $url_params['filter_code'] = urlencode(html_entity_decode($this->request->get['filter_code'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_trigger'])) {
            $url_params['filter_trigger'] = urlencode(html_entity_decode($this->request->get['filter_trigger'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_action'])) {
            $url_params['filter_action'] = urlencode(html_entity_decode($this->request->get['filter_action'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_status'])) {
            $url_params['filter_status'] = $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_date_added'])) {
            $url_params['filter_date_added'] = $this->request->get['filter_date_added'];
        }

        if (isset($this->request->get['sort'])) {
            $url_params['sort'] = $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url_params['order'] = $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url_params['page'] = $this->request->get['page'];
        }

        $url = ((!empty($url_params)) ? '&' : '' ) . http_build_query($url_params);

        $url_token = '';

		if (isset($this->session->data['token'])) {
			$url_token .=  'token=' . $this->session->data['token'];
		}

		if (isset($this->session->data['user_token'])) {
			$url_token .=  'user_token=' . $this->session->data['user_token'];
		}

        // Heading
        $this->document->setTitle($this->language->get('heading_title_main'));
        $data['heading_title'] = $this->language->get('heading_title_main');
        $data['text_edit'] = $this->language->get('text_edit');

        // Setup
        $data['setup'] = $this->isSetup();
        $data['background_image'] = HTTP_CATALOG . "extension/event_manager/admin/view/image/event_manager/bg.svg";
        $data['icon_image'] = HTTP_CATALOG . "extension/event_manager/admin/view/image/event_manager/icon.svg";
        $data['setup_link'] = $this->url->link($this->route.'|setup'.$user_token, '', true);
        $data['title_setup'] = $this->language->get('title_setup');
        $data['text_setup'] = $this->language->get('text_setup');

        // Variable
        $data['codename'] = $this->codename;
        $data['route'] = $this->route;
        $data['token'] =  $this->session->data['user_token'];

        // Customer
        $data['tab_event'] = $this->language->get('tab_event');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_code'] = $this->language->get('column_code');
        $data['column_trigger'] = $this->language->get('column_trigger');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_sort_order'] = $this->language->get('column_sort_order');

        $data['entry_code'] = $this->language->get('entry_code');
        $data['entry_trigger'] = $this->language->get('entry_trigger');
        $data['entry_action'] = $this->language->get('entry_action');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_date_added'] = $this->language->get('entry_date_added');

        $data['button_enable'] = $this->language->get('button_enable');
        $data['button_disable'] = $this->language->get('button_disable');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');

        // Tab
        $data['tab_setting'] = $this->language->get('tab_setting');

        // Button
        $data['button_save'] = $this->language->get('button_save');
        $data['button_save_and_stay'] = $this->language->get('button_save_and_stay');
        $data['button_create'] = $this->language->get('button_create');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_clear'] = $this->language->get('button_clear');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_remove'] = $this->language->get('button_remove');

        // Entry
        $data['entry_compatibility'] = $this->language->get('entry_compatibility');
        $data['entry_skipped_models'] = $this->language->get('entry_skipped_models');
        $data['help_skipped_models'] = $this->language->get('help_skipped_models');
        $data['entry_test_toggle'] = $this->language->get('entry_test_toggle');
        $data['entry_test'] = $this->language->get('entry_test');
        $data['text_install'] = $this->language->get('text_install');
        $data['text_uninstall'] = $this->language->get('text_uninstall');

        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_config_files'] = $this->language->get('entry_config_files');
        $data['entry_select'] = $this->language->get('entry_select');
        $data['entry_text'] = $this->language->get('entry_text');
        $data['entry_radio'] = $this->language->get('entry_radio');
        $data['entry_checkbox'] = $this->language->get('entry_checkbox');
        $data['entry_color'] = $this->language->get('entry_color');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_textarea'] = $this->language->get('entry_textarea');

        // Text
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        //field
        $data['entry_field'] = $this->language->get('entry_field');
        $data['entry_type'] = $this->language->get('entry_type');
        $data['text_field_1'] = $this->language->get('text_field_1');
        $data['text_field_2'] = $this->language->get('text_field_2');
        $data['text_field_3'] = $this->language->get('text_field_3');
        $data['setup_link'] = $this->url->link($this->route.'|setup'.$user_token, '', true);
        //action
        $data['module_link'] = $this->url->link($this->route, $url_token, true);
		$data['action'] = $this->url->link($this->route, $url_token, true);
        $data['create'] = $this->url->link($this->route.'|create'.$user_token, '', true);
        $data['delete'] = $this->url->link($this->route.'|delete'.$user_token, '', true);
        $data['install_test'] = $this->url->link($this->route.'|install_test'.$user_token, '', true);
        $data['uninstall_test'] = $this->url->link($this->route.'|uninstall_test'.$user_token, '', true);
        $data['autocomplete'] = $this->url->link($this->route.'|autocomplete'.$user_token, '', true);
        $data['cancel'] = $this->url->link('marketplace/extension', $user_token, true);

        //instruction
        $data['tab_instruction'] = $this->language->get('tab_instruction');
        $data['text_instruction'] = $this->language->get('text_instruction');


        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }

        if (isset($this->session->data['error'])) {
            $data['error']['warning'] = $this->session->data['error'];
            unset($this->session->data['error']);
        }

        if (isset($this->request->post[$this->codename.'_status'])) {
            $data[$this->codename.'_status'] = $this->request->post[$this->codename.'_status'];
        } else {
            $data[$this->codename.'_status'] = $this->config->get($this->codename.'_status');
        }

        //get store
        $data['store_id'] = $this->store_id;
        $data['stores'] = $this->model_setting_store->getStores();

        //get setting
        $setting = $this->model_setting_setting->getSetting($this->codename, $this->store_id);
        if($setting){
            $data['setting'] = $setting['event_manager_setting'] + $this->config->get('event_manager_setting');
        }else{
            $data['setting'] = $this->config->get('event_manager_setting');
        }

        $data['conflict_models'] = false;

        $data['compatibility_required'] = false;

        $data['tests'] = array();

        if($this->config->get('event_manager')){
            $data['tests'] = array(
                'controller_before' => false,
                'controller_after' => false,
                'model_before' => false,
                'model_after' => false,
                'view_before' => false,
                'view_after' => false,
                'language_before' => false,
                'language_after' => false);
            $data['tests'] = array_merge($data['tests'], $this->config->get('event_manager'));
        }

        $filter_code = (isset($this->request->get['filter_code'])) ? $this->request->get['filter_code'] : null;
        $filter_trigger = (isset($this->request->get['filter_trigger'])) ? $this->request->get['filter_trigger'] : null;
        $filter_action = (isset($this->request->get['filter_action'])) ? $this->request->get['filter_action'] : null;
        $filter_status = (isset($this->request->get['filter_status'])) ? $this->request->get['filter_status'] : null;
        $filter_date_added = (isset($this->request->get['filter_date_added'])) ? $this->request->get['filter_date_added'] : null;

        $sort = (isset($this->request->get['sort'])) ? $this->request->get['sort'] : '';
        $order = (isset($this->request->get['order'])) ? $this->request->get['order'] : '';
        $page = (isset($this->request->get['page'])) ? $this->request->get['page'] : 1;

        $data['events'] = array();

        $filter_data = array(
            'filter_code'               => $filter_code,
            'filter_trigger'            => $filter_trigger,
            'filter_action'             => $filter_action,
            'filter_status'             => $filter_status,
            'filter_date_added'         => $filter_date_added,
            'sort'                      => $sort,
            'order'                     => $order,
            'start'                     => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'                     => $this->config->get('config_limit_admin')
        );

        $event_total = $this->model_extension_event_manager_module_event_manager->getTotalEvents($filter_data);

        $results = $this->model_extension_event_manager_module_event_manager->getEvents($filter_data);

        foreach ($results as $result) {

            $enable = $this->url->link($this->route.'|enable'.$user_token,'event_id=' . $result['event_id'] . $url);
            $disable = $this->url->link($this->route.'|disable'.$user_token, 'event_id=' . $result['event_id'] . $url);

            $data['events'][] = array(
                'event_id'       => $result['event_id'],
                'code'           => $result['code'],
                'trigger'        => $result['trigger'],
                'action'         => $result['action'],
                'sort_order'     => $result['sort_order'],
                'status'         => (isset($result['status'])) ? $result['status'] : 1,
                'enable'         => $enable,
                'disable'        => $disable,
                'edit'           => $this->url->link($this->route.'|edit'.$user_token, 'event_id=' . $result['event_id'] . $url)
            );
        }

        //sort
        if($sort){
            if ($order == 'ASC') {
                $url_params['order'] = 'DESC';
            } else {
                $url_params['order'] = 'ASC';
            }
        }
        unset($url_params['sort']);
        $url = ((!empty($url_params)) ? '&' : '' ) . http_build_query($url_params);
        $data['sort_code'] = $this->url->link($this->route, 'sort=code' . $url);
        $data['sort_trigger'] = $this->url->link($this->route, 'sort=sort_trigger' . $url);
        $data['sort_action'] = $this->url->link($this->route, 'sort=sort_action' . $url);
        $data['sort_status'] = $this->url->link($this->route, 'sort=sort_status' . $url);
        $data['sort_sort_order'] = $this->url->link($this->route, 'sort=sort_sort_order' . $url);
        $data['sort_date_added'] = $this->url->link($this->route, 'sort=date_added' . $url);

        //pagination
        if (isset($this->request->get['sort'])) {
            $url_params['sort'] = $this->request->get['sort'];
        }
        if (isset($this->request->get['order'])) {
            $url_params['order'] = $this->request->get['order'];
        }
        unset($url_params['page']);
        $url = ((!empty($url_params)) ? '&' : '' ) . http_build_query($url_params);

        $pagination_setting = [];
        $pagination_setting['total'] = $event_total;
        $pagination_setting['page'] = $page;
        $pagination_setting['limit'] = $this->config->get('config_limit_admin');
        $pagination_setting['url'] =  $this->url->link($this->route.$user_token,  $url . '&page={page}');
        $data['pagination'] = $this->load->controller('common/pagination', $pagination_setting);

        $data['filter_code'] = $filter_code;
        $data['filter_trigger'] = $filter_trigger;
        $data['filter_action'] = $filter_action;
        $data['filter_status'] = $filter_status;
        $data['filter_date_added'] = $filter_date_added;

        $data['sort'] = $sort;
        $data['order'] = $order;

         // Breadcrumbs
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', $user_token)
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_module'),
            'href'      => $this->url->link('marketplace/extension', $user_token)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_main'),
            'href' => $this->url->link($this->route, $user_token)
        );

        // Notification
        foreach($this->error as $key => $error){
            $data['error'][$key] = $error;
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view($this->route, $data));
    }

    public function isSetup() {
        $setting_module = $this->model_setting_setting->getSetting('module_' . $this->codename);

    	if($setting_module) {
        	return true;
        }

    	return false;
    }

    public function setup() {

        $this->model_extension_event_manager_module_event_manager->installDatabase();

        $data['module_'.$this->codename.'_status'] = 1;
        $this->model_setting_setting->editSetting('module_' . $this->codename, $data);

        $this->session->data['success'] = $this->language->get('success_setup');
        $this->response->redirect($this->url->link($this->route, '&user_token=' . $this->session->data['user_token']));
    }

    private function validate($permission = 'modify') {

        if (isset($this->request->post['config'])) {
            return false;
        }

        $this->load->language($this->route);

        if (!$this->user->hasPermission($permission, $this->route)) {
            $this->error['warning'] = $this->language->get('error_permission');
            return false;
        }

        return true;
    }

    public function enable() {

        if(!$this->validate()){
            return false;
        }

        $event_id = false;
        $event = array();

        if(isset($this->request->get['event_id'])){
            $event_id = $this->request->get['event_id'];
            $event = $this->model_setting_event->getEvent($event_id);
        }

        if($event){
            $result = $this->model_extension_event_manager_module_event_manager->enableEvent($event_id);
            $json['enabled'] = true;


        }else{
            $json['enabled'] = false;
        }


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function disable() {
        if(!$this->validate()){
            return false;
        }

        $event_id = false;
        $event = array();

        if(isset($this->request->get['event_id'])){
            $event_id = $this->request->get['event_id'];
            $event = $this->model_setting_event->getEvent($event_id);
        }

        if($event){
            $result = $this->model_extension_event_manager_module_event_manager->disableEvent($event_id);
            $json['enabled'] = false;


        }else{
            $json['enabled'] = ture;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function edit(){

        $event_id = false;
        $result = array();
        $user_token = '&user_token=' . $this->session->data['user_token'];

        if(isset($this->request->get['event_id'])){
            $event_id = $this->request->get['event_id'];
            $result = $this->model_setting_event->getEvent($event_id);
        }

        if($result){
            $enable = $this->url->link($this->route.'|enable', 'event_id=' . $result['event_id']);
            $disable = $this->url->link($this->route.'|disable','event_id=' . $result['event_id']);

            $json = array(
                'event_id'      => $result['event_id'],
                'code'          => $result['code'],
                'trigger'       => $result['trigger'],
                'action'        => $result['action'],
                'status'        => $result['status'],
                'sort_order'    => $result['sort_order'],
                'enable'        => $enable,
                'disable'       => $disable,
                'edit'          => $this->url->link($this->route.'|edit', 'event_id=' . $result['event_id']),
                'save'          => $this->url->link($this->route.'|save'.$user_token, 'event_id=' . $result['event_id'])
            );
        }else{
            $json = false;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function create(){

        if(!$this->validate()){
            return false;
        }

        $event_id = false;
        $event = array();

        if( isset($this->request->post['code'])
        && isset($this->request->post['trigger'])
        && isset($this->request->post['action'])){

            $event['code'] = $this->request->post['code'];
            $event['trigger'] = $this->request->post['trigger'];
            $event['action'] = $this->request->post['action'];
            $event['status'] = 1;
            $event['sort_order'] = 0;
            $event['event_id'] = $this->model_extension_event_manager_module_event_manager->addEvent($event['code'], '', $event['trigger'], $event['action'] , $event['status'], $event['sort_order']);
        }

        if($event){
            $enable = $this->url->link($this->route.'|enable', 'event_id=' . $event['event_id']);
            $disable = $this->url->link($this->route.'|disable', 'event_id=' . $event['event_id']);

            $json = array(
                'event_id'      => $event['event_id'],
                'code'          => $event['code'],
                'trigger'       => $event['trigger'],
                'action'        => $event['action'],
                'status'        => $event['status'],
                'sort_order'    => $event['sort_order'],
                'enable'        => $enable,
                'disable'       => $disable,
                'edit'          => $this->url->link($this->route.'|edit', 'event_id=' . $event['event_id']),
                'save'          => $this->url->link($this->route.'|save', 'event_id=' . $event['event_id'])
            );

            $json['saved'] = true;
        }else{
            $json['saved'] = false;
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function delete(){
        if(!$this->validate()){
            return false;
        }

        $json = array();

        if( isset($this->request->post['event_id'])){
            foreach($this->request->post['event_id'] as $event_id){
                $this->model_setting_event->deleteEvent($event_id);
            }
            $json['deleted'] = true;
        }else{
            $json['deleted'] = false;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function save(){

        if(!$this->validate()){
            return false;
        }

        $event_id = false;
        $event = array();

        if(isset($this->request->get['event_id'])
        && isset($this->request->post['code'])
        && isset($this->request->post['trigger'])
        && isset($this->request->post['action'])){
            $event_id = $this->request->get['event_id'];
            $event = $this->model_setting_event->getEvent($event_id);
            $event['code'] = $this->request->post['code'];
            $event['trigger'] = $this->request->post['trigger'];
            $event['action'] = $this->request->post['action'];
            $event['sort_order'] = $this->request->post['sort_order'];
        }

        if($event){

            $event = $this->model_extension_event_manager_module_event_manager->updateEvent($event_id, $event);

            if($event){
                $enable = $this->url->link($this->route.'|enable', 'event_id=' . $event['event_id']);
                $disable = $this->url->link($this->route.'|disable', 'event_id=' . $event['event_id']);

                $json = array(
                    'event_id'      => $event['event_id'],
                    'code'          => $event['code'],
                    'trigger'       => $event['trigger'],
                    'action'        => $event['action'],
                    'status'        => $event['status'],
                    'sort_order'    => $event['sort_order'],
                    'enable'        => $enable,
                    'disable'       => $disable,
                    'edit'          => $this->url->link($this->route.'|edit', 'event_id=' . $event['event_id']),
                    'save'          => $this->url->link($this->route.'|save', 'event_id=' . $event['event_id'])
                );

                $json['saved'] = true;
            }else{
                $json['saved'] = false;
            }
        }else{
            $json['saved'] = false;
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function install_test(){

        if(!$this->validate()){
            $this->session->data['error'] = $this->language->get('error_permission');
            $this->response->redirect($this->url->link($this->route, '&user_token=' . $this->session->data['user_token']));
        }
            $this->model_extension_event_manager_module_event_manager->installDatabase();

            $this->model_setting_event->deleteEventByCode($this->codename);

            $this->model_extension_event_manager_module_event_manager->addEvent($this->codename, '', 'admin/view/common/header/before', $this->route_event.'|view_before');
            $this->model_extension_event_manager_module_event_manager->addEvent($this->codename, '', 'admin/view/common/header/after', $this->route_event.'|view_after');
            $this->model_extension_event_manager_module_event_manager->addEvent($this->codename, '', 'admin/controller/common/header/before', $this->route_event.'|controller_before');
            $this->model_extension_event_manager_module_event_manager->addEvent($this->codename, '', 'admin/controller/common/header/after', $this->route_event.'|controller_after');
            $this->model_extension_event_manager_module_event_manager->addEvent($this->codename, '', 'admin/model/setting/store/getStores/before', $this->route_event.'|model_before');
            $this->model_extension_event_manager_module_event_manager->addEvent($this->codename, '', 'admin/model/setting/store/getStores/after', $this->route_event.'|model_after');
            $this->model_extension_event_manager_module_event_manager->addEvent($this->codename, '', 'admin/language/common/header/before', $this->route_event.'|language_before');
            $this->model_extension_event_manager_module_event_manager->addEvent($this->codename, '', 'admin/language/common/header/after', $this->route_event.'|language_after');

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link($this->route, '&user_token=' . $this->session->data['user_token']));

    }
    public function uninstall_test(){

        if(!$this->validate()){
            $this->session->data['error'] = $this->language->get('error_permission');
            $this->response->redirect($this->url->link($this->route, '&user_token=' . $this->session->data['user_token']));
        }

        $this->model_setting_event->deleteEventByCode($this->codename);

        $this->session->data['success'] = $this->language->get('text_success');
        $this->response->redirect($this->url->link($this->route, '&user_token=' . $this->session->data['user_token']));
    }

    public function clear_debug_file() {
        $this->load->language($this->route);
        $json = array();

        if (!$this->user->hasPermission('modify', $this->route)) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $file = DIR_LOGS.$this->request->post['debug_file'];

            $handle = fopen($file, 'w+');

            fclose($handle);

            $json['success'] = $this->language->get('success_clear_debug_file');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));

    }

    public function autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_code']) || isset($this->request->get['filter_trigger']) || isset($this->request->get['filter_action'])) {
            if (isset($this->request->get['filter_code'])) {
                $filter_code = $this->request->get['filter_code'];
            } else {
                $filter_code = '';
            }

            if (isset($this->request->get['filter_trigger'])) {
                $filter_trigger = $this->request->get['filter_trigger'];
            } else {
                $filter_trigger = '';
            }

            if (isset($this->request->get['filter_action'])) {
                $filter_action = $this->request->get['filter_action'];
            } else {
                $filter_action = '';
            }

            $filter_data = array(
                'filter_code'  => $filter_code,
                'filter_trigger' => $filter_trigger,
                'filter_action' => $filter_action,
                'start'        => 0,
                'unique'       => 1,
                'limit'        => 5
            );

            $this->load->model('extension/module/d_event_manager');

            $results = $this->model_extension_event_manager_module_event_manager->getEvents($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'event_id'          => $result['event_id'],
                    'code'              => strip_tags(html_entity_decode($result['code'], ENT_QUOTES, 'UTF-8')),
                    'trigger'           => strip_tags(html_entity_decode($result['trigger'], ENT_QUOTES, 'UTF-8')),
                    'action'      => strip_tags(html_entity_decode($result['action'], ENT_QUOTES, 'UTF-8')),
                );
            }
        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['code'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}

<?php
/*
 *	location: admin/controller
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class ControllerModuleDEventManager extends Controller {

	private $codename = 'd_event_manager';
	private $route = 'module/d_event_manager';
	private $config_file = 'd_event_manager';
	private $extension = array();
	private $store_id = 0;
	private $error = array();


	public function __construct($registry) {
		parent::__construct($registry);

		$this->d_shopunity = (file_exists(DIR_SYSTEM.'mbooth/extension/d_shopunity.json'));
		$this->extension = json_decode(file_get_contents(DIR_SYSTEM.'mbooth/extension/'.$this->codename.'.json'), true);
		$this->store_id = (isset($this->request->get['store_id'])) ? $this->request->get['store_id'] : 0;
		if(VERSION >= '2.3.0.0'){
			$this->route = 'extension/'.$this->route;
		}
	}

	public function required(){
		$this->load->language($this->route);

		$this->document->setTitle($this->language->get('heading_title_main'));
		$data['heading_title'] = $this->language->get('heading_title_main');
		$data['text_not_found'] = $this->language->get('text_not_found');
		$data['breadcrumbs'] = array();

   		$data['header'] = $this->load->controller('common/header');
   		$data['column_left'] = $this->load->controller('common/column_left');
   		$data['footer'] = $this->load->controller('common/footer');

   		$this->request->get['extension'] = $this->codename;
   		$this->response->setOutput($this->load->view('error/not_found.tpl', $data));
	}


	public function index(){

		if(!$this->d_shopunity){
			$this->response->redirect($this->url->link($this->route.'/required', 'codename=d_shopunity&token='.$this->session->data['token'], 'SSL'));
		}

		$this->load->model('d_shopunity/mbooth');
	    $this->model_d_shopunity_mbooth->validateDependencies($this->codename);


		$this->load->language($this->route);
		$this->load->model('module/d_event_manager');
		$this->load->model('setting/setting');
		$this->load->model('extension/module');
		$this->load->model('d_shopunity/setting');

		//save post
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$this->model_setting_setting->editSetting($this->codename, $this->request->post, $this->store_id);
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		// styles and scripts
		$this->document->addStyle('view/stylesheet/shopunity/bootstrap.css');
		// sortable
		$this->document->addScript('view/javascript/shopunity/rubaxa-sortable/Sortable.js');
		$this->document->addStyle('view/stylesheet/shopunity/rubaxa-sortable/Sortable.css');
		$this->document->addScript('view/javascript/shopunity/tinysort/jquery.tinysort.min.js');
		
		$this->document->addScript('view/javascript/shopunity/bootstrap-colorpicker/bootstrap-colorpicker.min.js');
		$this->document->addStyle('view/stylesheet/shopunity/bootstrap-colorpicker/bootstrap-colorpicker.min.css');

		$this->document->addScript('view/javascript/shopunity/bootstrap-switch/bootstrap-switch.min.js');
		$this->document->addStyle('view/stylesheet/shopunity/bootstrap-switch/bootstrap-switch.css');

		if(VERSION >= '2.3.0.0'){
			$this->document->addScript('view/javascript/summernote/summernote.js');
			$this->document->addScript('view/javascript/summernote/opencart.js');
			$this->document->addStyle('view/javascript/summernote/summernote.css');
		}
		
		// Add more styles, links or scripts to the project is necessary
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

		// Heading
		$this->document->setTitle($this->language->get('heading_title_main'));
		$data['heading_title'] = $this->language->get('heading_title_main');
		$data['text_edit'] = $this->language->get('text_edit');
		
		// Variable
		$data['id'] = $this->codename;
		$data['route'] = $this->route;
		$data['version'] = $this->extension['version'];
		$data['token'] =  $this->session->data['token'];
		$data['d_shopunity'] = $this->d_shopunity;

		// Customer
		$data['tab_customer'] = $this->language->get('tab_customer');

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

		$data['entry_code'] = $this->language->get('entry_code');
		$data['entry_trigger'] = $this->language->get('entry_trigger');
		$data['entry_action'] = $this->language->get('entry_action');
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
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_clear'] = $this->language->get('button_clear');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_remove'] = $this->language->get('button_remove');
		
		// Entry
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

		//action
		$data['module_link'] = $this->url->link($this->route, 'token=' . $this->session->data['token'], 'SSL');
		$data['action'] = $this->url->link($this->route, 'token=' . $this->session->data['token'] . $url, 'SSL');
		if(VERSION >= '2.3.0.0'){	
			$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);
		}else{
			$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		}
		
		//debug
		$data['tab_debug'] = $this->language->get('tab_debug');
		$data['entry_debug'] = $this->language->get('entry_debug');
		$data['entry_debug_file'] = $this->language->get('entry_debug_file');
		$data['clear_debug_file'] = str_replace('&amp;', '&', $this->url->link($this->route.'/clear_debug_file', 'token=' . $this->session->data['token'], 'SSL'));
		
		//support
		$data['tab_support'] = $this->language->get('tab_support');
		$data['text_support'] = $this->language->get('text_support');
		$data['entry_support'] = $this->language->get('entry_support');
		$data['button_support_email'] = $this->language->get('button_support_email');				
		
		//instruction
		$data['tab_instruction'] = $this->language->get('tab_instruction');
		$data['text_instruction'] = $this->language->get('text_instruction');


		if (isset($this->request->post[$this->codename.'_status'])) {
			$data[$this->codename.'_status'] = $this->request->post[$this->codename.'_status'];
		} else {
			$data[$this->codename.'_status'] = $this->config->get($this->codename.'_status');
		}

			
		//get config 
		$this->config_file = $this->model_d_shopunity_setting->getConfigFileName($this->codename);
		$data['config'] = $this->config_file;
		$data['config_files'] = $this->model_d_shopunity_setting->getConfigFileNames($this->codename);

		//get store
		$data['store_id'] = $this->store_id;
		$data['stores'] = $this->model_d_shopunity_setting->getStores();

		//get setting
		$data['setting'] = $this->model_d_shopunity_setting->getSetting($this->codename);


		//select
		$data['selects'] = array('option_1', 'option_2', 'option_3');

		//radio
		$data['radios'] = array('1', '0');
		foreach($data['radios'] as $radio){
			$data['text_radio_'.$radio] = $this->language->get('text_radio_'.$radio);
		}

		//image
		$this->load->model('tool/image');
		if (isset($this->request->post[$this->codename.'_setting']['image']) && is_file(DIR_IMAGE . $this->request->post[$this->codename.'_setting']['image'])) {
			$data['image'] = $this->model_tool_image->resize($this->request->post[$this->codename.'_setting']['image'], 100, 100);
		} elseif (isset($data['setting']['image']) && is_file(DIR_IMAGE . $data['setting']['image'])) {
			$data['image'] = $this->model_tool_image->resize($data['setting']['image'], 100, 100);
		} else {
			$data['image'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		//debug
		if(isset($data['setting']['debug'])){
			//get debug file
			$data['debug_log'] = $this->model_module_d_event_manager->getFileContents(DIR_LOGS.$data['setting']['debug_file']);
			$data['debug_file'] = $data['setting']['debug_file'];
		}
		
	
		//Customer
		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$filter_code = (isset($this->request->get['filter_code'])) ? $this->request->get['filter_code'] : null;
		$filter_trigger = (isset($this->request->get['filter_trigger'])) ? $this->request->get['filter_trigger'] : null;
		$filter_action = (isset($this->request->get['filter_action'])) ? $this->request->get['filter_action'] : null;
		$filter_status = (isset($this->request->get['filter_status'])) ? $this->request->get['filter_status'] : null;
		$filter_date_added = (isset($this->request->get['filter_date_added'])) ? $this->request->get['filter_date_added'] : null;
		
		$sort = (isset($this->request->get['sort'])) ? $this->request->get['sort'] : 'code';
		$order = (isset($this->request->get['order'])) ? $this->request->get['order'] : 'ASC';
		$page = (isset($this->request->get['page'])) ? $this->request->get['page'] : 1;

		$data['add'] = $this->url->link($this->route.'/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link($this->route.'/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['events'] = array();

		$filter_data = array(
			'filter_code'				=> $filter_code,
			'filter_trigger'			=> $filter_trigger,
			'filter_action'				=> $filter_action,
			'filter_status'				=> $filter_status,
			'filter_date_added'			=> $filter_date_added,
			'sort'						=> $sort,
			'order'						=> $order,
			'start'						=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'						=> $this->config->get('config_limit_admin')
		);

		$event_total = $this->model_module_d_event_manager->getTotalEvents($filter_data);

		$results = $this->model_module_d_event_manager->getEvents($filter_data);

		foreach ($results as $result) {
			
			$enable = $this->model_module_d_event_manager->ajax($this->route.'/enable', 'token=' . $this->session->data['token'] . '&event_id=' . $result['event_id'] . $url, 'SSL');
			$disable = $this->model_module_d_event_manager->ajax($this->route.'/disable', 'token=' . $this->session->data['token'] . '&event_id=' . $result['event_id'] . $url, 'SSL');

			$data['events'][] = array(
				'event_id'       => $result['event_id'],
				'code'           => $result['code'],
				'trigger'        => $result['trigger'],
				'action'         => $result['action'],
				'status'         => $result['status'],
				'date_added'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'enable'         => $enable,
				'disable'        => $disable,
				'edit'           => $this->url->link($this->route.'/edit', 'token=' . $this->session->data['token'] . '&event_id=' . $result['event_id'] . $url, 'SSL')
			);
		}

		//sort
		if ($order == 'ASC') {
			$url_params['order'] = 'DESC';
		} else {
			$url_params['order'] = 'ASC';
		}
		unset($url_params['sort']);
		$url = ((!empty($url_params)) ? '&' : '' ) . http_build_query($url_params);
		$data['sort_code'] = $this->url->link($this->route, 'token=' . $this->session->data['token'] . '&sort=code' . $url, 'SSL');
		$data['sort_trigger'] = $this->url->link($this->route, 'token=' . $this->session->data['token'] . '&sort=trigger' . $url, 'SSL');
		$data['sort_action'] = $this->url->link($this->route, 'token=' . $this->session->data['token'] . '&sort=action' . $url, 'SSL');
		$data['sort_status'] = $this->url->link($this->route, 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link($this->route, 'token=' . $this->session->data['token'] . '&sort=date_added' . $url, 'SSL');

		//pagination
		if (isset($this->request->get['sort'])) {
			$url_params['sort'] = $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url_params['order'] = $this->request->get['order'];
		}
		unset($url_params['page']);
		$url = ((!empty($url_params)) ? '&' : '' ) . http_build_query($url_params);
		$pagination = new Pagination();
		$pagination->total = $event_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link($this->route, 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf(
			$this->language->get('text_pagination'), 
			($event_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, 
			((($page - 1) * $this->config->get('config_limit_admin')) > ($event_total - $this->config->get('config_limit_admin'))) ? $event_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), 
			$event_total, 
			ceil($event_total / $this->config->get('config_limit_admin'))
		);



		$data['filter_code'] = $filter_code;
		$data['filter_trigger'] = $filter_trigger;
		$data['filter_action'] = $filter_action;
		$data['filter_status'] = $filter_status;
		$data['filter_date_added'] = $filter_date_added;

		$this->load->model('setting/store');


		$data['sort'] = $sort;
		$data['order'] = $order;

   		/**

   		 Add code here 

   		 **/

   		 // Breadcrumbs
		$data['breadcrumbs'] = array(); 
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
			);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
			);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link($this->route, 'token=' . $this->session->data['token'] . $url, 'SSL')
			);

		// Notification
		foreach($this->error as $key => $error){
			$data['error'][$key] = $error;
		}

   		$data['header'] = $this->load->controller('common/header');
   		$data['column_left'] = $this->load->controller('common/column_left');
   		$data['footer'] = $this->load->controller('common/footer');

   		$this->response->setOutput($this->load->view('module/d_event_manager.tpl', $data));
   	}


	private function validate($permission = 'modify') {

		if (isset($this->request->post['config'])) {
			return false;
		}

		$this->language->load($this->route);
		
		if (!$this->user->hasPermission($permission, $this->route)) {
			$this->error['warning'] = $this->language->get('error_permission');
			return false;
		}

		if(empty($this->request->post[$this->codename.'_setting']['select'])){
			$this->error['select'] = $this->language->get('error_select');
			return false;
		}

		if(empty($this->request->post[$this->codename.'_setting']['text'])){
			$this->error['text'] = $this->language->get('error_text');
			return false;
		}

		return true;
	}

	public function enable() {
		$event_id = false;
		$event = array();

		$this->load->model('module/d_event_manager');

		if(isset($this->request->get['event_id'])){
			$event_id = $this->request->get['event_id'];
			$event = $this->model_module_d_event_manager->getEventById($event_id);
		}

		if($event){
			$result = $this->model_module_d_event_manager->enableEvent($event_id);
			$json['enabled'] = true;

			
		}else{
			$json['enabled'] = false;
		}


		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function disable() {
		$event_id = false;
		$event = array();

		$this->load->model('module/d_event_manager');

		if(isset($this->request->get['event_id'])){
			$event_id = $this->request->get['event_id'];
			$event = $this->model_module_d_event_manager->getEventById($event_id);
		}

		if($event){
			$result = $this->model_module_d_event_manager->disableEvent($event_id);
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

		$this->load->model('module/d_event_manager');

		if(isset($this->request->get['event_id'])){
			$event_id = $this->request->get['event_id'];
			$result = $this->model_module_d_event_manager->getEventById($event_id);
		}

		if($result){
			$enable = $this->model_module_d_event_manager->ajax($this->route.'/enable', 'token=' . $this->session->data['token'] . '&event_id=' . $result['event_id'] , 'SSL');
			$disable = $this->model_module_d_event_manager->ajax($this->route.'/disable', 'token=' . $this->session->data['token'] . '&event_id=' . $result['event_id'] , 'SSL');

			$json = array(
				'event_id'		=> $result['event_id'],
				'code'			=> $result['code'],
				'trigger'		=> $result['trigger'],
				'action'		=> $result['action'],
				'status'		=> $result['status'],
				'date_added'	=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'enable'		=> $enable,
				'disable'		=> $disable,
				'edit'			=> $this->url->link($this->route.'/edit', 'token=' . $this->session->data['token'] . '&event_id=' . $result['event_id'] , 'SSL'),
				'save'			=> $this->url->link($this->route.'/save', 'token=' . $this->session->data['token'] . '&event_id=' . $result['event_id'] , 'SSL')
			);
		}else{
			$json = false;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function save(){
		$event_id = false;
		$event = array();

		$this->load->model('module/d_event_manager');

		if(isset($this->request->get['event_id']) 
		&& isset($this->request->post['code'])
		&& isset($this->request->post['trigger'])
		&& isset($this->request->post['action'])){
			$event_id = $this->request->get['event_id'];
			$event = $this->model_module_d_event_manager->getEventById($event_id);
			$event['code'] = $this->request->post['code'];
			$event['trigger'] = $this->request->post['trigger'];
			$event['action'] = $this->request->post['action'];
		}

		if($event){

			$event = $this->model_module_d_event_manager->updateEvent($event_id, $event);

			if($event){
				$enable = $this->model_module_d_event_manager->ajax($this->route.'/enable', 'token=' . $this->session->data['token'] . '&event_id=' . $event['event_id'] , 'SSL');
				$disable = $this->model_module_d_event_manager->ajax($this->route.'/disable', 'token=' . $this->session->data['token'] . '&event_id=' . $event['event_id'] , 'SSL');

				$json = array(
					'event_id'		=> $event['event_id'],
					'code'			=> $event['code'],
					'trigger'		=> $event['trigger'],
					'action'		=> $event['action'],
					'status'		=> $event['status'],
					'date_added'	=> date($this->language->get('date_format_short'), strtotime($event['date_added'])),
					'enable'		=> $enable,
					'disable'		=> $disable,
					'edit'			=> $this->url->link($this->route.'/edit', 'token=' . $this->session->data['token'] . '&event_id=' . $event['event_id'] , 'SSL'),
					'save'			=> $this->url->link($this->route.'/save', 'token=' . $this->session->data['token'] . '&event_id=' . $event['event_id'] , 'SSL')
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


	public function install() {
		$this->load->model('module/d_event_manager');
		$this->model_module_d_event_manager->installDatabase();

		// if($this->d_shopunity){
		// 	$this->load->model('d_shopunity/vqmod');
		// 	$this->model_d_shopunity_vqmod->setVqmod('a_vqmod_d_event_manager.xml', 1);

		// 	$this->load->model('d_shopunity/mbooth');
		// 	$this->model_d_shopunity_mbooth->installDependencies($this->codename);  
		// }
	}

	public function uninstall() {
		if($this->d_shopunity){
			// $this->load->model('d_shopunity/vqmod');
			// $this->model_module_d_event_manager->setVqmod('a_vqmod_d_event_manager.xml', 0);  
		}
	}

   	/**

	Add Assisting functions here 

	**/	

	/*
	 *	Ajax: clear debug file.
	 */
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

	/**
	 * Ajax requests
	 */
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
				'limit'        => 5
			);

			$this->load->model('module/d_event_manager');

			$results = $this->model_module_d_event_manager->getEvents($filter_data);

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
?>
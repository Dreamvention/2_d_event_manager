<?php
/*
* Event System Userguide
* 
* https://github.com/opencart/opencart/wiki/Events-(script-notifications)-2.2.x.x
*/

namespace d_event_manager;

class Event {
	protected $registry;
	protected $data = array();

	public function __construct($registry) {
		
		if(VERSION < '2.2.0.0'){
			$db = new \DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
			$registry->set('db', $db);
		}

		$this->registry = $registry;
		$this->register_all();
	}

	public function register_all(){
		if(VERSION < '2.2.0.0'){
			$location = 'catalog';
			if(defined('HTTP_CATALOG')){
				$location = 'admin';
			}
			
			$query = $this->registry->get('db')->query("SELECT * FROM `" . DB_PREFIX . "event` WHERE `trigger` LIKE '".$location."/%' AND `status` = '1' ORDER BY `event_id` ASC");
			
			foreach ($query->rows as $result) {
				$this->register(substr($result['trigger'], strpos($result['trigger'], '/') + 1), new Action($result['action']));
			}
		}
	}

	public function register($trigger, Action $action) {
		$this->data[$trigger][] = $action;
	}
	
	public function trigger($event, array $args = array()) {
		foreach ($this->data as $trigger => $actions) {
			if (preg_match('/^' . str_replace(array('\*', '\?'), array('.*', '.'), preg_quote($trigger, '/')) . '/', $event)) {
				foreach ($actions as $action) {
					$result = $action->execute($this->registry, $args);

					if (!is_null($result) && !($result instanceof Exception)) {
						return $result;
					}
				}
			}
		}
	}

	public function unregister($trigger, $route = '') {
		if ($route) {
			foreach ($this->data[$trigger] as $key => $action) {
				if ($action->getId() == $route) {
					unset($this->data[$trigger][$key]);
				}
			}
		} else {
			unset($this->data[$trigger]);
		}
	}

	public function removeAction($trigger, $route) {
		foreach ($this->data[$trigger] as $key => $action) {
			if ($action->getId() == $route) {
				unset($this->data[$trigger][$key]);
			}
		}
	}
}
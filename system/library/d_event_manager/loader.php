<?php
namespace d_event_manager;

final class Loader {
	protected $class;


	public function __construct($class, $registry) {
		$this->class = $class;
		$this->registry = $registry;
		$this->event = new Event($registry);
	}
	public function controller($route, $data = array()){

		if(VERSION < '2.2.0.0'){
			$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
			
			$output = null;
			
			// Trigger the pre events
			$result = $this->event->trigger('controller/' . $route . '/before', array(&$route, &$data, &$output));
			

			$output = $this->class->_controller($route, $data);

			// Trigger the post events
			$result = $this->event->trigger('controller/' . $route . '/after', array(&$route, &$data, &$output));
			
		}else{
			$output = $this->class->_controller($route, $data);
		}

		if ($output instanceof Exception) {
			return false;
		}

		return $output;
	}

	public function model($route, $data = array()){
		if(VERSION < '2.2.0.0'){
			$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
			
			$output = null;
			
			// Trigger the pre events
			$result = $this->event->trigger('model/' . $route . '/before', array(&$route));

			//, $data = array()???
			$output = $this->class->_model($route);

			// Trigger the post events
			$result = $this->event->trigger('model/' . $route . '/after', array(&$route));
		}else{
			$output = $this->class->_model($route);
		}

		if ($output instanceof Exception) {
			return false;
		}

		return $output;
	}
	
	public function view($template, $data = array()){
		if(VERSION < '2.2.0.0'){
			$output = null;
			
			// Sanitize the call
			$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$template);
			//remove tpl
			if (substr($route, -3) == 'tpl') {
				$route = substr($route, 0, -3);
			}
			//remove twig
			if (substr($route, -4) == 'twig') {
				$route = substr($route, 0, -4);
			}

			$parts = explode('template/', $route);
			if(isset($parts['1'])){
				$route = $parts['1'];
			}

			// Trigger the pre events
			$result = $this->event->trigger('view/' . $route . '/before', array(&$route, &$data, &$output));

			if(!$output){
				//, $data = array()???
				$output = $this->class->_view($template, $data);
			}

			$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$template);
			if (substr($route, -3) == 'tpl') {
				$route = substr($route, 0, -3);
			}
			//remove twig
			if (substr($route, -4) == 'twig') {
				$route = substr($route, 0, -4);
			}

			// Trigger the post events
			$result = $this->event->trigger('view/' . $route . '/after', array(&$route, &$data, &$output));
		}else{
			$output = $this->class->_view($template, $data);
		}

		if ($result) {
			return $result;
		}
		
		return $output;
	}

	
	
	public function config($route) {

		if(VERSION < '2.2.0.0'){
			$this->event->trigger('config/' . $route . '/before', array(&$route));
			
			$output = $this->class->_config($route);
			
			$this->event->trigger('config/' . $route . '/after', array(&$route, &$output));
		}else{
			$output = $this->class->_config($route);
		}
	}

	public function language($route) {
		if(VERSION < '2.2.0.0'){
			$output = null;
			
			$this->event->trigger('language/' . $route . '/before', array(&$route, &$output));
			
			$output = $this->class->_language($route);
			
			$this->event->trigger('language/' . $route . '/after', array(&$route, &$output));
		}else{
			$output = $this->class->_language($route);
		}
		return $output;
	}
	
	public function callback($registry, $route) {
		return function($args) use($registry, &$route) {
			static $model = array(); 			
			
			$output = null;
			
			// Trigger the pre events
			$result = $this->event->trigger('model/' . $route . '/before', array(&$route, &$args, &$output));
			
			if ($result) {
				return $result;
			}
			
			// Store the model object
			if (!isset($model[$route])) {
				$file = DIR_APPLICATION . 'model/' .  substr($route, 0, strrpos($route, '/')) . '.php';
				$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', substr($route, 0, strrpos($route, '/')));

				if (is_file($file)) {
                    if(!class_exists($class)) {
                        include_once($file);
                    }
				
					$model[$route] = new $class($registry);
				} else {
					throw new \Exception('Error: Could not load model ' . substr($route, 0, strrpos($route, '/')) . '!');
				}
			}

			$method = substr($route, strrpos($route, '/') + 1);
			
			$callable = array($model[$route], $method);

			if (is_callable($callable)) {
				$output = call_user_func_array($callable, $args);
			} else {
				throw new \Exception('Error: Could not call model/' . $route . '!');
			}
			
			// Trigger the post events
			$result = $this->event->trigger('model/' . $route . '/after', array(&$route, &$args, &$output));
			
			if ($result) {
				return $result;
			}
						
			return $output;
		};
	}	
}
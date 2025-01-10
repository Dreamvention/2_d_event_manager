<?php
namespace Opencart\Admin\Controller\Extension\EventManager\Event;

class EventManager  extends \Opencart\System\Engine\Controller {

    private $config_path = '../../extension/event_manager/system/config/event_manager';

    public function controller_before(&$route, &$data){
        $this->config->load($this->config_path);
        $setting = $this->config->get('event_manager');
        $setting['controller_before'] = true;
        $this->config->set('event_manager', $setting);
    }

    public function controller_after(&$route, &$data, &$output){
        $this->config->load($this->config_path);
        $setting = $this->config->get('event_manager');
        $setting['controller_after'] = true;
        $this->config->set('event_manager', $setting);
        $output.= '
            <script> 
                var d_event_manager = '.json_encode($setting).'; 
                $(function () {

                    jQuery.each(d_event_manager, function(i, val) {
                        if(val){
                            $("." + i).removeClass("failed");
                        }
                        
                    });
                });
            </script>';
    }
    
    public function model_before(&$route){
        $this->config->load($this->config_path);
        $setting = $this->config->get('event_manager');
        $setting['model_before'] = true;
        $this->config->set('event_manager', $setting);
    }

    public function model_after(&$route, &$data){
        $this->config->load($this->config_path);
        $setting = $this->config->get('event_manager');
        $setting['model_after'] = true;
        $this->config->set('event_manager', $setting);
    }


    public function view_before(&$route, &$data){
        $this->config->load($this->config_path);
        $setting = $this->config->get('event_manager');
        $setting['view_before'] = true;
        $this->config->set('event_manager', $setting);
    }

    public function view_after(&$route, &$data, &$output){
        $this->config->load($this->config_path);
        $setting = $this->config->get('event_manager');
        $setting['view_after'] = true;
        $this->config->set('event_manager', $setting);
    }

    public function language_before(&$route, &$output){
        $this->config->load($this->config_path);
        $setting = $this->config->get('event_manager');
        $setting['language_before'] = true;
        $this->config->set('event_manager', $setting);
    }

    public function language_after(&$route, &$output){
        $this->config->load($this->config_path);
        $setting = $this->config->get('event_manager');
        $setting['language_after'] = true;
        $this->config->set('event_manager', $setting);
    }
}
<?php
/*
 *	location: admin/model
 */

class ModelModuleDEventManager extends Model {

	/**

	 Modal functions

	 **/

	public function getEvents($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "event` ";

		$implode = array();

		if (!empty($data['filter_code'])) {
			$implode[] = "`code` LIKE '%" . $this->db->escape($data['filter_code']) . "%'";
		}

		if (!empty($data['filter_trigger'])) {
			$implode[] = "`trigger` LIKE '%" . $this->db->escape($data['filter_trigger']) . "%'";
		}

		if (!empty($data['filter_action'])) {
			$implode[] = "`action` LIKE '%" . $this->db->escape($data['filter_action']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "`status` = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(`date_added`) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'code',
			'trigger',
			'action',
			'status',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY `" . $data['sort'] . "`";
		} else {
			$sql .= " ORDER BY `code`";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalEvents($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "event";

		$implode = array();

		if (!empty($data['filter_code'])) {
			$implode[] = "`code` LIKE '%" . $this->db->escape($data['filter_code']) . "%'";
		}

		if (!empty($data['filter_trigger'])) {
			$implode[] = "`trigger` LIKE '%" . $this->db->escape($data['filter_trigger']) . "%'";
		}

		if (!empty($data['filter_action'])) {
			$implode[] = "`action` LIKE '%" . $this->db->escape($data['filter_action']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "`status` = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(`date_added`) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function updateEvent($event_id, $data){

		$this->db->query("UPDATE " . DB_PREFIX . "event SET 
			`code` = '" . $this->db->escape($data['code'])."',
			`trigger` = '" . $this->db->escape($data['trigger'])."',
			`action` = '" . $this->db->escape($data['action'])."',
			`status` = '". (int)$data['status']."'
			WHERE event_id = '" . (int)$event_id . "'");

		return $this->getEventById($event_id);
	}

	public function addEvent($code, $trigger, $action, $status = 1) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = '" . $this->db->escape($code) . "', `trigger` = '" . $this->db->escape($trigger) . "', `action` = '" . $this->db->escape($action) . "', `status` = '" . (int)$status . "', `date_added` = now()");
	
		return $this->db->getLastId();
	}

	public function deleteEvent($code) {
		//if you have several events under one code - they will all be deleted. 
		//please use deleteEventById.
		if(VERSION > '2.0.0.0'){
			$this->load->model('extension/event');
			return $this->model_extension_event->deleteEvent($code);
		}else{

			$this->db->query("DELETE FROM " . DB_PREFIX . "event WHERE `code` = '" . $this->db->escape($code) . "'");

		}
		
	}

	public function deleteEventById($event_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `event_id` = '" . (int)$event_id . "'");
	}

	public function getEventById($event_id) {
		$event = $this->db->query("SELECT * FROM `" . DB_PREFIX . "event` WHERE `event_id` = '" . $this->db->escape($event_id) ."'");
		
		return $event->row;
	}

	public function enableEvent($event_id) {
		if(VERSION > '2.3.0.0'){
			$this->load->model('extension/event');
			return $this->model_extension_event->enableEvent($event_id);	
		}else{
			$this->db->query("UPDATE " . DB_PREFIX . "event SET `status` = '1' WHERE event_id = '" . (int)$event_id . "'");
		}
		
	}
	
	public function disableEvent($event_id) {
		if(VERSION > '2.3.0.0'){
			$this->load->model('extension/event');
			return $this->model_extension_event->disableEvent($event_id);
		}else{
			$this->db->query("UPDATE " . DB_PREFIX . "event SET `status` = '0' WHERE event_id = '" . (int)$event_id . "'");
		}
	}

	public function installDatabase(){
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `oc_event` (
		  `event_id` int(11) NOT NULL AUTO_INCREMENT,
		  `code` varchar(32) NOT NULL,
		  `trigger` text NOT NULL,
		  `action` text NOT NULL,
		  `status` tinyint(1) NOT NULL,
		  `date_added` datetime NOT NULL,
		  PRIMARY KEY (`event_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");


		$result = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".DB_DATABASE."' AND TABLE_NAME = '" . DB_PREFIX . "event' ORDER BY ORDINAL_POSITION")->rows; 
		$columns = array();
		foreach($result as $column){
			$columns[] = $column['COLUMN_NAME'];
		}

		if(!in_array('status', $columns)){
			 $this->db->query("ALTER TABLE `" . DB_PREFIX . "event` ADD status int( 1 ) NOT NULL default '1'");
		}

		if(!in_array('date_added', $columns)){
			 $this->db->query("ALTER TABLE `" . DB_PREFIX . "event` ADD `date_added` datetime NOT NULL");
		}

	}

    public function installCompatibility(){

        if(VERSION >= '2.3.0.0'){
            return true;
        }

        $d_shopunity = (file_exists(DIR_SYSTEM.'mbooth/extension/d_shopunity.json'));
        if(!$d_shopunity){
            return false;
        }

        $this->load->model('d_shopunity/ocmod');
        
        $compatibility = $this->model_d_shopunity_ocmod->getModificationByName('d_event_manager');
        if($compatibility){
            if(!empty($compatibility['status'])){
                return true;
            }else{
                $this->model_d_shopunity_ocmod->setOcmod('d_event_manager.xml', 0);
            }
        }

        $this->installDatabase();
        $this->model_d_shopunity_ocmod->setOcmod('d_event_manager.xml', 1);
        $this->model_d_shopunity_ocmod->refreshCache();

        return true;
    }

    public function uninstallCompatibility(){

        if(VERSION >= '2.3.0.0'){
            return true;
        }

        $d_shopunity = (file_exists(DIR_SYSTEM.'mbooth/extension/d_shopunity.json'));
        if(!$d_shopunity){
            return false;
        }

        $this->load->model('d_shopunity/ocmod');

        $compatibility = $this->model_d_shopunity_ocmod->getModificationByName('d_event_manager');
        if(!$compatibility){
            return true;
        }

        $this->model_d_shopunity_ocmod->setOcmod('d_event_manager.xml', 0);
        $this->model_d_shopunity_ocmod->refreshCache();

        return true;
    }

	/**

	 Helper functions

	 **/

	/*
	*	Format the link to work with ajax requests
	*/
	public function ajax($route, $url = '', $ssl = true){
		return str_replace('&amp;', '&', $this->url->link($route, $url, $ssl));
	}

	/*
	*	Get file contents, usualy for debug log files.
	*/

	public function getFileContents($file){

		if (file_exists($file)) {
			$size = filesize($file);

			if ($size >= 5242880) {
				$suffix = array(
					'B',
					'KB',
					'MB',
					'GB',
					'TB',
					'PB',
					'EB',
					'ZB',
					'YB'
					);

				$i = 0;

				while (($size / 1024) > 1) {
					$size = $size / 1024;
					$i++;
				}

				return sprintf($this->language->get('error_get_file_contents'), basename($file), round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i]);
			} else {
				return file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
			}
		}
	}

	/*
	*	Return name of config file.
	*/
	public function getConfigFile($id, $sub_versions){
		
		if(isset($this->request->post['config'])){
			return $this->request->post['config'];
		}

		$setting = $this->config->get($id.'_setting');

		if(isset($setting['config'])){
			return $setting['config'];
		}

		$full = DIR_SYSTEM . 'config/'. $id . '.php';
		if (file_exists($full)) {
			return $id;
		} 

		foreach ($sub_versions as $lite){
			if (file_exists(DIR_SYSTEM . 'config/'. $id . '_' . $lite . '.php')) {
				return $id . '_' . $lite;
			}
		}
		
		return false;
	}

	/*
	*	Return list of config files that contain the id of the module.
	*/
	public function getConfigFiles($id){
		$files = array();
		$results = glob(DIR_SYSTEM . 'config/'. $id .'*');
		foreach($results as $result){
			$files[] = str_replace('.php', '', str_replace(DIR_SYSTEM . 'config/', '', $result));
		}
		return $files;
	}

	/*
	*	Get config file values and merge with config database values
	*/
	public function getConfigData($id, $config_key, $store_id, $config_file = false){
		if(!$config_file){
			$config_file = $this->config_file;
		}
		if($config_file){
			$this->config->load($config_file);
		}

		$result = ($this->config->get($config_key)) ? $this->config->get($config_key) : array();

		if(!isset($this->request->post['config'])){
			$this->load->model('setting/setting');
			if (isset($this->request->post[$config_key])) {
				$setting = $this->request->post;
			} elseif ($this->model_setting_setting->getSetting($id, $store_id)) { 
				$setting = $this->model_setting_setting->getSetting($id, $store_id);
			}
			if(isset($setting[$config_key])){
				foreach($setting[$config_key] as $key => $value){
					$result[$key] = $value;
				}
			}
			
		}
		return $result;
	}


	/**

	 MBooth functions

	**/

	/*
	*	Return mbooth file.
	*/
	public function getMboothFile($id, $sub_versions){
		$full = DIR_SYSTEM . 'mbooth/xml/mbooth_'. $id .'.xml';
		if (file_exists($full)) {
			return 'mbooth_'. $id . '.xml';
		} else{
			foreach ($sub_versions as $lite){
				if (file_exists(DIR_SYSTEM . 'mbooth/xml/mbooth_'. $id . '_' . $lite . '.php')) {
					$this->prefix = '_' . $lite;
					return $this->id . '_' . $lite . '.xml';
				}
			}
		}
		return false;
	}

	/*
	*	Return mbooth file.
	*/
	public function getMboothInfo($mbooth_file){
		if(file_exists(DIR_SYSTEM . 'mbooth/xml/'. $mbooth_file)){
			$xml = new SimpleXMLElement(file_get_contents(DIR_SYSTEM . 'mbooth/xml/'. $mbooth_file));
			return $xml;
		}else{
			return false;
		}
	}

	/*
	*	Get the version of this module
	*/
	public function getVersion($mbooth_file){
		if(file_exists(DIR_SYSTEM . 'mbooth/xml/'. $mbooth_file)){
			$xml = new SimpleXMLElement(file_get_contents(DIR_SYSTEM . 'mbooth/xml/'. $mbooth_file));
			return $xml->version;
		}else{
			return false;
		}
	}

	/*
	*	Check if another extension/module is installed.
	*/
	public function isInstalled($code) {
		$extension_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `code` = '" . $this->db->escape($code) . "'");
		if($query->row) {
			return true;
		}else{
			return false;
		}	
	}

	/*
	*	Get extension info by mbooth from server (Check for update)
	*/
	public function getUpdateInfo($mbooth_file, $status = 1){
		$result = array();

		$current_version = $this->getVersion($mbooth_file);
		$customer_url = HTTP_SERVER;
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE language_id = " . (int)$this->config->get('config_language_id') ); 
		$language_code = $query->row['code'];
		$ip = $this->request->server['REMOTE_ADDR'];

		$request = 'http://opencart.dreamvention.com/api/1/index.php?route=extension/check&mbooth=' . $mbooth_file . '&store_url=' . $customer_url . '&module_version=' . $current_version . '&language_code=' . $language_code . '&opencart_version=' . VERSION . '&ip='.$ip . '&status=' .$status;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $request);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$result['data'] = curl_exec($curl);
		$result['code'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		return $result;
	}

	/**
	 
	*	Get the version of this module
	*	Use this function to install dependencies, which are set in your mbooth xml file 
	*	under the tag required;
	
	*/
    public function installDependencies($mbooth_file){
        if(!defined('DIR_ROOT')) { define('DIR_ROOT', substr_replace(DIR_SYSTEM, '/', -8)); }

        foreach($this->getDependencies($mbooth_file) as $extension){
            if(isset($extension['codename'])){
                if(!$this->getVersion('mbooth_'.$extension['codename'].'.xml') || ($extension['version'] > $this->getVersion('mbooth_'.$extension['codename'].'.xml'))){
                    $this->downloadExtension($extension['codename'], $extension['version']);
                    $this->extractExtension();
                    if(file_exists(DIR_SYSTEM . 'mbooth/xml/'.$mbooth_file)){
                        $result = $this->backupFilesByMbooth($mbooth_file, 'update');
                    }
                    $this->move_dir(DIR_DOWNLOAD . 'upload/', DIR_ROOT, $result);
                }
            }
        }
    }

    public function getDependencies($mbooth_xml){
        if(file_exists(DIR_SYSTEM . 'mbooth/xml/'. $mbooth_xml)){
            $xml = new SimpleXMLElement(file_get_contents(DIR_SYSTEM . 'mbooth/xml/'. $mbooth_xml));
            $result = array();
            $version = false;
            
            foreach($xml->required->require as $require){

                foreach($require->attributes() as $key => $value){
                    $version = false;
                    if($key == 'version'){
                        $version = $value;
                    }
                }
                $result[] = array(
                    'codename' => (string)$require,
                    'version' => (string)$version
                );
            }
            return $result;
        }else{
            return false;
        }
    }

    public function downloadExtension($codename, $version, $filename  = false ) {

        if(!$filename){
            $filename = DIR_DOWNLOAD . 'archive.zip';
        }

        $userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';  
        $ch = curl_init();  
        $fp = fopen($filename, "w");  
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);  
        curl_setopt($ch, CURLOPT_URL, 'http://opencart.dreamvention.com/api/1/extension/download/?codename=' . $codename.'&opencart_version='.VERSION.'&extension_version='. $version);  
        curl_setopt($ch, CURLOPT_FAILONERROR, true);  
        curl_setopt($ch, CURLOPT_HEADER,0);  
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);  
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);  
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);   
        curl_setopt($ch, CURLOPT_FILE, $fp);  
        $page = curl_exec($ch);  
        if (!$page) {  
            exit;  
        }
        curl_close($ch);

    }

    

    public function extractExtension($filename = false, $location = false ) {
        if(!$filename){
            $filename = DIR_DOWNLOAD . 'archive.zip';
        }
        if(!$location){
            $location = DIR_DOWNLOAD;
        }

        $result = array();
        $zip = new ZipArchive;
        if (!$zip) {  
            $result['error'][] = 'ZipArchive not working.'; 
        }

        if($zip->open($filename) != "true") {  
            $result['error'][] = $filename;
        }
        $zip->extractTo($location);  
        $zip->close();

        unlink($filename);

        return $result;

    }

    

    public function getMboothFiles($mbooth_url) {

        $xml = new SimpleXMLElement(file_get_contents($mbooth_url));

        if(isset($xml->id)){
            $result['file_name'] =   basename($mbooth_url, '');
            $result['id'] = isset($xml->id) ? (string)$xml->id : '';
            $result['name'] = isset($xml->name) ? (string)$xml->name : '';
            $result['description'] = isset($xml->description) ? (string)$xml->description : '';
            $result['type'] = isset($xml->type) ? (string)$xml->type : '';
            $result['version'] = isset($xml->version) ? (string)$xml->version : '';
            $result['license'] = isset($xml->license) ? (string)$xml->license : '';
            $result['opencart_version'] = isset($xml->opencart_version) ? (string)$xml->opencart_version : '';
            $result['mbooth_version'] = isset($xml->mbooth_version) ? (string)$xml->mbooth_version : '';
            $result['author'] = isset($xml->author) ? (string)$xml->author : '';
            $result['support_email'] = isset($xml->support_email) ? (string)$xml->support_email : '';
            $files = $xml->files;
            $dirs = $xml->dirs;
            $required = $xml->required;
            $updates = $xml->update;

            foreach ($files->file as $file){
               $result['files'][] = (string)$file; 
            } 
            
            if (!empty($dirs)) {

                $dir_files = array();
            
                foreach ($dirs->dir as $dir) {
                    $this->scan_dir(DIR_ROOT . $dir, $dir_files);
                }
                
                foreach ($dir_files as $file) {
                    $file = str_replace(DIR_ROOT, "", $file);
                    $result['files'][] = (string)$file;
                }
            }
            
            return $result;  
        }else{
            return false;
        }
        
    }

    public function backupFilesByMbooth($mbooth_xml, $action = 'install'){

        $zip = new ZipArchive();

        if (!file_exists(DIR_SYSTEM . 'mbooth/backup/')) {
            mkdir(DIR_SYSTEM . 'mbooth/backup/', 0777, true);
        }

        $mbooth = $this->getMboothFiles(DIR_SYSTEM . 'mbooth/xml/' . $mbooth_xml);
        $files = $mbooth['files'];

        $zip->open(DIR_SYSTEM . 'mbooth/backup/' . date('Y-m-d.h-i-s'). '.'. $action .'.'.$mbooth_xml.'.v'.$mbooth['version'].'.zip', ZipArchive::CREATE);

        
        foreach ($files as $file) {
            
            if(file_exists(DIR_ROOT.$file)){

                if (is_file(DIR_ROOT.$file)) {
                    $zip->addFile(DIR_ROOT.$file, 'upload/'.$file);
                    $result['success'][] = $file;
                }else{
                    $result['error'][] = $file;
                }
            }else{
                    $result['error'][] = $file;
            }
        }
        $zip->close();
        return $result; 

    }

    /*
    *   Dir function
    */
    public function scan_dir($dir, &$arr_files){
        
        if (is_dir($dir)){
            $handle = opendir($dir);
            while ($file = readdir($handle)){
                    if ($file == '.' or $file == '..') continue;
                    if (is_file($file)) $arr_files[]="$dir/$file";
                    else $this->scan_dir("$dir/$file", $arr_files);
            }
            closedir($handle);
        }else {
            $arr_files[]=$dir;
        }
    }

    public function move_dir($souce, $dest, &$result) {
        
        $files = scandir($souce);

        foreach($files as $file){
            
            if($file == '.' || $file == '..' || $file == '.DS_Store') continue;
            
            if(is_dir($souce.$file)){
                if (!file_exists($dest.$file.'/')) {
                    mkdir($dest.$file.'/', 0777, true);
                }
                $this->move_dir($souce.$file.'/', $dest.$file.'/', $result);
            }elseif (rename($souce.$file, $dest.$file)) {
                $result['success'][] = str_replace(DIR_ROOT, '', $dest.$file);
            }else{
                $result['error'][] = str_replace(DIR_ROOT, '', $dest.$file);
            }
        }

        $this->delete_dir($souce);
    }

    public function delete_dir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") $this->delete_dir($dir."/".$object); 
                    else unlink($dir."/".$object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
}
?>
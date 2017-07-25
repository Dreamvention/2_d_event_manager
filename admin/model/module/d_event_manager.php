<?php
/*
 *	location: admin/model
 */
require_once(DIR_APPLICATION.'model/extension/module/d_event_manager.php');
class ModelModuleDEventManager extends ModelExtensionModuleDEventManager
{   
    public function __construct($registry)
    {
        parent::__construct($registry);
    }
}
?>
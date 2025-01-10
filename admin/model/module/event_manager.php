<?php
namespace Opencart\Admin\Model\Extension\EventManager\Module;

class EventManager extends \Opencart\System\Engine\Model {

    public function getEvents($data = array()) {
        $sql = "SELECT ";

        if(!empty($data['unique'])){
            $sql .= " DISTINCT ";
        }

        $sql .= " * FROM `" . DB_PREFIX . "event` ";

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


        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        if(!empty($data['unique'])){
            $sql .= " GROUP BY code ";
        }

        $sort_data = array(
            'code',
            'trigger',
            'action',
            'sort_order',
            'status'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY `" . $data['sort'] . "`";
        } else {
            $sql .= " ORDER BY `sort_order`";
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

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

	public function addEvent(string $code, string $description, string $trigger, string $action, bool $status = true, int $sort_order = 0): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = '" . $this->db->escape($code) . "', `description` = '" . $this->db->escape($description) . "', `trigger` = '" . $this->db->escape($trigger) . "', `action` = '" . $this->db->escape($action) . "', `status` = '" . (int)$status . "', `sort_order` = '" . (int)$sort_order . "'");

		return $this->db->getLastId();
	}

	public function deleteEvent(int $event_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `event_id` = '" . (int)$event_id . "'");
	}

    public function updateEvent($event_id, $data){
        if(!isset($data['sort_order'])){
            $data['sort_order'] = 0;
        }
        $this->db->query("UPDATE " . DB_PREFIX . "event SET
            `code` = '" . $this->db->escape($data['code'])."',
            `trigger` = '" . $this->db->escape($data['trigger'])."',
            `action` = '" . $this->db->escape($data['action'])."',
            `status` = '". (int)$data['status']."',
            `sort_order` = '". (int)$data['sort_order']."'
            WHERE event_id = '" . (int)$event_id . "'");

        return $this->getEventById($event_id);
    }

    public function getEventById($event_id) {
        $event = $this->db->query("SELECT * FROM `" . DB_PREFIX . "event` WHERE `event_id` = '" . $this->db->escape($event_id) ."'");

        return $event->row;
    }

    public function enableEvent($event_id) {
        $this->db->query("UPDATE `" . DB_PREFIX . "event` SET `status` = '1' WHERE event_id = '" . (int)$event_id . "'");
    }

    public function disableEvent($event_id) {
        $this->db->query("UPDATE `" . DB_PREFIX . "event` SET `status` = '0' WHERE event_id = '" . (int)$event_id . "'");
    }

    public function installDatabase(){

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "event` (
          `event_id` int(11) NOT NULL AUTO_INCREMENT,
          `code` varchar(32) NOT NULL,
          `trigger` text NOT NULL,
          `action` text NOT NULL,
          `status` tinyint(1) NOT NULL,
          `sort_order` int(3) NOT NULL,
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

        if(!in_array('sort_order', $columns)){
             $this->db->query("ALTER TABLE `" . DB_PREFIX . "event` ADD `sort_order` int(3) NOT NULL");
        }

	}

}

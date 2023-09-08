<?php
// vim: set ai ts=4 sw=4 ft=php:
#[\AllowDynamicProperties]
class Callforward implements BMO {

	public function __construct($freepbx = null) {
		if ($freepbx == null) {
			throw new Exception("Not given a FreePBX Object");
		}

		$this->FreePBX = $freepbx;
		$this->db = $freepbx->Database;
		$this->astman = $freepbx->astman;
	}

	public function doConfigPageInit($page) {

	}

	public function install() {

	}
	public function uninstall() {

	}

	public function genConfig() {

	}

	function getStatusesByExtension($extension) {
		//CFU - Unconditional
		//CFB - Busy
		//CF - Unavailable
		$cf_type = ['CF', 'CFU', 'CFB'];
		$users = [];

		foreach ($cf_type as $value) {
			$users[$value] = $this->astman->database_get($value, $extension);
		}

		return $users;
	}

	/* UCP template to get the user assigned vm extension details
	* @defaultexten is the default_extensionof the userman userid
	* @userid is userman user id
	* @widget is an array we need to replace few item based on the userid
	*/
	public function getWidgetListByModule($defaultexten, $userid,$widget) {
		// if the widget_type_id is not defaultextension and widget_type_id is not in extensions
		// then return only the defaultexten details
		$widgets = [];
		$widget_type_id = $widget['widget_type_id'];// this will be an extension number
		$extensions = $this->FreePBX->Ucp->getCombinedSettingByID($userid,'Settings','assigned');
		$extensions = is_array($extensions)?$extensions:[];
		if(in_array($widget_type_id,$extensions)){
			// nothing to do return the same widget
			return $widget;
		}else {// sent the default extension
			$data = $this->FreePBX->Core->getDevice($defaultexten);
			if(empty($data) || empty($data['description'])) {
				$data = $this->FreePBX->Core->getUser($defaultexten);
				$name = $data['name'];
			} else {
				$name = $data['description'];
			}
			$widget['widget_type_id'] = $defaultexten;
			$widget['name'] = $name;
			return $widget;
		}
		return false;
	}

	function getNumberByExtension($extension, $type = 'CF') {
		$cf_type = match ($type) {
      'CFU' => 'CFU',
      'CFB' => 'CFB',
      default => 'CF',
  };

		$number = $this->astman->database_get($cf_type, $extension);
		return $number ?: false;
	}

	public function setMultipleNumberByExten($exten,$numbers){
		if(empty($numbers)) {
			return;
		}
		foreach($numbers as $key => $val){
			if(empty($val)){
				continue;
			}
			$this->setNumberByExtension($exten,$val,$key);
		}
	}

	function setNumberByExtension($extension, $number, $type = "CF") {
		$cf_type = match ($type) {
      'CFU' => 'CFU',
      'CFB' => 'CFB',
      default => 'CF',
  };

		if ($cf_type == 'CF') {
			$state = $this->FreePBX->Config->get('AST_FUNC_DEVICE_STATE');
			$this->astman->set_global($state . "(Custom:" . $cf_type . $extension . ")", 'BUSY');
			$devices = $this->astman->database_get("AMPUSER", $extension . "/device");
			$device_arr = explode('&', (string) $devices);
			foreach ($device_arr as $device) {
				$this->astman->set_global($state . "(Custom:DEV" . $cf_type . $device . ")", 'BUSY');
			}
		}

		return $this->astman->database_put($cf_type, $extension, $number);
	}
	function delAllNumbersByExtension($exten){
		$types = ['CFU','CFB','CF'];
		foreach($types as $type){
			$this->delNumberByExtension($exten,$type);
		}
	}

	function delNumberByExtension($extension, $type = "CF") {
		$cf_type = match ($type) {
      'CFU' => 'CFU',
      'CFB' => 'CFB',
      default => 'CF',
  };

		if ($cf_type == 'CF') {
			$state = $this->FreePBX->Config->get('AST_FUNC_DEVICE_STATE');
			$this->astman->set_global($state . "(Custom:" . $cf_type . $extension . ")", 'NOT_INUSE');
			$devices = $this->astman->database_get("AMPUSER", $extension . "/device");
			$device_arr = explode('&', (string) $devices);
			foreach ($device_arr as $device) {
				$this->astman->set_global($state . "(Custom:DEV" . $cf_type . $device . ")", 'NOT_INUSE');
			}
		}
		return $this->FreePBX->astman->database_del($cf_type, $extension);
	}

	function setRingtimerByExtension($extension, $ringtimer = 0) {
		if ($ringtimer > 120) {
			$ringtimer = 120;
		} else if ($ringtimer < -1) {
			$ringtimer = -1;
		}
		return $this->astman->database_put("AMPUSER", $extension . '/cfringtimer', $ringtimer);
	}

	function getRingtimerByExtension($extension) {
		return $this->astman->database_get('AMPUSER', $extension . '/cfringtimer');
	}
}

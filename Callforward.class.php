<?php
// vim: set ai ts=4 sw=4 ft=php:

class Callforward implements BMO {

	public function __construct($freepbx = null) {
		if ($freepbx == null) {
			throw new Exception("Not given a FreePBX Object");
		}

		$this->FreePBX = $freepbx;
		$this->db = $freepbx->Database;
	}

	public function doConfigPageInit($page) {

	}

	public function install() {

	}
	public function uninstall() {

	}
	public function backup(){

	}
	public function restore($backup){

	}
	public function genConfig() {

	}

	function getStatusesByExtension($extension) {
		//CFU - Unconditional
		//CFB - Busy
		//CF - Unavailable
		$cf_type = array('CF','CFU','CFB');
		$users = array();

		foreach ($cf_type as $value) {
			$users[$value] = $this->FreePBX->astman->database_get($value, $extension);
		}

		return $users;
	}

	function getNumberByExtension($extension, $type = 'CF') {
		switch ($type) {
			case 'CFU':
				$cf_type = 'CFU';
			break;
			case 'CFB':
				$cf_type = 'CFB';
			break;
			case 'CF':
			default:
				$cf_type = 'CF';
			break;
		}

		$number = $this->FreePBX->astman->database_get($cf_type, $extension);
		return is_numeric($number) ? $number : false;
	}

	function setNumberByExtension($extension, $number, $type = "CF") {
		switch ($type) {
			case 'CFU':
				$cf_type = 'CFU';
			break;
			case 'CFB':
				$cf_type = 'CFB';
			break;
			case 'CF':
			default:
				$cf_type = 'CF';
			break;
		}

		return $this->FreePBX->astman->database_put($cf_type, $extension, $number);
	}

	function delNumberByExtension($extension, $type = "CF") {
		switch ($type) {
			case 'CFU':
				$cf_type = 'CFU';
			break;
			case 'CFB':
				$cf_type = 'CFB';
			break;
			case 'CF':
			default:
				$cf_type = 'CF';
			break;
		}
		return $this->FreePBX->astman->database_del($cf_type, $extension);
	}

	function setRingtimerByExtension($extension, $ringtimer = 0) {
		if ($ringtimer > 120) {
			$ringtimer = 120;
		} else if ($ringtimer < -1) {
			$ringtimer = -1;
		}
		return $this->FreePBX->astman->database_put("AMPUSER", $extension . '/cfringtimer', $ringtimer);
	}

	function getRingtimerByExtension($extension) {
		return $this->FreePBX->astman->database_get('AMPUSER', $extension . '/cfringtimer');
	}
}

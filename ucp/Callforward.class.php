<?php
/**
 * This is the User Control Panel Object.
 *
 * Copyright (C) 2013 Schmooze Com, INC
 * Copyright (C) 2013 Andrew Nagy <andrew.nagy@schmoozecom.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   FreePBX UCP BMO
 * @author   Andrew Nagy <andrew.nagy@schmoozecom.com>
 * @license   AGPL v3
 */
namespace UCP\Modules;
use \UCP\Modules as Modules;
#[\AllowDynamicProperties]
class Callforward extends Modules{
	protected $module = 'Callforward';
	private $user = null;
	private $userId = false;

	function __construct($Modules) {
		$this->Modules = $Modules;
		$this->user = $this->UCP->User->getUser();
		$this->userId = $this->user ? $this->user["id"] : false;
	}

	public function getWidgetList() {
		$widgetList = $this->getSimpleWidgetList();
		return $widgetList;
	}

	public function poll($data) {
		$states = [];
		foreach($data as $ext) {
			if(!$this->_checkExtension($ext)) {
				continue;
			}
			$s = ['CFU', 'CFB', 'CF'];
			foreach(['CFU', 'CFB', 'CF'] as $type) {
				$states[$ext][$type] = $this->UCP->FreePBX->Callforward->getNumberByExtension($ext,$type);
			}
		}

		return ["states" => $states];
	}

	public function getSimpleWidgetList() {
		$widgets = [];

		$extensions = $this->UCP->getCombinedSettingByID($this->userId,'Settings','assigned');

		if (!empty($extensions)) {
			foreach($extensions as $extension) {
				$data = $this->UCP->FreePBX->Core->getDevice($extension);
				if(empty($data) || empty($data['description'])) {
					$data = $this->UCP->FreePBX->Core->getUser($extension);
					$name = isset($data['name']) ?? '';
				} else {
					$name = $data['description'];
				}

				$widgets[$extension] = ["display" => $name, "hasSettings" => true, "description" => sprintf(_("Call Forwarding for %s"),$name), "defaultsize" => ["height" => 7, "width" => 1], "minsize" => ["height" => 7, "width" => 1]];
			}
		}

		if (empty($widgets)) {
			return [];
		}

		return ["rawname" => "callforward", "display" => _("Call Forwarding"), "icon" => "fa fa-arrow-right", "list" => $widgets];
	}

	public function getWidgetDisplay($id) {
		if (!$this->_checkExtension($id)) {
			return [];
		}

		$displayvars = ["extension" => $id, "CFU" => $this->UCP->FreePBX->Callforward->getNumberByExtension($id,'CFU'), "CFB" => $this->UCP->FreePBX->Callforward->getNumberByExtension($id,'CFB'), "CF" => $this->UCP->FreePBX->Callforward->getNumberByExtension($id,'CF')];

		$display = ['title' => _("Call Forwarding"), 'html' => $this->load_view(__DIR__.'/views/widget.php',$displayvars)];

		return $display;
	}

	public function getSimpleWidgetSettingsDisplay($id) {
		return $this->getWidgetSettingsDisplay($id);
	}

	public function getWidgetSettingsDisplay($id) {
		if (!$this->_checkExtension($id)) {
			return [];
		}

		$displayvars = ["ringtime" => $this->UCP->FreePBX->Callforward->getRingtimerByExtension($id)];
		for($i = 1;$i<=120;$i++) {
			$displayvars['cfringtimes'][$i] = $i;
		}

		$display = ['title' => _("Call Forward"), 'html' => $this->load_view(__DIR__.'/views/settings.php',$displayvars)];

		return $display;
	}


		/**
	 * Determine what commands are allowed
	 *
	 * Used by Ajax Class to determine what commands are allowed by this class
	 *
	 * @param string $command The command something is trying to perform
	 * @param string $settings The Settings being passed through $_POST or $_PUT
	 * @return bool True if pass
	 */
	function ajaxRequest($command, $settings) {
		if(!$this->_checkExtension($_POST['ext'])) {
			return false;
		}
		return match ($command) {
      'settings' => true,
      default => false,
  };
	}

	/**
	 * The Handler for all ajax events releated to this class
	 *
	 * Used by Ajax Class to process commands
	 *
	 * @return mixed Output if success, otherwise false will generate a 500 error serverside
	 */
	function ajaxHandler() {
		$return = ["status" => false, "message" => ""];
		switch($_REQUEST['command']) {
			case 'settings':
				if(isset($_POST['type'])) {
					if($_POST['type'] == 'ringtimer') {
						$this->UCP->FreePBX->Callforward->setRingtimerByExtension($_POST['ext'],$_POST['value']);
					} else {
						if(isset($_POST['value'])) {
							$this->UCP->FreePBX->Callforward->setNumberByExtension($_POST['ext'],$_POST['value'],$_POST['type']);
						} else {
							$this->UCP->FreePBX->Callforward->delNumberByExtension($_POST['ext'],$_POST['type']);
						}
					}
				}
				return ["status" => true, "alert" => "success", "message" => _('Call Forwarding Has Been Updated!')];
				break;
			default:
				return $return;
			break;
		}
	}

	private function _checkExtension($extension) {
		$extensions = $this->UCP->getCombinedSettingByID($this->userId,'Settings','assigned');
		$extensions = is_array($extensions) ? $extensions : [];
		return in_array($extension,$extensions);
	}
}

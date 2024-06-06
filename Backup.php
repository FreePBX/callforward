<?php
namespace FreePBX\modules\Callforward;
use FreePBX\modules\Backup as Base;
class Backup Extends Base\BackupBase{
	public function runBackup($id,$transaction){
		$cf = $this->FreePBX->Callforward;
		$configs = [
			'features' => $this->dumpFeatureCodes()
		];
		$users = $this->FreePBX->Core->getAllUsers();
		$users = array_column($users,'extension');
		$devices = $this->FreePBX->Core->getAllDevicesByType();
		$devices = array_column($devices,'id');
		$devices = array_merge($devices, $users);
		foreach($devices as $exten){
			$configs['data'][$exten] = [
				'numbers' => [
					'CF' => $cf->getNumberByExtension($exten,'CF'),
					'CFU' => $cf->getNumberByExtension($exten,'CFU'),
					'CFB' => $cf->getNumberByExtension($exten,'CFB'),
				],
				'ringtimer' => $cf->getRingtimerByExtension($exten),
			];
		}
		$this->addConfigs($configs);
	}
}

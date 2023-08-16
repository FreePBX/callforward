<?php
namespace FreePBX\modules\Callforward;
use FreePBX\modules\Backup as Base;
class Restore Extends Base\RestoreBase{

	public function runRestore(){
		$configs = $this->getConfigs();
		global $astman;
		$astman->database_deltree("CF");
		$cf = $this->FreePBX->Callforward;
      	if(array_key_exists('data', $configs)) {
          	foreach($configs['data'] as $k => $v){
                $cf->setMultipleNumberByExten($k,$v['numbers']);
                $cf->setRingTimerByExtension($k,$v['ringtimer']);
            }
        }
		$this->importFeatureCodes($configs['features']);
	}
	public function processLegacy($pdo, $data, $tables, $unknownTables){
		$cf = $this->FreePBX->Callforward;
		$astdb =  $data['astdb'];
		if (isset($astdb['CF'])) {
			foreach($astdb['CF'] as $exten => $val){
				$cf->setNumberByExtension($exten, $val, 'CF');
			}
		}
		if (isset($astdb['CFU'])) {
			foreach ($astdb['CFU'] as $exten => $val) {
				$cf->setNumberByExtension($exten, $val, 'CFU');
			}
		}
		if (isset($astdb['CFB'])) {
			foreach ($astdb['CFB'] as $exten => $val) {
				$cf->setNumberByExtension($exten, $val, 'CFB');
			}
		}
		if(isset($astdb['AMPUSER'])){
			foreach ($astdb['AMPUSER'] as $key => $value) {
				if(!str_contains((string) $key, 'ringtimer')){
					continue;
				}
				$parts = explode('/', (string) $key);
				if($parts[1] !== 'ringtimer'){
					continue;
				}
				$cf->setRingTimerByExtension($parts[0], $value);
			}
		}

		$this->restoreLegacyFeatureCodes($pdo);
	}
}

<?php
namespace FreePBX\modules\Callforward;
use FreePBX\modules\Backup as Base;
class Restore Extends Base\RestoreBase{
 
  public function runRestore($jobid){
    $configs = $this->getConfigs();
    $cf = $this->FreePBX->Callforward;
    foreach($configs as $conf){
      foreach($conf as $k => $v){
       $cf->setMultipleNumberByExten($k,$v['numbers']);
       $cf->setRingTimerByExtension($k,$v['ringtimer']);
      }
    }
  }
  public function processLegacy($pdo, $data, $tables, $unknownTables, $tmpfiledir){
    $cf = $this->FreePBX->Callforward;
    $astdb = $this->getAstDb($tmpfiledir . '/astdb');
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
        if(strpos($key, 'ringtimer') === false){
          continue;
        }
        $parts = explode('/', $key);
        if($parts[1] !== 'ringtimer'){
          continue;
        }
        $cf->setRingTimerByExtension($parts[0], $value);
      }
    }
    return $this;
  }
}
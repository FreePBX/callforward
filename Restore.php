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
}
<?php


// Unconditional Call Forwarding
$cfaa = _("Call Forward All Activate");
$fcc = new featurecode('callforward', 'cfon');
$fcc->setDescription($cfaa);
$fcc->setDefault('*72');
$fcc->update();
unset($fcc);

$cfad = _("Call Forward All Deactivate");
$fcc = new featurecode('callforward', 'cfoff');
$fcc->setDescription($cfad);
$fcc->setDefault('*73');
$fcc->update();
unset($fcc);

$cfapd = _("Call Forward All Prompting Deactivate");
$fcc = new featurecode('callforward', 'cfoff_any');
$fcc->setDescription($cfad);
$fcc->setDefault('*74');
$fcc->update();
unset($fcc);

// Call Forward on Busy
$cfba = _("Call Forward Busy Activate");
$fcc = new featurecode('callforward', 'cfbon');
$fcc->setDescription($cfba);
$fcc->setDefault('*90');
$fcc->update();
unset($fcc);

$cfbd = _("Call Forward Busy Deactivate");
$fcc = new featurecode('callforward', 'cfboff');
$fcc->setDescription($cfbd);
$fcc->setDefault('*91');
$fcc->update();
unset($fcc);

$cfbpd = _("Call Forward Busy Prompting Deactivate");
$fcc = new featurecode('callforward', 'cfboff_any');
$fcc->setDescription($cfbpd);
$fcc->setDefault('*92');
$fcc->update();
unset($fcc);

// Call Forward on No Answer/Unavailable (i.e. phone not registered)
$cfnaua = _("Call Forward No Answer/Unavailable Activate");
$fcc = new featurecode('callforward', 'cfuon');
$fcc->setDescription($cfnaua);
$fcc->setDefault('*52');
$fcc->update();
unset($fcc);

$cfnaud = _("Call Forward No Answer/Unavailable Deactivate");
$fcc = new featurecode('callforward', 'cfuoff');
$fcc->setDescription($cfnaud);
$fcc->setDefault('*53');
$fcc->update();
unset($fcc);

?>
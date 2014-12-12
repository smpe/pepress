<?php
$workingDir = realpath(dirname(__FILE__) . '/../');
require $workingDir . '/library/smpe/bootstrap.php';
smpe_bootstrap::run($workingDir);

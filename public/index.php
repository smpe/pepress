<?php

$workingDir = realpath(dirname(__FILE__) . '/../');
require $workingDir . '/library/Smpe/Mvc/Bootstrap.php';
Smpe_Mvc_Bootstrap::run($workingDir);

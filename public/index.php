<?php

$workingDir = realpath(dirname(__FILE__) . '/../');
require $workingDir . '/library/smpe/mvc/bootstrap.php';
Smpe_Mvc_Bootstrap::run($workingDir);

<?php
// Copyright 2015 The Smpe Authors. All rights reserved.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.

$workingDir = realpath(dirname(__FILE__));
require $workingDir . '/library/Smpe/Bootstrap.php';
require $workingDir . '/library/Smpe/UnitTest.php';

if(count($argv) < 2){
    echo "Error: \$argv empty.\r\n";
    echo "Example: php -f test.php controller Support\r\n";
    exit();
}

Smpe_UnitTest::init($workingDir, $argv);

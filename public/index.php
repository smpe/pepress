<?php
// Copyright 2015 The Smpe Authors. All rights reserved.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.

$workingDir = realpath(dirname(__FILE__) . '/../');
require $workingDir . '/library/Smpe/Application.php';
Smpe_Application::init($workingDir);

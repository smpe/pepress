<?php
// Copyright 2015 The Smpe Authors. All rights reserved.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.

class Support_Help extends Smpe_Model
{
    /**
     * @var Smpe_DbInterface
     */
    protected static $data = null;

    /**
     * @var string
     */
    protected static $module = 'Support';

    /**
     * @var string
     */
    protected static $table  = 'help';

    /**
     * @var string
     */
    protected static $primary = 'HelpID';

    /**
     * @var array
     */
    protected static $joins = array(
        'b' => ' INNER JOIN help_revision b ON b.HelpID = a.HelpID ',
    );
}
<?php
// Copyright 2015 The Smpe Authors. All rights reserved.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.

class Support_HelpRevision
{
    /**
     * @var Smpe_Db_Interface
     */
    private static $data = null;

    /**
     * @return Smpe_Db_Interface
     */
    public static function data()
    {
        if(is_null(self::$data)){
            self::$data = new Smpe_Db_Mysql('Support', 'help_revision', 'HelpRevisionID');
        }

        return self::$data;
    }
}
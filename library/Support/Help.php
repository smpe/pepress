<?php
// Copyright 2015 The Smpe Authors. All rights reserved.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.

class Support_Help
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
            // The definition of the associated table
            $joins = array(
                'b' => ' INNER JOIN help_revision b ON b.HelpID = a.HelpID ',
            );

            // Data Access Objects
            self::$data = new Smpe_Db_Mysql('Support', 'help', 'HelpID', $joins);
        }

        return self::$data;
    }
}
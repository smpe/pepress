<?php
// Copyright 2015 The Smpe Authors. All rights reserved.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.

class I18in_Sentence extends Smpe_Model
{
    /**
     * @var Smpe_DbInterface
     */
    protected static $data = null;

    /**
     * @var string
     */
    protected static $module = 'I18in';

    /**
     * @var string
     */
    protected static $table  = 'sentence';

    /**
     * @var string
     */
    protected static $primary = 'SentenceID';

    /**
     * @var array
     */
    protected static $joins = array();
}
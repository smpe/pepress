<?php
// Copyright 2015 The Smpe Authors. All rights reserved.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.

class Smpe_Locale
{
	/**
	 * @param string $str
	 * @param string $origin
	 * @return string
	 */
	public static function parse($str, $origin = '') {
		return $str.$origin;
	}
}
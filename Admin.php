<?php
/**
 * 
 * AddScript plugin for Typesetter CMS
 * Authors: Florin-Cătălin Tofan, Jürgen Krausz
 * Copyright (c) 2019 Florin-Cătălin Tofan
 * License: MIT
 * 
 */

namespace Addon\AddScript;

defined('is_running') or die('Not an entry point...');


class Admin extends \Addon\AddScript\Common
{

	static function AdminPage() {
		echo 'Admin Page, will follow';
	}


}

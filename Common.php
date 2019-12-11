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


class Common
{

	public static $sectionType		= 'addscript_section';

	public static $global_scripts	= array();

	public static $i18n;


	static function GetTranslations() {
		global $config, $addonPathCode;
		$lang_file = $addonPathCode . '/i18n/' . $config['language'] . '.php';
		if( file_exists($lang_file) ){
			include $lang_file;
			$msg = 'lang_file loaded (' . $lang_file . ')';
		}else{
			include $addonPathCode . '/i18n/en.php'; // fallback to english
			$msg = 'lang_file does not exists (' .  $lang_file . ')';
		}
		self::$i18n = $i18n;
		return $msg;
	}


	static function GetGlobalScripts() {
		global $addonPathData;
		if( file_exists($addonPathData . '/global_scripts.php') ){
			include $addonPathData . '/global_scripts.php';
			self::$global_scripts = $global_scripts;
			return true;
		}
		return false;
	}


}

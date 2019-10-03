<?php 
/* 
######################################################################
AddScript - PHP Plugin for Typesetter CMS 
Author: Florin-Cătălin Tofan
Copyright (c) 2019 Florin-Cătălin Tofan, MIT License (MIT)
Date: 01.10.2019
Version 1.0
  This is my first plugin for Typesetter CMS (https://github.com/Typesetter/Typesetter). As the documentation was scarce, I used ppeterka's EasyMark plugin as the main source of information (https://github.com/ppeterka/easymark), MIT license. 
  The AddScript plugin can be used to enter source code directly into the page. CKEditor3.4, the basic editor of Typesetter5.1 does not allow this, but it can be configured to allow editing, with certain limitations.
  However, the AddScript plugin makes it much easier to insert any code without modifying it in any way. For example, you can easily insert any Facebook script.
  After installing the plugin it can be easily inserted into the page, similar to other objects.
  The benefit of such a plugin is considerable.
  The disadvantage is that it requires great attention, as a malfunctioning script can ruin the entire page. So it is a useful script especially for those who have programming knowledge. Use it at your own risk!
  zenodo
######################################################################
*/

defined('is_running') or die('Not an entry point...');


class AddScript
{
	public static $sectionType = 'addscript_section';

	function SectionTypes( $section_types) {
		$section_types[self::$sectionType] = array();
		$section_types[self::$sectionType]['label'] = 'AddScript';
		return $section_types;
	}

	function SectionToContent($section_data) {
		global $addonPathCode,$addonPathData;
		return $section_data;
	}
	
	function DefaultContent($default_content,$type) {
		if( $type == self::$sectionType ) {
			$section = array();
			$section['content'] = "Adauga script";
			return $section;
		}
		return $default_content;
	}

	
	function SaveSection($return,$section,$type) {
		if( $type != self::$sectionType ) {
		  return $return;
		}
		global $page;
		$content =& $_POST['gpcontent'];
		$page->file_sections[$section]['content'] = $content;
		return true;
	}
	
	
	function GenerateContent_Admin() {
		global $addonFolderName, $page;
		static $done = false;
		if ($done || !common::LoggedIn()) { return; }
		$done = true;
	}
	
	
	function InlineEdit_Scripts($scripts,$type) {
		global $addonPathCode;
		
		if( $type != self::$sectionType ) {
		  return $scripts;
		}
		
		$scripts[] = $addonPathCode.'/js/edit.js';
		return $scripts; 
	}
}



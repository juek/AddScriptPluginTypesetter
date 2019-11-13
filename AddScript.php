<?php 
/* 
######################################################################
AddScript - PHP Plugin for Typesetter CMS 
Author: Florin-Cătălin Tofan
Copyright (c) 2019 Florin-Cătălin Tofan, MIT License (MIT)
Date: 01.10.2019
Version 1.0

  This is my first plugin for Typesetter CMS (https://github.com/Typesetter/Typesetter). 
  As the documentation was scarce, I used ppeterka's EasyMark plugin as the main source 
  of information (https://github.com/ppeterka/easymark), MIT license. 
  The AddScript plugin can be used to enter source code directly into the page. 
  CKEditor, the basic editor of Typesetter5.1 does not allow this, but it can be configured 
  to allow editing, with certain limitations. However, the AddScript plugin makes it much 
  easier to insert any code without modifying it in any way. 
  For example, you can easily insert any Facebook script.
  After installing the plugin it can be easily inserted into the page, similar to other objects.
  The benefit of such a plugin is considerable.
  The disadvantage is that it requires great attention, as a malfunctioning script can ruin the entire page. 
  So it is a useful script especially for those who have programming knowledge. 
  Use it at your own risk!
  zenodo

######################################################################
*/

defined('is_running') or die('Not an entry point...');


class AddScript
{
	public static $sectionType = 'addscript_section';


	static function GetHead() {
		\gp\tool\Plugins::css('css/user.css');
		if( !\gp\tool::LoggedIn() ){
			return;
		}
		\gp\tool\Plugins::css('css/admin.css', false);
		// \gp\tool\Plugins::js('js/AddScript_admin.js', false);
	}


	static function SectionTypes($section_types) {
		$section_types[self::$sectionType] = array();
		$section_types[self::$sectionType]['label'] = 'AddScript';
		return $section_types;
	}


	static function NewSections($links){
		global $addonRelativeCode;
		foreach( $links as $key => $link ){
			$match = is_array($link[0]) ? implode('-', $link[0]) : $link[0];
			if( $match ==  self::$sectionType ){
				$links[$key][1] = $addonRelativeCode . '/icons/ui-icon.png'; 
				break;
			}
		}
		return $links;
	}


	static function SectionToContent($section_data) {
		global $page;

		if( empty($section_data['script']) ){
			return $section_data;
		}

		switch( $section_data['script_type'] ){
			case 'jQuery':
				$page->jQueryCode .= "\n/* AddScript section START */\n" 
					. $section_data['script'] 
					. "\n/* AddScript section END */\n";
				break;

			case 'js':
				$page->head_script .= "\n/* AddScript section START */\n" 
					. $section_data['script']
					. "\n/* AddScript section END */\n";
				break;

			case 'url':
				$page->head .= "\n<!-- AddScript section START -->\n" 
					. '<script src="' . $section_data['script'] . '"></script>' 
					. "\n<!-- AddScript section END -->\n"; 
				break;

			case 'raw':
				$section_data['content'] = "<!-- AddScript Section Start -->\n" 
					. $section_data['script'] 
					. "\n<!-- AddScript section END -->";
				break;

		}

		return $section_data;
	}


	static function ToggleClass($section_data, $class, $toggle=true) {
		if( empty($section_data['attributes']['class']) ){
			if( $toggle ){
				$section_data['attributes'] = array( 'class' => $class );
				return $section_data;
			}
		}
		$classes = explode(' ', $section_data['attributes']['class']);
		$classes = array_map('trim', $classes);
		if( $toggle && !in_array($class, $classes) ){
			$classes[] = $class;
		}
		if( !$toggle && in_array($class, $classes) ){
			$key = array_search($class, $classes);
			unset($classes[$key]);
		}
		$section_data['attributes']['class'] = implode(' ', $classes);
		return $section_data;
	}


	static function DefaultContent($default_content, $type) {
		if( $type != self::$sectionType ){
			return $default_content;
		}
		$section = array();
		$section['content']			= '<!-- AddScript Section -->';
		$section['script_type']		= 'js'; // 'js' | 'jQuery' | 'url' | 'raw'
		$section['script']			= '';
		return $section;
	}


	static function SaveSection($return, $section, $type) {
		global $page;
		if( $type != self::$sectionType ){
			return $return;
		}

		$section_data 					= $page->file_sections[$section];
		$section_data['script_type']	= trim($_POST['script_type']);
		$section_data['script']			= trim($_POST['script']);

		$section_data = self::ToggleClass($section_data, 'addscript-section-empty', false);
		$section_data = self::ToggleClass($section_data, 'addscript-section-raw-output', false);
		if( empty($script) ){
			$section_data['content'] = '<!-- EMPTY AddScript Section -->';
			$section_data = self::ToggleClass($section_data, 'addscript-section-empty', true);
		}elseif( $section_data['script_type'] == 'raw' ){
			$section_data = self::ToggleClass($section_data, 'addscript-section-raw-output', true);
		}else{
			$section_data['content'] = '<!-- AddScript Section -->';
		}


		$page->file_sections[$section] = $section_data;

		return true;
	}


	static function InlineEdit_Scripts($scripts, $type) {
		global $addonRelativeCode, $addonFolderName;;

		if( $type != self::$sectionType ){
		  return $scripts;
		}

		$addonBasePath = (strpos($addonRelativeCode, 'addons/') > 0) 
			? '/addons/' . $addonFolderName 
			: '/data/_addoncode/' . $addonFolderName;
		echo "\n" . 'AddScript_base = "' . $addonBasePath . '";' . "\n";

		$scripts[] = $addonRelativeCode . '/thirdparty/codemirror/lib/codemirror.min.js';
		$scripts[] = $addonRelativeCode . '/thirdparty/codemirror/mode/xml/xml.min.js';
		$scripts[] = $addonRelativeCode . '/thirdparty/codemirror/mode/javascript/javascript.min.js';
		$scripts[] = $addonRelativeCode . '/thirdparty/codemirror/mode/css/css.min.js';
		$scripts[] = $addonRelativeCode . '/thirdparty/codemirror/mode/htmlmixed/htmlmixed.min.js';
		$scripts[] = $addonRelativeCode . '/thirdparty/codemirror/addon/display/placeholder.min.js';
		$scripts[] = $addonRelativeCode . '/js/edit.js';
		return $scripts; 
	}

}

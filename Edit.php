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


class Edit extends \Addon\AddScript\Common
{

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


	static function ToggleClass($section_data, $class, $toggle=true) {
		if( empty($section_data['attributes']['class']) ){
			if( $toggle ){
				$section_data['attributes'] = array( 'class' => $class );
				return $section_data;
			}
		}
		$classes = array();
		if( !empty($section_data['attributes']['class']) ){
			$classes = explode(' ', $section_data['attributes']['class']);
		}
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


	static function GetDefaultContent($default_content, $type) {
		if( $type != self::$sectionType ){
			return $default_content;
		}
		$section = array();
		$section['content']			= '<!-- AddScript Section -->';
		$section['script_type']		= 'js'; // 'js' | 'jQuery' | 'url' | 'raw'
		$section['script_attrs']	= array(); // [['async'] [,'defer']]
		$section['linked_to']		= array(); // [[global script id(s)]]
		$section['script']			= '';
		$section['gp_label']		= 'AddScript';
		$section['gp_color']		= '#777';
		return $section;
	}


	static function SaveSection($return, $section, $type) {
		global $page;

		if( $type != self::$sectionType ){
			return $return;
		}

		$section_data					= $page->file_sections[$section];
		$section_data['script_type']	= trim($_POST['script_type']);
		$section_data['script']			= trim($_POST['script']);

		if( !empty($_POST['script_attrs']) ){
			$section_data['script_attrs']	= $_POST['script_attrs'];
		}else{
			unset($section_data['script_attrs']);
		}
		if( !empty($_POST['linked_to']) ){
			$section_data['linked_to']		= $_POST['linked_to'];
		}else{
			unset($section_data['linked_to']);
		}

		$section_data = self::ToggleClass($section_data, 'addscript-section-empty', false);
		$section_data = self::ToggleClass($section_data, 'addscript-section-raw-output', false);
		if( empty($section_data['script']) ){
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

		self::GetTranslations();
		echo "\n" . 'AddScript_i18n = ' . json_encode(self::$i18n) . ';' . "\n";

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
		$scripts[] = $addonRelativeCode . '/thirdparty/codemirror/addon/show-invisibles/show-invisibles.js';
		$scripts[] = $addonRelativeCode . '/js/edit.js';
		// $scripts[] = array( 'code' => '<style></style>' );
		return $scripts;
	}


}

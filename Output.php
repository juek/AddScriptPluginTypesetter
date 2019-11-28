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


class Output extends \Addon\AddScript\Common
{

	static function GetHead() {
		\gp\tool\Plugins::css('css/user.css');
		if( !\gp\tool::LoggedIn() ){
			return;
		}
		\gp\tool\Plugins::css('css/admin.css', false);
	}


	static function SectionTypes($section_types) {
		$section_types[self::$sectionType] = array();
		$section_types[self::$sectionType]['label'] = 'AddScript';
		return $section_types;
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

}

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
		global $page;
		// msg('$page = ' . pre(get_object_vars($page)));
		\gp\tool\Plugins::css('css/user.css');

		self::GetGlobalScripts();
		foreach( self::$global_scripts as $script_id => $script ){

			if( !isset($script['load_in']) || !isset($script['code']) ){
				continue;
			}

			$data_attr = ' data-addscript-global="' . htmlspecialchars($script_id) . '"';

			switch( $script['type'] ){
				case 'url':
					$script_url = $script['code'];

					switch( $script['load_in'] ){
						case 'head':
							$page->head .= "<script\n" . ' src="' . $script_url . '"' // OMFG how hackish! <script\n will not be catched by the regex /<script.*?</script>/ hence not be moved out of <head>
								. ( isset($script['attrs']) && in_array('async', $script['attrs']) ? ' async="async"' : '' )
								. ( isset($script['attrs']) && in_array('defer', $script['attrs']) ? ' defer="defer"' : '' )
								. $data_attr . '></script>';
							break;

						case 'body':
							$page->head .= '<script src="' . $script_url . '"'
								. ( isset($script['attrs']) && in_array('async', $script['attrs']) ? ' async="async"' : '' )
								. ( isset($script['attrs']) && in_array('defer', $script['attrs']) ? ' defer="defer"' : '' )
								. $data_attr . '></script>';
							break;

						case 'jQuery':
							$page->jQueryCode .= "\n/* AddScript global [" . htmlspecialchars($script_id) . "] START */\n"
								. '$.getScript("' . $script_url . '");'
								. "\n/* AddScript END */\n";
							break;

						case 'winLoad':
							$page->head_script .= "\n/* AddScript global [" . htmlspecialchars($script_id) . "] START */\n"
								. '$(window).on("load", function(){' . "\n"
								. '  $.getScript("' . $script_url . '");' . "\n"
								. '});'
								. "\n/* AddScript global END */\n";
							break;

					}
					break;

				case 'js':
					switch( $script['load_in'] ){
						case 'head':
							$page->head .= "<script\n" . $data_attr . '>' // same hack again :)
								. $script['code']
								. '</script>';
							break;
			
						case 'body':
							$page->head_script .= "\n/* AddScript global [" . htmlspecialchars($script_id) . "] START */\n"
								. $script['code']
								. "\n/* AddScript global END */\n";
							break;

						case 'jQuery':
							$page->jQueryCode .= "\n/* AddScript global [" . htmlspecialchars($script_id) . "] START */\n"
								. $script['code']
								. "\n/* AddScript global END */\n";
							break;

						case 'winLoad':
							$page->head_script .= "\n/* AddScript global [" . htmlspecialchars($script_id) . "] START */\n"
								. '$(window).on("load", function(){' . "\n"
								. $script['code'] . "\n"
								. '});'
								. "\n/* AddScript global END */\n";
							break;
					}
					break;
			}
		}

		if( \gp\tool::LoggedIn() ){
			\gp\tool\Plugins::css('css/admin.css', false);
		}
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

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

	static $admin_url;

	static function AdminPage() {
		global $langmessage, $page;

		\gp\tool\Plugins::css('css/admin_page.css', false);

		self::GetTranslations();
		self::GetGlobalScripts();

		self::$admin_url = \gp\tool::GetUrl('Admin_AddScript');

		$cmd = \gp\tool::GetCommand();

		switch( $cmd ){

			case 'edit_script':
				if ( isset($_REQUEST['script_id']) ){
					self::EditScript($_REQUEST['script_id']);
				}
				return;

			case 'new_script':
				self::EditScript(false);
				return;

			case 'delete_script':
				$page->ajaxReplace = array();
				if ( isset($_REQUEST['script_id']) ){
					self::DeleteScript($_REQUEST['script_id']);
					$page->ajaxReplace = array();
					$page->ajaxReplace[] = array(
						'replace', // DO
						'tr[data-script-id="' . htmlspecialchars($_REQUEST['script_id']) . '"]', // SELECTOR
						'', // CONTENT
					);
				}
				return 'return';

			case 'save_script':
				self::SaveScript();

		}

		self::ShowScripts();
	}



	private static function DeleteScript($script_id) {
		if( isset(self::$global_scripts[$script_id]) ){
			unset(self::$global_scripts[$script_id]);
			return self::SaveAllScripts();
		}
		return false;
	}



	private static function SaveScript() {
		global $langmessage;

		if ( !isset($_POST['script_id']) ){
			msg($langmessage['OOPS'] . ' (missing script id)');
			return false;
		}
		$script_id = trim($_POST['script_id']);

		$script = array();
		$script['label']	= trim($_POST['script_label']);
		$script['load_in']	= trim($_POST['script_load_in']);
		$script['type']		= trim($_POST['script_type']);

		$script['attrs']		= array();
		if( isset($_POST['script_attr_async']) ){
			$script['attrs'][] = 'async';
		}
		if( isset($_POST['script_attr_defer']) ){
			$script['attrs'][] = 'defer';
		}
		if( isset($_POST['script_req_opt_in']) ){
			$script['req_opt_in'] = true;
		}
		$script['code'] 		= trim($_POST['script_code']);

		self::$global_scripts[$script_id] = $script;

		if( self::SaveAllScripts() ){
			msg($langmessage['SAVED']);
			return true;
		}else{
			msg($langmessage['OOPS'] . ' (scripts not saved)');
			return false;
		}
	}



	private static function SaveAllScripts() {
		global $addonPathData;
		return \gp\tool\Files::SaveData(
			$addonPathData . '/global_scripts.php',
			'global_scripts',
			self::$global_scripts
		);
	}



	private static function ShowScripts() {
		global $langmessage;

		echo '<h2 class="hqmargin">AddScript &raquo; ' . self::$i18n['Admin_AddScript'] . '</h2><br/>';

		echo '<table class="bordered striped" style="width:100%;">';
		echo '<thead>';
		echo '<tr>';
		echo '<th>' . self::$i18n['script_label'] . '</th>';
		echo '<th>' . $langmessage['Content Type'] . '</th>';
		echo '<th>' . self::$i18n['load_in'] . '</th>';
		echo '<th>' . $langmessage['Attribute'] . '</th>';
		echo '<th>' . self::$i18n['opt_in_required'] . '</th>';
		echo '<th style="text-align:right;">' . $langmessage['options'] . '</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';

		foreach( self::$global_scripts as $script_id => $script_data ){
			echo self::GetTableRow($script_id, $script_data);
		}
		echo '</tbody>';

		echo '<tfoot>';
		echo '<tr>';
		echo '<td colspan="6">';
		echo \gp\tool::Link(
			'Admin_AddScript',
			self::$i18n['add_script'], // $langmessage['add'],
			'cmd=new_script',
			array('style' => 'font-weight:bold;')
		);
		echo '</th>';
		echo '</tr>';

		echo '</tfoot>';

		echo '</table>';

	}



	private static function GetTableRow($script_id, $script){
		global $langmessage;

		if( !is_array($script) ){
			return '';
		}

		ob_start();

		echo '<tr data-script-id="' . htmlspecialchars($script_id) . '">';

		echo '<td><strong>' . htmlspecialchars($script['label']) . '</strong></td>';

		$code_type = '';
		switch ($script['type']){
			case 'js':
				$code_type = self::$i18n['types']['js'];
				break;
			case 'url':
				$code_type = self::$i18n['types']['url'];
				break;
		}
		echo '<td>' . htmlspecialchars($code_type) . '</td>';

		$load_in = '';
		switch ($script['load_in']){
			case 'head':
				$load_in = '&lt;head&gt;';
				break;
			case 'body':
				$load_in = '&lt;body&gt;';
				break;
			case 'jQuery':
				$load_in = 'jQuery (DOM ready)';
				break;
			case 'winLoad':
				$load_in = 'window.load';
				break;
		}
		echo '<td>' . $load_in . '</td>';

		echo '<td>' . implode(', ', $script['attrs']) . '</td>';

		echo '<td>' . (isset($script['req_opt_in']) ? $langmessage['Yes'] : $langmessage['No']) . '</td>';

		echo '<td style="text-align:right;">';
		echo \gp\tool::Link(
			'Admin_AddScript',
			'<i class="fa fa-pencil"></i> ' . $langmessage['edit'],
			'cmd=edit_script&script_id=' . $script_id,
			array('class' => 'gpbutton')
		);
		echo ' ';
		echo \gp\tool::Link(
			'Admin_AddScript',
			'<i class="fa fa-trash"></i> ' . $langmessage['delete'],
			'cmd=delete_script&script_id=' . $script_id,
			array(
				'data-cmd'	=> 'postlink',
				'class'		=> 'gpconfirm gpbutton',
				'title'		=> sprintf($langmessage['generic_delete_confirm'], $script['label']),
			) 
		);
		echo '</td>';

		echo '</tr>';

		return ob_get_clean();
	}



	private static function EditScript($script_id) {
		global $langmessage, $page;

		$page->head_script .= "\n" . 'AddScript_i18n = ' . json_encode(self::$i18n) . ';' . "\n";

		\gp\tool\Plugins::css('thirdparty/codemirror/lib/codemirror.min.css', false);

		\gp\tool\Plugins::js('thirdparty/codemirror/lib/codemirror.min.js', false);
		\gp\tool\Plugins::js('thirdparty/codemirror/mode/xml/xml.min.js', false);
		\gp\tool\Plugins::js('thirdparty/codemirror/mode/javascript/javascript.min.js', false);
		// \gp\tool\Plugins::js('thirdparty/codemirror/mode/css/css.min.js', false);
		// \gp\tool\Plugins::js('thirdparty/codemirror/mode/htmlmixed/htmlmixed.min.js', false);
		\gp\tool\Plugins::js('thirdparty/codemirror/addon/display/placeholder.min.js', false);
		\gp\tool\Plugins::js('thirdparty/codemirror/addon/show-invisibles/show-invisibles.js', false);

		\gp\tool\Plugins::js('js/admin_page.js', false);

		echo '<h2 class="hqmargin">AddScript &raquo; ';
		echo \gp\tool::Link(
				'Admin_AddScript',
				self::$i18n['Admin_AddScript']
			);
		echo ' &raquo ' . $langmessage['edit'];
		echo '</h2><br/>';


		if( $script_id == false ){
			// new script, create a new id
			$script_id = \gp\tool::RandomString(6);
			$script_id_unique = false;
			while( !$script_id_unique ){
				$script_ids = array();
				foreach( self::$global_scripts as $id => $script ){
					$script_ids[] = $id;
				}
				if( in_array($script_id, $script_ids) ){
					$script_id = \gp\tool::RandomString(6);
				}else{
					$script_id_unique = true;
				}
			}
			// new script defaults
			$script = array(
				'label'			=> '',
				'type'			=> 'js',
				'load_in'		=> 'head',
				'req_opt_in'	=> false,		// requires opt-in cookie (EU GDPR)
				'code'			=> '',
				'attrs'			=> array(),		// defer, async
			);
		}else{
			if( empty(self::$global_scripts[$script_id]) ){
				echo $langmessage['OOPS'] . ' the script with id ' . htmlspecialchars($script_id) . ' does not exist.<br/>';
				echo \gp\tool::Link(
					'Admin_AddScript',
					$langmessage['back'],
					'',
					array('class' => 'gpcancel')
				);
				return false;
			}
			$script = self::$global_scripts[$script_id];
		}
		// debug('$script = ' . pre($script));


		echo '<form id="addscript_edit_form" action="' . self::$admin_url . '" method="post">';

		echo '<table class="bordered" style="width:100%;">';
		echo '<thead>';
		echo '<tr>';
		echo '<th>' . self::$i18n['script_label'] . '</th>';
		echo '<th>' . $langmessage['Content Type'] . '</th>';
		echo '<th>' . self::$i18n['load_in'] . '</th>';
		echo '<th>' . $langmessage['Attribute'] . '</th>';
		echo '<th style="text-align:right;">' . $langmessage['options'] . '</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';

		echo '<tr>';

		echo	'<td>';
		echo		'<input type="text" required="required" class="gpinput" name="script_label" ';
		echo			'value="' . htmlspecialchars($script['label']) . '" ';
		echo			'placeholder="' . htmlspecialchars(self::$i18n['script_label']) . '" ';
		echo		'/>';
		echo	'</td>';

		echo	'<td>';
		echo		'<select class="gpselect" name="script_type">';
		echo			'<option value="js"' . ($script['type'] == 'js' ? ' selected="selected"' : '') . '>' . self::$i18n['types']['js'] . '</option>';
		echo			'<option value="url"' . ($script['type'] == 'url' ? ' selected="selected"' : '') . '>' . self::$i18n['types']['url'] . '</option>';
		echo		'</select>';
		echo	'</td>';

		echo	'<td>';
		echo		'<select class="gpselect" name="script_load_in">';
		echo			'<option value="head"' . ($script['load_in'] == 'head' ? ' selected="selected"' : '') . '>&lt;head&gt;</option>';
		echo			'<option value="body"' . ($script['load_in'] == 'body' ? ' selected="selected"' : '') . '>&lt;body&gt;</option>';
		echo			'<option value="jQuery"' . ($script['load_in'] == 'jQuery' ? ' selected="selected"' : '') . '>jQuery (DOM ready)</option>';
		echo			'<option value="winLoad"' . ($script['load_in'] == 'winLoad' ? ' selected="selected"' : '') . '>window.load</option>';
		echo		'</select>';
		echo	'</td>';

		echo	'<td>';
		$disabled = $script['type'] != 'url' ? ' disabled="disabled"' : '';
		echo		'<label><input type="checkbox" name="script_attr_async" ';
		if( !empty($script['attrs']) && in_array('async', $script['attrs']) ){
			echo ' checked="checked"';
		}
		echo		$disabled;
		echo		'/>&nbsp;async</label>&nbsp; ';
		echo		'<label><input type="checkbox" name="script_attr_defer" ';
		if( !empty($script['attrs']) && in_array('defer', $script['attrs']) ){
			echo ' checked="checked"';
		}
		echo		$disabled;
		echo		'/>&nbsp;defer</label>&nbsp; ';
		echo	'</td>';

		echo	'<td style="text-align:right;">';
		echo		'<label><input type="checkbox" name="script_req_opt_in" ';
		if( !empty($script['req_opt_in']) ){
			echo ' checked="checked"';
		}
		echo		'/>&nbsp;' . self::$i18n['opt_in_required'] . '</label>';
		echo	'</td>';

		echo '</tr>';

		echo '<tr>';
		echo	'<td colspan="6" class="addscript-code-container">';
		echo		'<div class="addscript-code-before"></div>';
		echo		'<div class="addscript-code-area">';
		echo			'<textarea class="addscript-code-area" name="script_code">';
		echo			htmlspecialchars($script['code']);
		echo			'</textarea>';
		echo		'</div>';
		echo		'<div class="addscript-code-after"></div>';
		echo		'<div class="addscript-msg-area"></div>';
		echo	'</td>';
		echo '</tr>';

		echo '</tbody>';
		echo '</table>';

		echo '<br/><p>';
		echo '<input type="hidden" name="script_id" value="' . $script_id . '" />';
		echo '<input type="hidden" name="cmd" value="save_script" />';
		echo '<input type="submit" name="save" value="' . $langmessage['save'] . '" class="gpsubmit" /> ';
		echo '<input type="button" onClick="location.href=\'' . self::$admin_url . '\'" ';
		echo	' value="' . $langmessage['cancel'] . '" class="gpcancel" />';
		echo '</p>';

		echo '</form>';

	}



	/**
	 * 
	 * New filter hook as of 5.1.1-b1
	 * we may now use translated Admin Link labels
	 * 
	 */
	static function AdminLinkLabel($link_name, $link_label) {

		if( $link_name !== 'Admin_AddScript' ){
			return $link_label;
		}

		self::GetTranslations();

		if( !empty(self::$i18n['Admin_AddScript']) ){
			$link_label = self::$i18n['Admin_AddScript'];
		}

		return $link_label;
	}

}

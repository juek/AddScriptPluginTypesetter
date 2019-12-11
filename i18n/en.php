<?php 

defined('is_running') or die('Not an entry point...');

$i18n = array(
  'error'                 => 'Error',
  'warning'               => 'Warning',
  'notice'                => 'Notice',
  'invalid_url'           => 'the current URL appears to be invalid',
  'check'                 => 'Check',
  'check_again'           => 'Check again',
  'script_label'          => 'Script Label',
  'load_in'               => 'Load in&hellip;',
  'add_script'            => 'Add Script',
  'opt_in_required'       => 'Opt-in cookie required',

  'types'                 => array(
    'js'      => 'JavaScript',
    'jQuery'  => 'jQuery',
    'url'     => 'Script URL',
    'raw'     => 'Raw Output (in place)',
  ),

  'textarea_placeholder' => array(
    'js'      => 'Add custom JavaScript code here...',
    'jQuery'  => 'Add custom JavaScript or jQuery code here. Will execute when the DOM is ready...',
    'url'     => 'Add a script web URL here...',
    'raw'     => 'Add code here...',
  ),

  'Admin_AddScript'       => 'Manage Scripts', // for TS 5.1.1-b1+ AdminLinkLabel hook, see Addon.ini

);

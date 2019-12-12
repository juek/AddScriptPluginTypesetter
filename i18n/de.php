<?php 

defined('is_running') or die('Not an entry point...');

$i18n = array(
  'error'                     => 'Fehler',
  'warning'                   => 'Warnung',
  'notice'                    => 'Anmerkung',
  'invalid_url'               => 'die angegebene Adresse (URL) scheint ungültig zu sein',
  'check'                     => 'Prüfen',
  'check_again'               => 'Nochmals prüfen',
  'script_label'              => 'Script Name',
  'load_in'                   => 'Laden in&hellip;',
  'add_script'                => 'Script hinzufügen',
  'opt_in_required'           => 'Zustimmung (Opt-In Cookie) erforderlich',
  'global_scripts'            => 'Globale Scripts',
  'linked_to_global_scripts'  => 'Verbunden mit globalen Scripts',

  'types' => array(
    'js'      => 'JavaScript',
    'jQuery'  => 'jQuery',
    'url'     => 'Script URL',
    'raw'     => 'Direktausgabe (hier)',
  ),

  'textarea_placeholder' => array(
    'js'      => 'Füge deinen JavaScript Code hier ein...',
    'jQuery'  => 'Füge deinen JavaScript oder jQuery Code hier ein. Wird bei "DOM ready" ausgeführt...',
    'url'     => 'Füge deine Script Adresse (URL) hier ein...',
    'raw'     => 'Füge deinen Code hier ein (HTML, Text, Script Tag, ...)',
  ),

  'Admin_AddScript'       => 'Scripts Verwalten', // for TS 5.1.1-b1+ AdminLinkLabel hook, see Addon.ini

);

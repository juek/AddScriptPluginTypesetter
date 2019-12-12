<?php 

defined('is_running') or die('Not an entry point...');

$i18n = array(
  'error'                     => 'Errore',
  'warning'                   => 'Avvertimento',
  'notice'                    => 'Avviso',
  'invalid_url'               => 'l’URL corrente sembra non essere valido',
  'check'                     => 'Controllare',
  'check_again'               => 'Ricontrollare',
  'script_label'              => 'Etichetta dello script',
  'load_in'                   => 'Carica in&hellip;',
  'add_script'                => 'Aggiungi script',
  'opt_in_required'           => 'Richiede il cookie di opt-in',
  'global_scripts'            => 'Script globali',
  'linked_to_global_scripts'  => 'Collegato a script globali',

  'types' => array(
    'js'      => 'JavaScript',
    'jQuery'  => 'jQuery',
    'url'     => 'URL dello script',
    'raw'     => 'Output diretta (qui)',
  ),

  'textarea_placeholder' => array(
    'js'      => 'Aggiungi qui il codice JavaScript...',
    'jQuery'  => 'Aggiungi JavaScript o jQuery qui. Verrà eseguito quando il DOM è pronto...',
    'url'     => 'Aggiungi un URL web di script qui...',
    'raw'     => 'Aggiungi il codice qui...',
  ),

  'Admin_AddScript'       => 'Gestisci gli script', // for TS 5.1.1-b1+ AdminLinkLabel hook, see Addon.ini

);

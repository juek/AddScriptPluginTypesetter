<?php 

defined('is_running') or die('Not an entry point...');

$i18n = array(
  'error'                 => 'Erreur',
  'warning'               => 'Avertissement',
  'notice'                => 'Avis',
  'invalid_url'           => 'l’URL actuelle semble être invalide',
  'check'                 => 'Vérifier',
  'check_again'           => 'Revérifier',
  'script_label'          => 'Étiquette de script',
  'load_in'               => 'Charger dans&hellip;',
  'add_script'            => 'Ajouter un script',
  'opt_in_required'       => 'Cookie opt-in requis',

  'types'                 => array(
    'js'      => 'JavaScript',
    'jQuery'  => 'jQuery',
    'url'     => 'URL du script',
    'raw'     => 'Sortie (sur site)',
  ),

  'textarea_placeholder' => array(
    'js'      => 'Ajoutez un code JavaScript personnalisé ici...',
    'jQuery'  => 'Ajoutez un code JavaScript ou jQuery personnalisé ici. S’exécutera lorsque le DOM est prêt...',
    'url'     => 'Ajoutez une URL de script ici...',
    'raw'     => 'Ajoutez le code ici...',
  ),

  'Admin_AddScript'       => 'Gérer les scripts', // for TS 5.1.1-b1+ AdminLinkLabel hook, see Addon.ini

);

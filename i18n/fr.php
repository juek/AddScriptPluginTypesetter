<?php 

defined('is_running') or die('Not an entry point...');

$i18n = array(
  'error'                 => 'Erreur',
  'warning'               => 'Avertissement',
  'notice'                => 'Avis',
  'invalid_url'           => 'l’URL actuelle semble être invalide',
  'check'                 => 'Vérifier',
  'check_again'           => 'Revérifier',

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
);

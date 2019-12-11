<?php 

defined('is_running') or die('Not an entry point...');

$i18n = array(
  'error'                 => 'Помилка',
  'warning'               => 'Попередження',
  'notice'                => 'Cповіщення',
  'invalid_url'           => 'поточна URL-адреса видається недійсною',
  'check'                 => 'Перевірити',
  'check_again'           => 'Перевірити знов',
  'script_label'          => 'Мітка скрипту',
  'load_in'               => 'Завантаження&hellip;',
  'add_script'            => 'Додати скрипт',
  'opt_in_required'       => 'необхідна кука входу (Opt-in cookie)',

  'types'                 => array(
    'js'      => 'JavaScript',
    'jQuery'  => 'jQuery',
    'url'     => 'Script URL',
    'raw'     => 'Вивід як є (в коді)',
  ),

  'textarea_placeholder' => array(
    'js'      => 'Додайте користувацький JavaScript код...',
    'jQuery'  => 'Додайте користувацький JavaScript чи jQuery код (буде виконуватись після готовності DOM)...',
    'url'     => 'Додайте URL скрипту...',
    'raw'     => 'Додайте код...',
  ),

  'Admin_AddScript'       => 'Керувати скриптами', // for TS 5.1.1-b1+ AdminLinkLabel hook, see Addon.ini

);

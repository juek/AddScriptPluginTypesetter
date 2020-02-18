<?php 

defined('is_running') or die('Not an entry point...');

$i18n = array(
  'error'                 => 'Ошибка',
  'warning'               => 'Предупреждение',
  'notice'                => 'Уведомление',
  'invalid_url'           => 'текущий  URL-адрес выглядит недействительным',
  'check'                 => 'Проверить',
  'check_again'           => 'Проверить еще раз',
  'script_label'          => 'Метка скрипта',
  'load_in'               => 'Загрузка&hellip;',
  'add_script'            => 'Добавить скрипт',
  'opt_in_required'       => 'необходима кука входа (Opt-in cookie)',

  'types'                 => array(
    'js'      => 'JavaScript',
    'jQuery'  => 'jQuery',
    'url'     => 'Script URL',
    'raw'     => 'Вывод как есть (в коде)',
  ),

  'textarea_placeholder' => array(
    'js'      => 'Добавьте пользовательский JavaScript код...',
    'jQuery'  => 'Добавьте пользовательский JavaScript или jQuery код (будет выполняться после готовности DOM)...',
    'url'     => 'Добавьте URL скрипта...',
    'raw'     => 'Добавьте код...',
  ),

  'Admin_AddScript'       => 'Управление скриптами', // for TS 5.1.1-b1+ AdminLinkLabel hook, see Addon.ini

);

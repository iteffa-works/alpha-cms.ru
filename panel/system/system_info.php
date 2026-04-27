<?php  
acms_header('О системе', 'management');
?>

<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<a href='/admin/system/'><?=lg('Настройки системы')?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('О системе')?>
</div>
  
<div class='list-body6'>
<div class='list-menu list-title'>
<?=lg('Системные требования')?> (<?=lg('обязательные для корректной работы сайта')?>)
</div>
<div class='list-menu'>
<?php list($php_ver1, $php_ver2, $php_ver3) = explode('.', strtok(strtok(phpversion(),'-'),' '), 3); ?>
<?php $php_p = $php_ver1.".".$php_ver2; ?>
<?php if ($php_p == '7.3' || $php_p == '7.2') : ?>
<?php $php = "<span class='info green'>".lg('есть')."</span>"; ?>
<?php else : ?>
<?php $php = "<span class='info red'>".lg('отсутствует')."</span>"; ?>
<?php endif ?>
<b><?=lg('Версия PHP')?> 7.2/7.3</b> <?=$php?><br /><small><?=lg('работа с PHP скриптами для взаимодействия пользователя с сервером')?></small><br /><br /><?=lg('Текущая версия')?>: <span class='info gray'><?=$php_ver1?>.<?=$php_ver2?>.<?=$php_ver3?></span>
</div>  
<div class='list-menu'>
<?php $mysql_version = db::get_column('SELECT VERSION()'); ?>
<?php if (strpos($mysql_version, '5.6') !== false || strpos($mysql_version, '5.7') !== false || strpos($mysql_version, '5.5') !== false) : ?>
<?php $pdo = "<span class='info green'>".lg('есть')."</span>"; ?>
<?php else : ?>
<?php $pdo = "<span class='info red'>".lg('отсутствует')."</span>"; ?>
<?php endif ?>  
<b><?=lg('Версия MySQL')?> (5.5/5.6/5.7)</b> <?=$pdo?><br /><small><?=lg('база данных')?></small><br /><br /><?=lg('Текущая версия')?>: <span class='info gray'><?=$mysql_version?></span>
</div>  
<div class='list-menu'>
<?php if (db::connect(0)) : ?>
<?php $pdo = "<span class='info green'>".lg('есть')."</span>"; ?>
<?php else : ?>
<?php $pdo = "<span class='info red'>".lg('отсутствует')."</span>"; ?>
<?php endif ?>  
<b><?=lg('Драйвер PDO')?></b> <?=$pdo?><br /><small><?=lg('работа с базами данных MySQL')?></small>
</div> 
</div>
  
<div class='list-body6'>
<div class='list-menu list-title'>
<?=lg('Требования PHP')?>
</div>
<div class='list-menu'>
<?php if (function_exists('exec')) : ?>
<?php $exec = "<span class='info green'>".lg('включен')."</span>"; ?>
<?php else : ?>
<?php $exec = "<span class='info gray'>".lg('отключен')."</span>"; ?>
<?php endif ?>  
<b><?=lg('Вызов внешней программы')?> - exec()</b> <?=$exec?><br /><small><?=lg('удаленное соединение с программами на сервере, например соединение с FFmpeg для обработки видефайлов на сайте')?></small>
</div> 
<div class='list-menu'>
<?php if (function_exists('file_put_contents')) : ?>
<?php $fp = "<span class='info green'>".lg('включен')."</span>"; ?>
<?php else : ?>
<?php $fp = "<span class='info gray'>".lg('отключен')."</span>"; ?>
<?php endif ?>  
<b><?=lg('Запись данных в файл')?> - file_put_contents()</b> <?=$fp?><br /><small><?=lg('используется для записи данных в файл, например в системных файлах .ini')?></small>
</div>
<div class='list-menu'>
<?php if (function_exists('file_get_contents')) : ?>
<?php $fg = "<span class='info green'>".lg('включен')."</span>"; ?>
<?php else : ?>
<?php $fg = "<span class='info gray'>".lg('отключен')."</span>"; ?>
<?php endif ?>  
<b><?=lg('Считывание содержимого файла')?> - file_get_contents()</b> <?=$fg?><br /><small><?=lg('используется для получения содержимого файла в виде строки')?></small>
</div>
<div class='list-menu'>
<?php if (class_exists('zip')) : ?>
<?php $zip = "<span class='info green'>".lg('включен')."</span>"; ?>
<?php else : ?>
<?php $zip = "<span class='info gray'>".lg('отключен')."</span>"; ?>
<?php endif ?>  
<b><?=lg('Класс для работы с zip архивами')?> - ZipArchive</b> <?=$zip?><br /><small><?=lg('используется для установки новых компонентов на сайт через Альфа установщик')?></small>
</div> 
</div>
  
<div class='list-body6'>
<div class='list-menu list-title'>
<?=lg('Общая информация')?>
</div>
<div class='list-menu'>
<?=lg('Версия')?>: <span class='info gray'><?=tabs(config('ACMS_VERSION'))?></span><br />
<?=lg('Тип')?>: <span class='info gray'><?=tabs(config('ACMS_TYPE'))?></span><br />
<?=lg('Посл. обновление')?>: <span class='info gray'><?=tabs(config('ACMS_UPDATE'))?></span><br />
<?=lg('Полное наименование')?>: <span class='info gray'><?=tabs(config('ACMS_NAME'))?></span><br />
<?=lg('Официальный сайт поддержки')?>: <a class='info blue' href='https://alpha-cms.ru' ajax='no'>alpha-cms.ru</a><br />
<?=lg('Руководитель проекта')?>: <a class='info blue' href='https://alpha-cms.ru/id1' ajax='no'>adm (ID: 1)</a><br /><br />
<?php if (function_exists('disk_free_space') && function_exists('disk_total_space')) : ?>
<?php $free_space = disk_free_space(ROOT); ?>
<?php $total_space = disk_total_space(ROOT); ?>
<?php $disk = "<span class='info gray'>".lg('свободно').": ".size_file($free_space)." из ".size_file($total_space)."</span>"; ?>
<?php else : ?>
<?php $disk = "<span class='info red'>".lg('нет данных')."</span>"; ?>
<?php endif ?>
<b><?=lg('Память на сервере')?></b> <br /><?=$disk?>
</div>  
</div>
<br />

<?
back('/admin/system/');
acms_footer();
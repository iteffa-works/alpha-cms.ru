<?php
html::title('Рабочий стол');
acms_header();

?><div style='white-space: nowrap;'><div class='desktop-info-optimize'><?

require_once (ROOT.'/panel/desktop/plugins/info.php');

if (VERSION == 'web'){
  
  ?>
  <?php $users = db::get_column("SELECT COUNT(`ID`) FROM `USERS`"); ?>
  <div class='desktop-info2'><span><?=icons('users', 25)?></span><div><?=num_format($users, 2)?></div><small><?=num_decline($users, ['пользователь',   'пользователя', 'пользователей'], 0)?></small></div>
  <?php $comments = db::get_column("SELECT COUNT(`ID`) FROM `COMMENTS`"); ?>
  <div class='desktop-info2'><span><?=icons('comments', 25)?></span><div><?=num_format($comments, 2)?></div><small><?=num_decline($comments, ['сообщение', 'сообщения', 'сообщений'], 0)?></small></div>
  <?php $files = db::get_column("SELECT COUNT(`ID`) FROM `MUSIC`") + db::get_column("SELECT COUNT(`ID`) FROM `VIDEOS`") + db::get_column("SELECT COUNT(`ID`) FROM `PHOTOS`") + db::get_column("SELECT COUNT(`ID`) FROM `FILES`"); ?>
  <div class='desktop-info2'><span><?=icons('files-o', 25)?></span><div><?=num_format($files, 2)?></div><small><?=num_decline($files, ['файл', 'файла', 'файлов'], 0)?></small></div>
  </div>
  <?
    
}

require (ROOT.'/panel/desktop/plugins/speed.php');

?></div><div class='desktop-middle-vidjet'><?
direct::components(ROOT.'/panel/desktop/components/');
?></div><br /><br /><?

acms_footer();
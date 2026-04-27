<?php
  
if (db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_BAN` WHERE `COMMUNITY_ID` = ? AND `BAN` = ? LIMIT 1", [$comm['ID'], 1]) > 0 || db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_BAN` WHERE `COMMUNITY_ID` = ? AND `BAN_TIME` > ? AND `BAN` = ? LIMIT 1", [$comm['ID'], TM, 0]) > 0) {
  
  if (access('communities', null) == true) {
    
    ?>
    <div class='list'>
    <?=lg('Сообщество %s было заблокировано. Информация видна только членам администрации', '<b>'.tabs($comm['NAME']).'</b>')?>
    </div>
    <?
    
  }else{
    
    html::empty('Сообщество заблокировано. Возможно, это временно', 'ban');
    
    back('/', 'На главную');
    acms_footer();
    
  }
  
}
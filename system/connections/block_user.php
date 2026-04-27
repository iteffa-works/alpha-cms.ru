<?php
  
if (user('ID') > 0) {
  
  if (db::get_column("SELECT COUNT(`ID`) FROM `BAN_USER` WHERE `USER_ID` = ? AND `BAN_TIME` > ? LIMIT 1", [user('ID'), TM]) > 0 || db::get_column("SELECT COUNT(`ID`) FROM `BAN_USER` WHERE `USER_ID` = ? AND `BAN` = ? LIMIT 1", [user('ID'), 1]) > 0){
    
    $blocked = db::get_string("SELECT * FROM `BAN_USER` WHERE `USER_ID` = ? AND (`BAN` = ? OR `BAN_TIME` > ?) LIMIT 1", [user('ID'), 1, TM]);
    
    if ($blocked['REASON'] == 1){
      
      $reason = 'СПАМ, реклама';
    
    }elseif ($blocked['REASON'] == 2){
      
      $reason = 'Мошенничество';
    
    }elseif ($blocked['REASON'] == 3){
      
      $reason = 'Нецензурная брань, оскорбления';
    
    }elseif ($blocked['REASON'] == 4){
      
      $reason = 'Разжигание ненависти';  
    
    }elseif ($blocked['REASON'] == 5){
      
      $reason = 'Пропаганда нацизма';
    
    }elseif ($blocked['REASON'] == 6){
      
      $reason = 'Пропаганда наркотиков';
    
    }elseif ($blocked['REASON'] == 7){
      
      $reason = 'Систематические нарушения';
    
    }elseif ($blocked['REASON'] == 0){
      
      $reason = 'Иная';      
    
    }
    
    if ($blocked['BAN'] == 1){
      
      $block_time = lg('Навсегда');
    
    }else{
      
      $block_time = lg('до')." ".ftime($blocked['BAN_TIME']);
    
    }
    
    ?>
    <div class='list'>
    <center><?=icons('ban', 70)?>
    <br />
    <font size='+1'><?=lg('Вы заблокированы')?></font>
    </center>
    <br /><br />
    <b><?=lg('Причина блокировки')?>:</b> <?=lg($reason)?><br /><br />
    <b><?=lg('Комментарий')?>:</b> <?=text($blocked['MESSAGE'])?><br /><br />
    <b><?=lg('Время блокировки')?>:</b> <?=ftime($blocked['TIME'])?><br /><br />
    <b><?=lg('Срок')?>:</b> <?=$block_time?>
    </div>
    <?
      
    acms_footer();
    
  }

}
<?php
  
if (access('users_blocked', null) == false){ 
  
  if ($settings['PRIVATE_ACCOUNT'] == 1 && user('ID') == 0){ 
    
    ?>
    <div class="list">
    <center>
    <?=user::avatar($account['ID'], 80)?><br />
    <?=user::login($account['ID'], 0, 1)?><br /><br />
    <span class='time'><?=icons('user-secret', 15, 'fa-fw')?> <?=lg('Страница открыта только для авторизованных')?></span>
    </center>
    </div>
    <?

    acms_footer();
  
  }
  
  if (user('ID') != $account['ID'] && $settings['PRIVATE_ACCOUNT'] == 2 && db::get_column("SELECT COUNT(`ID`) FROM `FRIENDS` WHERE `USER_ID` = ? AND `MY_ID` = ? AND `ACT` = '0' LIMIT 1", [user('ID'), $account['ID']]) == 0){ 
    
    ?>
    <div class="list">
    <center>
    <?=user::avatar($account['ID'], 80)?><br />
    <?=user::login($account['ID'], 0, 1)?><br /><br />
    <span class='time'><?=icons('user-plus', 15, 'fa-fw')?> <?=lg('Страница открыта только для друзей')?></span>
    </center>
    </div>
      
    <div id="friends">
    <?
      
    $url_frscb = '/id'.$account['ID'];
    require (ROOT.'/modules/users/plugins/friends_and_subscribers.php');
    
    $mail_set = db::get_string("SELECT `PRIVATE` FROM `MAIL_SETTINGS` WHERE `USER_ID` = ? LIMIT 1", [$account['ID']]);  
    if ($mail_set['PRIVATE'] == 2 || $mail_set['PRIVATE'] == 1 && db::get_column("SELECT COUNT(`ID`) FROM `FRIENDS` WHERE `USER_ID` = ? AND `MY_ID` = ? AND `ACT` = '0' LIMIT 1", [user('ID'), $account['ID']]) == 0 || user('ID') > 0){
      
      $mess = '<a href="/account/mail/messages/?id='.$account['ID'].'&'.TOKEN_URL.'" class="btn">'.icons('envelope', 14, 'fa-fw').' '.lg('Написать').'</a> ';
    
    }else{
      
      $mess = '<a class="btn-o">'.icons('envelope', 14, 'fa-fw').' '.lg('Написать').'</a> ';
    
    }
    
    $friends = null;
    
    if (db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE `MY_ID` = ? AND `USER_ID` = ? AND `ACT` = '1'", [$account['ID'], $account['ID']]) > 0){
      
      $friends = '<span class="btn-o" onclick="request(\''.$url_frscb.'?friends_ok='.$account['ID'].'&'.TOKEN_URL.'\', \'#friends\')">'.icons('plus', 18, 'fa-fw').' '.lg('Принять заявку').'</span>';
    
    }else{
      
      if (user('ID') != $account['ID'] && db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE `MY_ID` = ? AND `USER_ID` = ? AND `ACT` = '1'", [user('ID'), $account['ID']]) == 1){
        
        $friends = '<span class="btn-o" onclick="request(\''.$url_frscb.'?friends_cancel='.$account['ID'].'&'.TOKEN_URL.'\', \'#friends\')">'.icons('times', 18, 'fa-fw').' '.lg('Отменить').'</span>';
      
      }else{
        
        if ($settings['FRIENDS_PRIVATE_ADD'] == 1 && user('ID') != $account['ID'] && db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE `MY_ID` = ? AND `USER_ID` = ? AND `ACT` = '0'", [user('ID'), $account['ID']]) == 0){
          
          $friends = '<span class="btn" onclick="request(\''.$url_frscb.'?friends_add='.$account['ID'].'&'.TOKEN_URL.'\', \'#friends\')">'.icons('user', 18, 'fa-fw').' '.lg('Дружить').'</span>';
        
        }
      
      }
      
      if (db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE (`USER_ID` = ? AND `MY_ID` = ? OR `USER_ID` = ? AND `MY_ID` = ?) AND `ACT` = '0'", [user('ID'), $account['ID'], $account['ID'], user('ID')])){
        
        $friends = '<span class="btn-o" onclick="request(\''.$url_frscb.'?friends_delete='.$account['ID'].'&'.TOKEN_URL.'\', \'#friends\')">'.icons('check', 18, 'fa-fw').' '.lg('Дружите').'</span>';
      
      }
    
    }
  
    ?>   
    <div class='list'>
    <center>
    <?=$mess?>
    <?=$friends?>
    </center>
    </div>
      
    </div>
    <?

    acms_footer();
  
  }
  
  if ($settings['PRIVATE_ACCOUNT'] == 3 && user('ID') != $account['ID']){ 
    
    ?>
    <div class="list">
    <center>
    <?=user::avatar($account['ID'], 80)?><br />
    <?=user::login($account['ID'], 0, 1)?><br /><br />
    <span class='time'><?=icons('user', 15, 'fa-fw')?> <?=lg('Страница открыта только для владельца')?></span>
    </center>
    </div>
    <?

    acms_footer();
  
  }
  
}
<?php
 
$block_p = 0;  
$block_user = db::get_string("SELECT * FROM `BAN_USER` WHERE `USER_ID` = ? ORDER BY `TIME` DESC LIMIT 1", [$account['ID']]);
  
if (isset($block_user['ID']) && $block_user['BAN_TIME'] > TM && $block_user['BAN'] == 0 || isset($block_user['ID']) && $block_user['BAN'] == 1) {
  
  if ($block_user['REASON'] == 1){
    
    $reason = 'СПАМ, реклама';
  
  }elseif ($block_user['REASON'] == 2){
    
    $reason = 'Мошенничество';
  
  }elseif ($block_user['REASON'] == 3){
    
    $reason = 'Нецензурная брань, оскорбления';
  
  }elseif ($block_user['REASON'] == 4){
    
    $reason = 'Разжигание ненависти';
  
  }elseif ($block_user['REASON'] == 5){
    
    $reason = 'Пропаганда нацизма';
  
  }elseif ($block_user['REASON'] == 6){
    
    $reason = 'Пропаганда наркотиков';
  
  }elseif ($block_user['REASON'] == 7){
    
    $reason = 'Систематические нарушения';
  
  }elseif ($block_user['REASON'] == 0){
    
    $reason = 'Иная';
  
  }
  
  ?>
  <div class='list'>
  <center>
  <?=icons('ban', 80, 'fa-fw')?><br /><br />
  <font size='+1'>
  <?php if ($block_user['BAN'] == 1) : ?>
  <?=lg('Пользователь заблокирован навсегда')?>
  <?php else : ?>
  <?=lg('Пользователь %s заблокирован до %s', '<b>'.user::login_mini($account['ID']).'</b>', '<b>'.mb_strtolower(ftime($block_user['BAN_TIME']), 'utf-8').'</b>')?>
  <?php endif ?>
  </font>
  </center><br />
  <b><?=lg('Причина блокировки')?>:</b> <?=mb_strtolower(lg($reason), 'utf-8')?>
  <?php if (str($block_user['MESSAGE']) > 0) : ?>
  <br />
  <b><?=lg('Комментарий администрации')?>:</b> <?=text($block_user['MESSAGE'])?>
  <?php endif ?>
  </div>
  <?

  if (access('users_blocked', null) == true) {
    
    ?>
    <div class='list'>
    <?=lg('Информация о странице %s видна только уполномоченным администраторам', '<b>'.$account['LOGIN'].'</b>')?>
    </div>
    <?
    
  }else{
    
    $block_p = 1;
    
  }

}

if ($block_p == 1 && user('ID') > 0) {
  
  ?><div id="friends"><?
      
  $url_frscb = '/id'.$account['ID'];
  require (ROOT.'/modules/users/plugins/friends_and_subscribers.php');
    
  $friends = null;
    
  if (db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE `MY_ID` = ? AND `USER_ID` = ? AND `ACT` = '1'", [$account['ID'], $account['ID']]) > 0){
      
    $friends = '<span class="btn-o" onclick="request(\''.$url_frscb.'?friends_ok='.$account['ID'].'&'.TOKEN_URL.'\', \'#friends\')">'.icons('plus', 15, 'fa-fw').' '.lg('Принять заявку').'</span>';
    
  }else{
      
    if (user('ID') != $account['ID'] && db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE `MY_ID` = ? AND `USER_ID` = ? AND `ACT` = '1'", [user('ID'), $account['ID']]) == 1){
        
      $friends = '<span class="btn-o" onclick="request(\''.$url_frscb.'?friends_cancel='.$account['ID'].'&'.TOKEN_URL.'\', \'#friends\')">'.icons('times', 15, 'fa-fw').' '.lg('Отменить').'</span>';
      
    }
      
    if (db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE (`USER_ID` = ? AND `MY_ID` = ? OR `USER_ID` = ? AND `MY_ID` = ?) AND `ACT` = '0'", [user('ID'), $account['ID'], $account['ID'], user('ID')])){
        
      $friends = '<span class="btn-o" onclick="request(\''.$url_frscb.'?friends_delete='.$account['ID'].'&'.TOKEN_URL.'\', \'#friends\')">'.icons('check', 15, 'fa-fw').' '.lg('Дружите').'</span>';
      
    }
    
  }
  
  ?>   
  <div class='list'>
  <center>
  <a href="/m/block/user_list/?id=<?=$account['ID']?>" class="btn"><?=icons('ban', 15, 'fa-fw')?> <?=lg('История блокировок')?></a>
  <?=$friends?>
  </center>
  </div>
      
  </div>
  <?
    
  back('/', 'На главную');
  acms_footer();
  
}
<div id='friends'>

<?php
  
if (user('ID') != $account['ID']) {  
  
  $url_frscb = '/id'.$account['ID'];
  require_once (ROOT.'/modules/users/plugins/friends_and_subscribers.php');
  
  $mail_set = db::get_string("SELECT `PRIVATE` FROM `MAIL_SETTINGS` WHERE `USER_ID` = ? LIMIT 1", [$account['ID']]);  
  if ($mail_set['PRIVATE'] == 2 || $mail_set['PRIVATE'] == 1 && db::get_column("SELECT COUNT(`ID`) FROM `FRIENDS` WHERE `USER_ID` = ? AND `MY_ID` = ? AND `ACT` = '0' LIMIT 1", [user('ID'), $account['ID']]) == 0 || user('ID') > 0){
    
    $mess = '<a href="/account/mail/messages/?id='.$account['ID'].'&'.TOKEN_URL.'" class="menu-profile-button">'.icons('envelope', 14, 'fa-fw').' '.lg('Написать').'</a> ';
  
  }else{
    
    $mess = '<a class="menu-profile-button menu-profile-op">'.icons('envelope', 14, 'fa-fw').' '.lg('Написать').'</a> ';
  
  }
  
  if (user('ID') != $account['ID'] && db::get_column("SELECT COUNT(*) FROM `SUBSCRIBERS` WHERE `MY_ID` = ? AND `USER_ID` = ? LIMIT 1", [user('ID'), $account['ID']]) > 0){
    
    $suscr = '<a ajax="no" class="menu-profile-button mpb-gray" onclick="request(\''.$url_frscb.'?subscribe_delete='.$account['ID'].'&'.TOKEN_URL.'\', \'#friends\')">'.icons('check', 14, 'fa-fw').' '.lg('Подписаны').'</a> ';
  
  }elseif (user('ID') == 0){
    
    $suscr = '<a ajax="no" class="menu-profile-button mpb-green menu-profile-op">'.icons('feed', 14, 'fa-fw').' '.lg('Подписаться').'</a> ';
  
  }else{
    
    $suscr = '<a ajax="no" class="menu-profile-button mpb-green" onclick="request(\''.$url_frscb.'?subscribe_ok='.$account['ID'].'&'.TOKEN_URL.'\', \'#friends\')">'.icons('feed', 14, 'fa-fw').' '.lg('Подписаться').'</a> ';
  
  }
  
  if (user('ID') == 0){
    
    $menu = "<a class='menu-profile-button mpb-green menu-profile-op' style='width: 15%; min-width: 30px'>".icons('angle-down', 20, 'fa-fw')."</a>";
  
  }else{
    
    $menu = '<a onclick="open_or_close(\'profile-menu-modal\')" class="menu-profile-button mpb-green" style="width: 15%; min-width: 30px" id="profile-menu-modal-c">'.icons('angle-down', 20, 'fa-fw').'</a>';
  
  }
  
  $friends = null;
  
  if (db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE `MY_ID` = ? AND `USER_ID` = ? AND `ACT` = '1'", [$account['ID'], $account['ID']]) > 0){
    
    $friends = '<span onclick="request(\''.$url_frscb.'?friends_ok='.$account['ID'].'&'.TOKEN_URL.'\', \'#friends\')">'.icons('plus', 18, 'fa-fw').' '.lg('Принять заявку').'</span>';
  
  }else{
    
    if (user('ID') != $account['ID'] && db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE `MY_ID` = ? AND `USER_ID` = ? AND `ACT` = '1'", [user('ID'), $account['ID']]) == 1){
      
      $friends = '<span onclick="request(\''.$url_frscb.'?friends_cancel='.$account['ID'].'&'.TOKEN_URL.'\', \'#friends\')">'.icons('times', 18, 'fa-fw').' '.lg('Отменить').'</span>';
    
    }else{
      
      if ($settings['FRIENDS_PRIVATE_ADD'] == 1 && user('ID') != $account['ID'] && db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE `MY_ID` = ? AND `USER_ID` = ? AND `ACT` = '0'", [user('ID'), $account['ID']]) == 0){
        
        $friends = '<span onclick="request(\''.$url_frscb.'?friends_add='.$account['ID'].'&'.TOKEN_URL.'\', \'#friends\')">'.icons('user', 18, 'fa-fw').' '.lg('Дружить').'</span>';
      
      }
    
    }
    
    if (db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE (`USER_ID` = ? AND `MY_ID` = ? OR `USER_ID` = ? AND `MY_ID` = ?) AND `ACT` = '0'", [user('ID'), $account['ID'], $account['ID'], user('ID')])){
      
      $friends = '<span onclick="request(\''.$url_frscb.'?friends_delete='.$account['ID'].'&'.TOKEN_URL.'\', \'#friends\')">'.icons('check', 18, 'fa-fw').' '.lg('Дружите').'</span>';
    
    }
  
  }
  
  ?>
  <div class='menu-profile-optimize'>
  <?=$mess?>
  <?=$suscr?>
  <?=$menu?>  
  <div id='profile-menu-modal' style='display: none;'> 
  <?=$friends?>
  <a href="/account/gifts/give/?id=<?=$account['ID']?>">
  <?=icons('gift', 18, 'fa-fw')?> <?=lg('Сделать подарок')?>
  </a>
  <?=hooks::challenge('profile_menu_modal', 'profile_menu_modal')?>
  <?=hooks::run('profile_menu_modal')?>
  <span onclick="open_or_close('profile-menu-modal')"><?=icons('times', 18, 'fa-fw')?> <?=lg('Закрыть')?></span>  
  </div>
  </div>
  <?
    
}elseif (user('ID') == $account['ID']) {
  
  ?>
  <a href='/account/form/?id=<?=$account['ID']?>' class='profile-edit'><?=lg('Редактировать')?></a>
  <?
  
}

?>
</div>
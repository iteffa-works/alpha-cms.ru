<?php
  
$settings = db::get_string("SELECT * FROM `USERS_SETTINGS` WHERE `USER_ID` = ? LIMIT 1", [$list['ID']]);
$friends = null;

if (get('get') == 'rating') {
  
  $rating = icons('star', 13)." <b>".lg('Рейтинг').": ".$list['RATING'].'</b><br /><br />';
  $rating_num = "<div class='rating_num'>".$rnum."</div>";
    
}else{
  
  $rating = null;
  $rating_num = null;

}

if ($list['ID'] != user('ID')){
  
  $mail = '<a href="/account/mail/messages/?id='.$list['ID'].'&'.TOKEN_URL.'"><div class="list-menu hover">'.icons('envelope', 18, 'fa-fw').' '.lg('Написать сообщение').'</div></a>'; 
  
}else{
  
  $mail = null;
  
}
  
if ($list['ID'] == user('ID')){
  
  $menu = null;
  
}else{
  
  if (user('ID') != $list['ID'] && db::get_column("SELECT COUNT(*) FROM `SUBSCRIBERS` WHERE `MY_ID` = ? AND `USER_ID` = ? LIMIT 1", [user('ID'), $list['ID']]) > 0){
    
    $suscr = '<div class="list-menu hover" onclick="request(\''.url_request_get(URL_FRSCB).'page='.$page.'&subscribe_delete='.$list['ID'].'&'.TOKEN_URL.'\', \'#friends'.$list['ID'].'\')">'.icons('check', 18, 'fa-fw').' '.lg('Подписаны').'</div>';
  
  }else{
    
    $suscr = '<div class="list-menu hover" onclick="request(\''.url_request_get(URL_FRSCB).'page='.$page.'&subscribe_ok='.$list['ID'].'&'.TOKEN_URL.'\', \'#friends'.$list['ID'].'\')">'.icons('feed', 18, 'fa-fw').' '.lg('Подписаться').'</div>';
  
  }
  
  if (db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE `MY_ID` = ? AND `USER_ID` = ? AND `ACT` = '1'", [$list['ID'], user('ID')]) > 0){
    
    $friends = '<div class="list-menu hover" onclick="request(\''.url_request_get(URL_FRSCB).'page='.$page.'&friends_ok='.$list['ID'].'&'.TOKEN_URL.'\', \'#friends'.$list['ID'].'\')">'.icons('plus', 18, 'fa-fw').' '.lg('Принять заявку').'</div>';
    
  }else{
    
    if (user('ID') != $list['ID'] && db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE `MY_ID` = ? AND `USER_ID` = ? AND `ACT` = '1'", [user('ID'), $list['ID']]) == 1){
      
      $friends = '<div class="list-menu hover" onclick="request(\''.url_request_get(URL_FRSCB).'page='.$page.'&friends_cancel='.$list['ID'].'&'.TOKEN_URL.'\', \'#friends'.$list['ID'].'\')">'.icons('times', 18, 'fa-fw').' '.lg('Отменить').'</div>';
    
    }else{
      
      if ($settings['FRIENDS_PRIVATE_ADD'] == 1 && user('ID') != $list['ID'] && db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE `MY_ID` = ? AND `USER_ID` = ? AND `ACT` = '0'", [user('ID'), $list['ID']]) == 0){
        
        $friends = '<div class="list-menu hover" onclick="request(\''.url_request_get(URL_FRSCB).'page='.$page.'&friends_add='.$list['ID'].'&'.TOKEN_URL.'\', \'#friends'.$list['ID'].'\')">'.icons('user', 18, 'fa-fw').' '.lg('Дружить').'</div>';
      
      }
    
    }
    
    if (db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE (`USER_ID` = ? AND `MY_ID` = ? OR `USER_ID` = ? AND `MY_ID` = ?) AND `ACT` = '0'", [user('ID'), $list['ID'], $list['ID'], user('ID')])){
      
      $friends = '<div class="list-menu hover" onclick="request(\''.url_request_get(URL_FRSCB).'page='.$page.'&friends_delete='.$list['ID'].'&'.TOKEN_URL.'\', \'#friends'.$list['ID'].'\')">'.icons('check', 18, 'fa-fw').' '.lg('Дружите').'</div>';
    
    }
    
  }
  
   $menu = $friends.$suscr;
  
}

if ($settings['G_R'] > 0 && $settings['M_R'] > 0 && $settings['D_R'] > 0) {
  
  $age = _age($list['ID'], age($list['ID'], $settings['G_R'], $settings['M_R'], $settings['D_R']), array(lg('год'), lg('года'), lg('лет'))).'<br />';
  
}else{
  
  $age = null;
  
}

if (str($settings['COUNTRY']) > 0 || str($settings['CITY']) > 0){
  
  $crc = tabs($settings['CITY']).", ".tabs($settings['COUNTRY']).'<br />';

}else{
  
  $crc = null;

}

$date_visit = db::get_column("SELECT `DATE_VISIT` FROM `USERS` WHERE `ID` = ? LIMIT 1", [$list['ID']]);

if ($date_visit < (TM-config('ONLINE_TIME_USERS'))){
  
  $on = lg('Был(-а) в сети').' '.stime($date_visit).'<br />';

}else{
  
  $on = '<font color="#45C07B">'.lg('онлайн').'</font><br />';

}

if (isset($user_menu_list)){
  
  $user_menu_list2 = $user_menu_list;
  
}else{
  
  $user_menu_list2 = null;
  
}

if (user('ID') == 0) {
  
  $menu = '<div class="list-menu">'.lg('Для выполнения действий с данным пользователем %s или %s на сайте', '<a href="/login/">'.lg('авторизуйтесь').'</a>', '<a href="/registration/">'.lg('зарегистрируйтесь').'</a>').'</div>';
  $mail = null;
  
}

if ($list['ID'] != user('ID')){
  
  $cmenu = '
  <span onclick="modal_center(\'cmenu'.$list['ID'].'\', \'open\')" class="user-login-menu">'.icons('ellipsis-v', 20).'</span>
  <div class="modal_phone modal_center_close" id="cmenu'.$list['ID'].'2" onclick="modal_center(\'cmenu'.$list['ID'].'\', \'close\')"></div>
  <div id="cmenu'.$list['ID'].'" class="modal_center modal_center_open">
  <div class="modal_bottom_title2">'.lg('Действия').'<button onclick="modal_center_close()">'.icons('times', 20).'</button></div>
  <div class="modal-container">
  <div id="friends'.$list['ID'].'">
  '.$menu.$mail.'
  </div>
  </div>
  </div>
  ';
  
}else{
  
  $cmenu = null;
  
}

?>
<div class="list-menu">

<?=$cmenu?>

<div class="user-avatar">
<?=$rating_num?>
<a href="/id<?=$list['ID']?>"><?=user::avatar($list['ID'], 55, 1)?></a>
</div>

<div class="user-login">
<?=user::login($list['ID'], 0, 1)?>
<br />
<span class="user-login-age"><?=$rating.$age.$crc.$on?></span>
</div>

<?=$user_menu_list2?>
  
</div>
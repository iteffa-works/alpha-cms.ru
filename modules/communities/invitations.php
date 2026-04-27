<?php
html::title('Приглашения в сообщества');
acms_header();
access('users');

if (config('PRIVATE_COMMUNITIES') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

if (get('int_yes')){
  
  $id = intval(get('int_yes'));   
  $inv = db::get_string("SELECT `ACT` FROM `COMMUNITIES_PAR` WHERE `USER_ID` = ? AND `ID` = ? LIMIT 1", [user('ID'), $id]);  
  get_check_valid();
  
  if ($inv['ACT'] == 2){
    
    db::get_set("UPDATE `COMMUNITIES_PAR` SET `ACT` = ? WHERE `ID` = ? LIMIT 1", [1, $id]);
    
    success('Вы успешно вступили в сообщество');
  
  }elseif ($inv['ACT'] == 1){
    
    error('Вы уже состоите в сообществе');
    
  }else{
    
    error('Неизвестная ошибка');
    
  }
  
  redirect('/m/communities/invitations/');

}

if (get('int_no')){
  
  $id = intval(get('int_no'));
  get_check_valid();
  
  db::get_set("DELETE FROM `COMMUNITIES_PAR` WHERE `ID` = ? LIMIT 1", [$id]);
  
  success('Приглашение отклонено');
  redirect('/m/communities/invitations/');

}

$column = db::get_column("SELECT COUNT(`ID`) FROM `COMMUNITIES_PAR` WHERE `USER_ID` = ? AND `ACT` = ?", [user('ID'), 2]);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><?
  
}

$data = db::get_string_all("SELECT * FROM `COMMUNITIES_PAR` WHERE `USER_ID` = ? AND `ACT` = ? ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [user('ID'), 2]);
while ($list2 = $data->fetch()) {
  
  $list = db::get_string("SELECT `NAME`,`ID`,`MESSAGE`,`URL`,`AVATAR` FROM `COMMUNITIES` WHERE `ID` = ? LIMIT 1", [$list2['COMMUNITY_ID']]);
  
  $dop = "
  <a href='/m/communities/invitations/?int_yes=".$list2['ID']."&".TOKEN_URL."' class='btn'>".icons('plus', 15, 'fa-fw')." ".lg('Принять приглашение')."</a>
  <a href='/m/communities/invitations/?int_no=".$list2['ID']."&".TOKEN_URL."' class='btn-o'>".icons('times', 15, 'fa-fw')." ".lg('Отклонить приглашение')."</a>
  ";
  
  require (ROOT.'/modules/communities/plugins/list.php');
  echo $comm_list;
  
}

if ($column > 0){
  
  ?></div><?
  
}

get_page('/m/communities/invitations/?', $spage, $page, 'list');

back('/m/communities/users/?id='.user('ID'));
acms_footer();
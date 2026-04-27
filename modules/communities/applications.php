<?php
$comm = db::get_string("SELECT `ID`,`PRIVATE`,`URL`,`NAME` FROM `COMMUNITIES` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
$par = db::get_string("SELECT `ADMINISTRATION` FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? AND `ACT` = ? LIMIT 1", [$comm['ID'], user('ID'), 1]);
html::title(lg('Заявки на вступление в сообщество %s', communities::name($comm['ID'])));
acms_header();
access('users');
communities::blocked($comm['ID']);

if (!isset($comm['ID'])) {
  
  error('Неверная директива');
  redirect('/m/communities/');

}

if ($par['ADMINISTRATION'] == 0) {
  
  error('Неверная директива');
  redirect('/public/'.$comm['URL']);

}

if (config('PRIVATE_COMMUNITIES') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

?>
<div class='list'>
<center><b><?=lg('Пользователи, ждущие одобрения заявки')?></b></center>
</div>
<?
  
if (get('yes')){
  
  $yes = db::get_string("SELECT `ID`,`USER_ID` FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `ACT` = ? AND `ID` = ? LIMIT 1", [$comm['ID'], 0, intval(get('yes'))]);
  
  get_check_valid();
  
  if (isset($yes['ID'])){
    
    db::get_set("UPDATE `COMMUNITIES_PAR` SET `ACT` = '1' WHERE `ID` = ? LIMIT 1", [$yes['ID']]);
    
    $message = lg('Поздравляем! Вы приняты в сообщество %s', '[url=/public/'.$comm['URL'].']'.$comm['NAME'].'[/url]');
    messages::get(intval(config('SYSTEM')), $yes['USER_ID'], $message);
    
    success('Пользователь успешно принят в сообщество');
    redirect('/m/communities/applications/?id='.$comm['ID']);
    
  }
  
}

if (get('no')){
  
  $no = db::get_string("SELECT `ID`,`USER_ID` FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `ACT` = ? AND `ID` = ? LIMIT 1", [$comm['ID'], 0, intval(get('no'))]);
  
  get_check_valid();
  
  if (isset($no['ID'])){
    
    db::get_set("DELETE FROM `COMMUNITIES_PAR` WHERE `ID` = ? LIMIT 1", [$no['ID']]);
    
    $message = lg('Сожалеем, но ваша заявка на вступление в сообщество %s была отклонена', '[url=/public/'.$comm['URL'].']'.$comm['NAME'].'[/url]');
    messages::get(intval(config('SYSTEM')), $no['USER_ID'], $message);
    
    success('Заявка успешно отклонена');
    redirect('/m/communities/applications/?id='.$comm['ID']);
    
  }
  
}

$column = db::get_column("SELECT COUNT(`ID`) FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `ACT` = ?", [$comm['ID'], 0]);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><?
  
}

$data = db::get_string_all("SELECT * FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `ACT` = ? ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$comm['ID'], 0]);
while ($list = $data->fetch()) {
  
  $dop2 = "
  <br />
  <a class='btn' href='/m/communities/applications/?id=".$comm['ID']."&yes=".$list['ID']."&".TOKEN_URL."'>".icons('plus', 15, 'fa-fw')." ".lg('Одобрить')."</a>
  <a class='btn' href='/m/communities/applications/?id=".$comm['ID']."&no=".$list['ID']."&".TOKEN_URL."'>".icons('plus', 15, 'fa-fw')." ".lg('Отклонить')."</a>
  ";
  
  require (ROOT.'/modules/users/plugins/list-mini.php');
  echo $list_mini;
  
}

if ($column > 0){
  
  ?></div><?
  
}

get_page('/m/communities/applications/?id='.$comm['ID'].'&', $spage, $page, 'list');

back('/public/'.$comm['URL']);
acms_footer();
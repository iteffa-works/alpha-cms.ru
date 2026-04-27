<?php
$comm = db::get_string("SELECT `ID`,`URL` FROM `COMMUNITIES` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
html::title(lg('Журнал сообщества %s', communities::name($comm['ID'])));
acms_header();
access('users');
communities::blocked($comm['ID']);

if (!isset($comm['ID'])) {
  
  error('Неверная директива');
  redirect('/m/communities/');

}

if (config('PRIVATE_COMMUNITIES') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

$column = db::get_column("SELECT COUNT(`ID`) FROM `COMMUNITIES_JURNAL` WHERE `COMMUNITY_ID` = ?", [$comm['ID']]);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><?
  
}

$data = db::get_string_all("SELECT * FROM `COMMUNITIES_JURNAL` WHERE `COMMUNITY_ID` = ? ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$comm['ID']]);
while ($list = $data->fetch()) {
  
  ?>
  <div class='list-menu'>
  <?=text($list['MESSAGE'])?>
  <br />
  <span class='count'><?=ftime($list['TIME'])?></span>
  </div>
  <?
  
}

if ($column > 0){
  
  ?></div><?
  
}

get_page('/m/communities/journal/?id='.$comm['ID'].'&', $spage, $page, 'list');

back('/public/'.$comm['URL']);
acms_footer();
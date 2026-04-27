<?php
$comm = db::get_string("SELECT `ID`,`URL` FROM `COMMUNITIES` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
$par = db::get_string("SELECT `ID` FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? AND `ACT` = ? LIMIT 1", [$comm['ID'], user('ID'), 1]);
html::title(lg('Записи в блоге сообщества %s', communities::name($comm['ID'])));
acms_header(); 
communities::blocked($comm['ID']);

if (config('PRIVATE_COMMUNITIES') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

if (!isset($comm['ID'])) {
  
  error('Неверная директива');
  redirect('/m/communities/');

}
  
if (isset($par['ID'])) {
  
  ?>
  <div class='list'>
  <a href='/m/communities/add_blog/?id=<?=$comm['ID']?>' class='btn'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Добавить запись')?></a>
  </div>
  <?
  
}
  
$column = db::get_column("SELECT COUNT(*) FROM `BLOGS` WHERE `COMMUNITY` = ?", [$comm['ID']]);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty('Пока пусто');
  
}

define('URL_BLOGS', '/m/communities/blogs/?id='.$comm['ID'].'&page='.tabs(get('page')));
$data = db::get_string_all("SELECT * FROM `BLOGS` WHERE `COMMUNITY` = ? ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$comm['ID']]);
while ($list = $data->fetch()) {
  
  require (ROOT.'/modules/blogs/plugins/list.php');
  echo $blogs_list;
  
}

get_page('/m/communities/blogs/?id='.$comm['ID'].'&', $spage, $page, 'list');

back('/public/'.$comm['URL']);
acms_footer();
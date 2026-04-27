<?php
$account = db::get_string("SELECT `ID`,`LOGIN` FROM `USERS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);  
html::title(lg('Блоги %s', $account['LOGIN']));
acms_header(); 

if (config('PRIVATE_BLOGS') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

if (!isset($account['ID'])) {
  
  error('Неверная директива');
  redirect('/m/blogs/');

}
  
?> 
<div class='menu-nav-content'>
  
<a class='menu-nav' href='/m/blogs/?'>
<?=lg('Все')?>
</a>
    
<a class='menu-nav' href='/m/blogs/categories/'>
<?=lg('Категории')?>
</a>
    
<a class='menu-nav' href='/m/blogs/?get=rating'>
<?=lg('ТОП')?>
</a>
    
<a class='menu-nav' href='/m/blogs/?get=new'>
<?=lg('Новые')?>
</a>
  
<a class='menu-nav h' href='/m/blogs/users/?id=<?=$account['ID']?>'>
<?=$account['LOGIN']?>
</a>
  
</div> 
<?
  
if ($account['ID'] == user('ID')) {
  
  ?>
  <div class='list'>
  <a href='/m/blogs/add/' class='btn'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Добавить запись')?></a>
  </div>
  <?
  
}
  
$column = db::get_column("SELECT COUNT(*) FROM `BLOGS` WHERE `USER_ID` = ? AND `COMMUNITY` = ?", [$account['ID'], 0]);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty('Пока пусто');
  
}

define('URL_BLOGS', '/m/blogs/users/?id='.$account['ID'].'&page='.tabs(get('page')));
$data = db::get_string_all("SELECT * FROM `BLOGS` WHERE `USER_ID` = ? AND `COMMUNITY` = ? ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$account['ID'], 0]);
while ($list = $data->fetch()) {
  
  require (ROOT.'/modules/blogs/plugins/list.php');
  echo $blogs_list;
  
}

get_page('/m/blogs/users/?id='.$account['ID'].'&', $spage, $page, 'list');

back('/id'.$account['ID'], 'К странице');
acms_footer();
<?php
html::title('Новости');
acms_header(); 

if (config('PRIVATE_NEWS') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

if (access('news', null) == true){
  
  ?>
  <div class='list'>
  <a href='/m/news/add/' class='btn'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Добавить новость')?></a>
  </div>
  <?
  
}

$column = db::get_column("SELECT COUNT(*) FROM `NEWS`");
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><?
  
}

define('URL_NEWS', '/m/news/?page='.tabs(get('page')));
$data = db::get_string_all("SELECT * FROM `NEWS` ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS);
while ($list = $data->fetch()) {
  
  require (ROOT.'/modules/news/plugins/list.php');
  echo $news_list;
  
}

if ($column > 0){ 
  
  ?></div><?
  
}

get_page('/m/news/?', $spage, $page, 'list'); 

back('/', 'На главную');
acms_footer();
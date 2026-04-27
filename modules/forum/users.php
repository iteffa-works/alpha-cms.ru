<?php
$account = db::get_string("SELECT `ID`,`LOGIN` FROM `USERS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);  
html::title(lg('Темы %s', $account['LOGIN']));
acms_header(); 

if (config('PRIVATE_FORUM') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

if (!isset($account['ID'])){
  
  error('Неверная директива');
  redirect('/');
  
}

?> 
<div class='menu-nav-content'>
  
<a class='menu-nav' href='/m/forum/?'>
<?=lg('Все')?>
</a>
    
<a class='menu-nav' href='/m/forum/sc/'>
<?=lg('Разделы')?>
</a>
  
<a class='menu-nav' href='/m/forum/?get=act'>
<?=lg('Актуальные')?>
</a>  
    
<a class='menu-nav' href='/m/forum/?get=rating'>
<?=lg('ТОП')?>
</a>
    
<a class='menu-nav' href='/m/forum/?get=new'>
<?=lg('Новые')?>
</a>
  
<a class='menu-nav h' href='/m/forum/users/?id=<?=$account['ID']?>'>
<?=$account['LOGIN']?>
</a>  
  
</div>
<?
  
$column = db::get_column("SELECT COUNT(*) FROM `FORUM_THEM` WHERE `USER_ID` = ?", [$account['ID']]);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><?
  
}

$data = db::get_string_all("SELECT * FROM `FORUM_THEM` WHERE `USER_ID` = ? ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$account['ID']]);
while ($list = $data->fetch()) {
  
  require (ROOT.'/modules/forum/plugins/list.php');
  echo $forum_list;
  
}

if ($column > 0){ 
  
  ?></div><?
  
}

get_page('/m/forum/users/?id='.$account['ID'].'&', $spage, $page, 'list');

back('/id'.$account['ID'], 'К странице');
acms_footer();
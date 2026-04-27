<?php  
acms_header('Журнал', 'users');

$new = db::get_column("SELECT COUNT(`ID`) FROM `NOTIFICATIONS` WHERE `USER_ID` = ? AND `READ` = '1' LIMIT 1", [user('ID')]);
$all = db::get_column("SELECT COUNT(`ID`) FROM `NOTIFICATIONS` WHERE `USER_ID` = ? LIMIT 1", [user('ID')]);

if (get('type') != 'all' && get('type') != 'new' && $new == 0) { 
  
  redirect('/account/journal/?type=all'); 

}

?>
<div class='menu-nav-content'>  
<a class='menu-nav <?=(get('type') == 'all' ? 'h' : null)?>' href='/account/journal/?type=all'>
<?=lg('Все')?> <span class='menu-nav-count'><?=$all?></span>
</a>    
<a class='menu-nav <?=(get('type') == 'all' ? null : 'h')?>' href='/account/journal/?type=new'>
<?=lg('Новые')?> <span class='menu-nav-count'><?=$new?></span>
</a>  
</div> 
  
<?php require (ROOT.'/users/account/journal/plugins/delete.php'); ?>
  
<div class='list'>
<a href='/account/journal/?get=delete_all&type=all&<?=TOKEN_URL?>' class='btn'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Очистить журнал')?></a>
<a href='/account/journal/settings/' style='float: right; margin-top: 5px'><?=icons('gear', 25, 'fa-fw')?></a>
</div>
  
<div id='notif'>
<?
  
$param = (get('type') == 'all' ? null : "AND `READ` = '1'");
$column = db::get_column("SELECT COUNT(`ID`) FROM `NOTIFICATIONS` WHERE `USER_ID` = ? ".$param, [user('ID')]);
$spage = SPAGE($column, PAGE_SETTINGS);
$page = PAGE($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){
  
  html::empty('Нет событий');
  
}else{
  
  ?><div class='list-body'><?
  
}

$data = db::get_string_all("SELECT * FROM `NOTIFICATIONS` WHERE `USER_ID` = ? ".$param." ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [user('ID')]);
while ($list = $data->fetch()){
  
  ?><div class='list-menu'><?
    
  if (is_file(ROOT.'/users/account/journal/components/'.$list['TYPE'].'.php')){
    
    require (ROOT.'/users/account/journal/components/'.$list['TYPE'].'.php');
    
  }else{
    
    ?><font color='red'><?=icons('exclamation-triangle', 15, 'fa-fw')?> <b><?=lg('Ошибка')?> <?=$list['TYPE']?></b>: <?=lg('компонент не обнаружен')?></font><?
    
  }
  
  ?>
  <div style='position: absolute; bottom: 8px; right: 8px;'><span onclick="request('/account/journal/?delete_one=<?=$list['ID']?>&type=<?=tabs(get('type'))?>&page=<?=$page?>&<?=TOKEN_URL?>', '#notif')"><?=icons('times', 18, 'fa-fw')?></span></div> 
  </div>
  <?
  
}

if ($column > 0){
  
  ?></div><?
  
}
  
get_page('/account/journal/?type='.tabs(get('type')).'&', $spage, $page, 'list');

?></div><?

require (ROOT.'/users/account/journal/plugins/read.php');

back('/id'.user('ID'), 'К аккаунту');
acms_footer();
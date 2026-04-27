<?php
$account = db::get_string("SELECT `ID` FROM `USERS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);  
acms_header(lg('Сообщества %s', user::login_mini($account['ID']))); 
is_active_module('PRIVATE_COMMUNITIES');

?> 
<div class='menu-nav-content'>  
<a class='menu-nav <?=($root == 'all' ? 'h' : null)?>' href='/m/communities/?'>
<?=lg('Все')?>
</a>    
<a class='menu-nav' href='/m/communities/categories/'>
<?=lg('Категории')?>
</a>    
<a class='menu-nav <?=($root == 'rating' ? 'h' : null)?>' href='/m/communities/?get=rating'>
<?=lg('ТОП')?>
</a>    
<a class='menu-nav <?=($root == 'new' ? 'h' : null)?>' href='/m/communities/?get=new'>
<?=lg('Новые')?>
</a>  
<a class='menu-nav h' href='/m/communities/users/?id=<?=$account['ID']?>'>
<?=user::login_mini($account['ID'])?>
</a>
</div>
<?
  
if ($account['ID'] == user('ID')) {
  
  ?>
  <div class='list'>
  <a href='/m/communities/add/' class='btn'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Создать сообщество')?></a>
  <a href='/m/communities/invitations/' class='btn-o'><?=icons('user-plus', 15, 'fa-fw')?> <?=lg('Приглашения')?></a>  
  </div>
  <?
  
}
  
$column = db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_PAR` WHERE `USER_ID` = ? AND `ACT` = ?", [$account['ID'], 1]);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><?
  
}

$data = db::get_string_all("SELECT * FROM `COMMUNITIES_PAR` WHERE `USER_ID` = ? AND `ACT` = ? ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$account['ID'], 1]);
while ($list2 = $data->fetch()) {
  
  $list = db::get_string("SELECT * FROM `COMMUNITIES` WHERE `ID` = ? LIMIT 1", [$list2['COMMUNITY_ID']]);
  
  require (ROOT.'/modules/communities/plugins/list.php');
  echo $comm_list;
  
}

if ($column > 0){ 
  
  ?></div><?
  
}

get_page('/m/communities/users/?id='.$account['ID'].'&', $spage, $page, 'list');

back('/id'.$account['ID'], 'К странице');
acms_footer();
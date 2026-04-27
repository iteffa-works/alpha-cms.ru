<?php  
html::title('Почта');
acms_header();
access('users');

?>  
<div id="search_close">
<div class="search_result"></div>
<div id="search-phone" style="display: none"></div>
</div>

<div class='list'>
<a href='/account/mail/write/' class='btn'><?=icons('envelope', 16, 'fa-fw')?> <?=LG('Написать')?></a>  
<a href='/account/mail/settings/' style='float: right; margin-top: 5px'><?=icons('gear', 25, 'fa-fw')?></a>
</div>
  
<div id='messages' action='/account/mail/?page=<?=tabs(get('page'))?>'>
<?
  
require (ROOT.'/users/account/mail/plugins/mail.php');
  
$column = db::get_column("SELECT COUNT(`ID`) FROM `MAIL` WHERE `MY_ID` = ?", [user('ID')]);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){
  
  html::empty('Пока нет собеседников');
  
}else{
  
  ?><div class='list-body'><?
  
}
  
$data = db::get_string_all("SELECT * FROM `MAIL` WHERE `MY_ID` = ? ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [user('ID')]);
while ($list = $data->fetch()){
  
  require (ROOT.'/users/account/mail/plugins/list_kont.php');
  
}

if ($column > 0){
  
  ?></div><?
  
}

get_page('/account/mail/?', $spage, $page, 'list');

?></div><?
  
if (user('MESSAGES_PRINTS') > 0){
  
  db::get_set("UPDATE `USERS` SET `MESSAGES_PRINTS` = '0' WHERE `ID` = ? LIMIT 1", [user('ID')]);

}
  
back('/id'.user('ID'), 'К аккаунту');
acms_footer();
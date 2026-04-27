<?php
acms_header('Онлайн помощники');

?> 
<div class='menu-nav-content'>  
<a class='menu-nav' href='/m/list_administration/'>
<?=lg('Администрация')?>
</a>    
<a class='menu-nav h' href='/m/list_administration/assistants/'>
<?=lg('Онлайн помощники')?>
</a>  
</div>
<?

$column = db::get_column("SELECT COUNT(*) FROM `USERS` WHERE `ASSISTANTS` = '1'");

if ($column == 0){ 
  
  html::empty('Пока пусто');
  
}else{
  
  ?>
  <div class='list-body'>
  <div class='list-menu'><?=lg('Онлайн помощники, которым Вы можете задавать свои вопросы по сайту')?>:</div>
  <? 
  
}

$data = db::get_string_all("SELECT * FROM `USERS` WHERE `ASSISTANTS` = '1' ORDER BY `DATE_VISIT` DESC");
while ($list = $data->fetch()) {
  
  $access = db::get_string("SELECT `NAME` FROM `PANEL_ACCESS_USER` WHERE `ACCESS` = ? LIMIT 1", [$list['ACCESS']]);  
  $list['USER_ID'] = $list['ID'];
  
  $dop = ' - <font color="green">'.lg(tabs($access['NAME'])).'</font><br /><br />
  <a href="/account/mail/messages/?id='.$list['ID'].'&'.TOKEN_URL.'" class="btn">'.icons('envelope', 15, 'fa-fw').' '.lg('Написать').'</a>
  ';
  
  require (ROOT.'/modules/users/plugins/list-mini.php');
  echo $list_mini;

}

if ($column > 0){

  ?></div><?
  
}

back('/', 'На главную');
acms_footer();
<?php
acms_header('Члены администрации', 'administration_show');

?>
<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<a href='/admin/site/'><?=lg('Настройки сайта')?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('Члены администрации')?>
</div>
<?
  
if (get('assistants') == 'add' && MANAGEMENT == 1 && db::get_column("SELECT COUNT(*) FROM `USERS` WHERE `ID` = ? LIMIT 1", [intval(get('aid'))]) > 0) {
  
  get_check_valid();
  db::get_set("UPDATE `USERS` SET `ASSISTANTS` = ? WHERE `ID` = ? LIMIT 1", [1, intval(get('aid'))]);
  
  success('Пользователю успешно выданы права онлайн помощника');
  redirect('/admin/site/users/?get=add&id='.intval(get('aid')));
  
}

if (get('assistants') == 'delete' && MANAGEMENT == 1 && db::get_column("SELECT COUNT(*) FROM `USERS` WHERE `ID` = ? LIMIT 1", [intval(get('aid'))]) > 0) {
  
  get_check_valid();
  db::get_set("UPDATE `USERS` SET `ASSISTANTS` = ? WHERE `ID` = ? LIMIT 1", [0, intval(get('aid'))]);
  
  success('С пользователя успешно сняты права онлайн помощника');
  redirect('/admin/site/users/?get=add&id='.intval(get('aid')));
  
}

if (get('get') == 'add' && MANAGEMENT == 1){
  
  if (get('id')){
    
    $id = intval(get('id'));
  
  }else{
    
    $id = null;
  
  }
  
  if (post('ok')){
    
    $us = intval(post('us'));
    $access = intval(post('access'));
    
    $account = db::get_string("SELECT `ID`,`ACCESS` FROM `USERS` WHERE `ID` = ? LIMIT 1", [$us]);  
    
    if (!isset($account['ID'])){
      
      error('Такого пользователя не существует');
      redirect('/admin/site/users/?get=add');
    
    }
    
    if ($account['ACCESS'] == 99){
      
      error('Неизвестная ошибка');
      redirect('/admin/site/users/?get=add');
    
    }
    
    if ($access == 98){
      
      $management = 1;
    
    }else{
      
      $management = 0;
    
    }
    
    db::get_set("UPDATE `USERS` SET `MANAGEMENT` = ?, `ACCESS` = ? WHERE `ID` = ? LIMIT 1", [$management, $access, $account['ID']]);
    
    $message = "Администрация сайта выдала вам новые права на сайте.";
    messages::get(intval(config('SYSTEM')), $account['ID'], $message);
    
    success('Пользователю успешно выданы права');
    redirect('/admin/site/users/');
  
  }
  
  ?>
  <div class='list'>
  <form method='post' class='ajax-form' action='/admin/site/users/?get=add'>    
  <?php    
  html::input('us', 'ID', 'Введите ID пользователя:', null, $id);
  
  if (get('id') && db::get_column("SELECT COUNT(*) FROM `USERS` WHERE `ID` = ?", [$id]) == 1){
    
    ?>
    <?=lg('Выбран пользователь')?>: <a href='/id<?=$account['ID']?>' ajax='no'><?=icons('user', 15, 'fa-fw')?> <?=user::login_mini($id)?></a>
    <br /><br />
    <?
    
  }
  ?>
    
  <b><?=lg('Выберите права')?>:</b><br />
  
  <?php
  $array = array();
  $data = db::get_string_all("SELECT * FROM `PANEL_ACCESS_USER` WHERE `ACCESS` != '99' ORDER BY `ID` DESC");  
  while ($list = $data->fetch()){
    
    $array[$list['ACCESS']] = [$list['NAME'], (0 == $list['ACCESS'] ? "selected" : null)];
  
  }
  html::select('access', $array, 'Права', 'form-control-100-modify-select', 'lock');
  html::button('button ajax-button', 'ok', 'plus', 'Выдать права');
  
  ?> 
  <?php if (db::get_column("SELECT COUNT(*) FROM `USERS` WHERE `ID` = ? AND `ASSISTANTS` = ? LIMIT 1", [$id, 1]) > 0) : ?>
  <a href='/admin/site/users/?assistants=delete&<?=TOKEN_URL?>&aid=<?=$id?>' class='button2'><?=lg('Удалить из онлайн помощников')?></a>
  <?php elseif ($id > 0) : ?>
  <a href='/admin/site/users/?assistants=add&<?=TOKEN_URL?>&aid=<?=$id?>' class='button3'><?=lg('Сделать онлайн помощником')?></a>
  <?php endif ?>
  </form>
  </div>
  <?
  
}

if (get('delete') && MANAGEMENT == 1){
  
  get_check_valid();
  
  $account = db::get_string("SELECT `ID`,`ACCESS` FROM `USERS` WHERE `ID` = ? LIMIT 1", [intval(get('delete'))]);
  
  if (!isset($account['ID'])){
    
    error('Такого пользователя не существует');
    redirect('/admin/site/users/');
  
  }
  
  if ($account['ACCESS'] == 99){
    
    error('Неизвестная ошибка');
    redirect('/admin/site/users/');
    
  }
  
  db::get_set("UPDATE `USERS` SET `MANAGEMENT` = '0', `ACCESS` = '1' WHERE `ID` = ? LIMIT 1", [$account['ID']]);
  
  $message = "Администрация сайта обнулила ваши права на сайте.";
  messages::get(intval(config('SYSTEM')), $account['ID'], $message);
  
  success('С пользователя сняты права');
  redirect('/admin/site/users/');
  
}
  
$column = db::get_column("SELECT COUNT(*) FROM `USERS` WHERE `ACCESS` > '1'");
$spage = SPAGE($column, PAGE_SETTINGS);
$page = PAGE($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

?>
<div class='list'>
<a href='/admin/site/users/?get=add' class='button'><?=icons('plus')?> <?=lg('Выдать права')?></a>
</div>
<div class='list-body'>  
<?

if ($column == 0){ 
  
  html::empty();
  
}

$data = db::get_string_all("SELECT * FROM `USERS` WHERE `ACCESS` > '1' ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS);
while ($list = $data->fetch()) {
  
  $access = db::get_string("SELECT `NAME` FROM `PANEL_ACCESS_USER` WHERE `ACCESS` = ? LIMIT 1", [$list['ACCESS']]);  
  $list['USER_ID'] = $list['ID'];
  
  $dop = ' - '.tabs($access['NAME']);
  
  if ($list['ACCESS'] != 99 && MANAGEMENT == 1){
    
    $dop2 = '<br /><br /><a href="/admin/site/users/?delete='.$list['ID'].'&'.TOKEN_URL.'" class="button2">'.icons('minus', 15, 'fa-fw').' '.lg('Снять права').'</a>';
    
  }else{
    
    $dop2 = null;
    
  }
  
  require (ROOT.'/modules/users/plugins/list-mini.php');
  echo $list_mini;
  
}

?></div><?

get_page('/admin/site/users/?', $spage, $page, 'list');
    
back('/admin/site/');
acms_footer();
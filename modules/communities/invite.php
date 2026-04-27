<?php
$comm = db::get_string("SELECT `ID`,`PRIVATE`,`URL`,`NAME` FROM `COMMUNITIES` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
$par = db::get_string("SELECT `ADMINISTRATION` FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? AND `ACT` = ? LIMIT 1", [$comm['ID'], user('ID'), 1]);
acms_header(lg('Приглашения в сообщество %s', communities::name($comm['ID'])), 'users');
communities::blocked($comm['ID']);
is_active_module('PRIVATE_COMMUNITIES');

if (!isset($comm['ID'])) {
  
  error('Неверная директива');
  redirect('/m/communities/');

}
  
if (get('get') == 'add'){
  
  $url = '/m/communities/invite/?id='.$comm['ID'].'&get=add';
  
  if ($comm['PRIVATE'] == 1 && $par['ADMINISTRATION'] == 0){
    
    error('Приглашать участников могут только создатели, администраторы и модераторы');
    redirect('/m/communities/invite/?id='.$comm['ID']);
    
  }
  
  if (get('inv_user')){
    
    get_check_valid();
    
    $inv_user = intval(get('inv_user'));
    
    if (db::get_column("SELECT COUNT(*) FROM `USERS` WHERE `ID` = ? LIMIT 1", [$inv_user]) == 0){
      
      error('Такого пользователя не существует');
      redirect($url);
    
    }
    
    if (user('ID') == $inv_user){

      error('Вы не можете пригласить самого себя');
      redirect($url);
    
    }
    
    if (config('SYSTEM') == $inv_user){

      error('Нельзя приглашать системный аккаунт');
      redirect($url);
    
    }
    
    $mail_set_user = db::get_string("SELECT * FROM `MAIL_SETTINGS` WHERE `USER_ID` = ? LIMIT 1", [$inv_user]);
    
    if ($mail_set_user['PRIVATE'] == 1 && db::get_column("SELECT COUNT(`ID`) FROM `FRIENDS` WHERE `USER_ID` = ? AND `MY_ID` = ? AND `ACT` = '0' LIMIT 1", [user('ID'), $inv_user]) == 0){
      
      error('Данному пользователю могут писать только друзья');
      redirect($url);
    
    }
    
    if ($mail_set_user['PRIVATE'] == 2){
      
      error('Вы не можете писать письма этому пользователю, так как он закрыл свою почту от всех');
      redirect($url);
    
    }
    
    if (db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? LIMIT 1", [$comm['ID'], $inv_user]) > 0){
      
      error('Данный пользователь уже приглашен или состоит в сообществе');
      redirect($url);
    
    }
    
    if (ERROR_LOG == 1){
      
      redirect($url);
    
    }
    
    db::get_add("INSERT INTO `COMMUNITIES_PAR` (`USER_ID`, `COMMUNITY_ID`, `ACT`) VALUES (?, ?, ?)", [$inv_user, $comm['ID'], 2]);
    
    $message = lg('Пользователь %s приглашает вас в сообщество %s', '[b]'.user::login_mini(user('ID')).'[/b]', '[b]'.communities::url($comm['ID']).'[/b]').'. [url=/m/communities/invitations/?id='.$comm['ID'].']'.lg('Перейти к приглашениям').'[/url]';
    messages::get(intval(config('SYSTEM')), $inv_user, $message);

    db::get_add("INSERT INTO `COMMUNITIES_JURNAL` (`COMMUNITY_ID`, `TIME`, `MESSAGE`) VALUES (?, ?, ?)", [$comm['ID'], TM, lg('%s пригласил(-а) в сообщество %s', '[url='.user::url(user('ID')).']'.user::login_mini(user('ID')).'[/url]', '[url='.user::url($inv_user).']'.user::login_mini($inv_user).'[/url]')]);
    
    success('Пользователь успешно приглашен');
    redirect('/m/communities/invite/?id='.$comm['ID']);
    
  }
  
  if (post('ok_inv')){
    
    valid::create(array(
      
      'INV_ID' => ['id', 'number', [0, 99999999999999999999999], 'ID']
    
    ));
    
    if (db::get_column("SELECT COUNT(*) FROM `USERS` WHERE `ID` = ? LIMIT 1", [INV_ID]) == 0){
      
      error('Такого пользователя не существует');
      redirect($url);
    
    }
    
    if (user('ID') == INV_ID){

      error('Вы не можете пригласить самого себя');
      redirect($url);
    
    }
    
    if (config('SYSTEM') == INV_ID){

      error('Нельзя приглашать системный аккаунт');
      redirect($url);
    
    }
    
    $mail_set_user = db::get_string("SELECT * FROM `MAIL_SETTINGS` WHERE `USER_ID` = ? LIMIT 1", [INV_ID]);
    
    if ($mail_set_user['PRIVATE'] == 1 && db::get_column("SELECT COUNT(`ID`) FROM `FRIENDS` WHERE `USER_ID` = ? AND `MY_ID` = ? AND `ACT` = '0' LIMIT 1", [user('ID'), INV_ID]) == 0){
      
      error('Данному пользователю могут писать только друзья');
      redirect($url);
    
    }
    
    if ($mail_set_user['PRIVATE'] == 2){
      
      error('Вы не можете писать письма этому пользователю, так как он закрыл свою почту от всех');
      redirect($url);
    
    }
    
    if (db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? LIMIT 1", [$comm['ID'], INV_ID]) > 0){
      
      error('Данный пользователь уже приглашен или состоит в сообществе');
      redirect($url);
    
    }
    
    if (ERROR_LOG == 1){
      
      redirect($url);
    
    }
    
    db::get_add("INSERT INTO `COMMUNITIES_PAR` (`USER_ID`, `COMMUNITY_ID`, `ACT`) VALUES (?, ?, ?)", [INV_ID, $comm['ID'], 2]);
    
    $message = lg('Пользователь %s приглашает вас в сообщество %s', '[b]'.user::login_mini(user('ID')).'[/b]', '[b]'.communities::url($comm['ID']).'[/b]').'. [url=/m/communities/invitations/?id='.$comm['ID'].']'.lg('Перейти к приглашениям').'[/url]';
    messages::get(intval(config('SYSTEM')), INV_ID, $message);

    db::get_add("INSERT INTO `COMMUNITIES_JURNAL` (`COMMUNITY_ID`, `TIME`, `MESSAGE`) VALUES (?, ?, ?)", [$comm['ID'], TM, lg('%s пригласил(-а) в сообщество %s', '[url='.user::url(user('ID')).']'.user::login_mini(user('ID')).'[/url]', '[url='.user::url(INV_ID).']'.user::login_mini(INV_ID).'[/url]')]);
    
    success('Пользователь успешно приглашен');
    redirect('/m/communities/invite/?id='.$comm['ID']);
    
  }
  
  ?>
  <div class='list'>
  <form method='post' class='ajax-form' action='/m/communities/invite/?id=<?=$comm['ID']?>&get=add'>
  <?=html::input('id', 'ID пользователя', null, null, null, 'form-control-30', 'number', null, 'user-plus')?>
  <?=html::button('button ajax-button', 'ok_inv', 'plus', 'Пригласить')?>
  </form>
  </div>
  <?
    
  $column = db::get_column("SELECT COUNT(`ID`) FROM `FRIENDS` WHERE `MY_ID` = ? AND `ACT` = '0'", [user('ID')]);
  $spage = spage($column, PAGE_SETTINGS);
  $page = page($spage);
  $limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;
  
  if ($column > 0){ 
    
    ?><div class='list-body'><?
    
  }
  
  $data = db::get_string_all("SELECT * FROM `FRIENDS` WHERE `MY_ID` = ? AND `ACT` = '0' ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [user('ID')]);
  while ($list = $data->fetch()) {
    
    $m = '<a href="/m/communities/invite/?id='.$comm['ID'].'&get=add&inv_user='.$list['USER_ID'].'&'.TOKEN_URL.'" class="btn">'.icons('plus', 15, 'fa-fw').' '.lg('Пригласить').'</a>';
    
    $mail_set_user = db::get_string("SELECT * FROM `MAIL_SETTINGS` WHERE `USER_ID` = ? LIMIT 1", [$list['USER_ID']]);
    
    if ($mail_set_user['PRIVATE'] == 1 && db::get_column("SELECT COUNT(`ID`) FROM `FRIENDS` WHERE `USER_ID` = ? AND `MY_ID` = ? AND `ACT` = '0' LIMIT 1", [user('ID'), $list['USER_ID']]) == 0){
      
      $m = '<font color="#FE3B7C">'.lg('Данному пользователю могут писать только друзья').'</font>';
    
    }
    
    if ($mail_set_user['PRIVATE'] == 2){
      
      $m = '<font color="#FE3B7C">'.lg('Вы не можете писать письма этому пользователю, так как он закрыл свою почту от всех').'</font>';
    
    }
    
    if (db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? LIMIT 1", [$comm['ID'], $list['USER_ID']]) > 0) {
      
      $m = '<font color="#27BC8C">'.lg('Данный пользователь уже приглашен или состоит в сообществе').'</font>';
    
    }
    
    $dop = '
    <br /><br />
    '.$m;

    require (ROOT.'/modules/users/plugins/list-mini.php');
    echo $list_mini;
  
  }
  
  if ($column > 0){ 
    
    ?></div><?
    
  }
  
  get_page('/m/communities/invite/?id='.$comm['ID'].'&get=add&', $spage, $page, 'list');
  
  back('/m/communities/invite/?id='.$comm['ID']);  
  acms_footer();
  
}

?>
<div class='list'>
<center><b><?=lg('Пользователи, приглашенные в сообщество')?> <a href='<?=communities::url($comm['ID'])?>'><?=communities::name($comm['ID'])?></a></b></center>
</div>
  
<div class='list'>
<a class='btn' href='/m/communities/invite/?id=<?=$comm['ID']?>&get=add'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Пригласить')?></a>
</div>
<?

$column = db::get_column("SELECT COUNT(`ID`) FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `ACT` = ?", [$comm['ID'], 2]);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><?
  
}

$data = db::get_string_all("SELECT * FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `ACT` = ? ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$comm['ID'], 2]);
while ($list = $data->fetch()) {
  
  require (ROOT.'/modules/users/plugins/list-mini.php');
  echo $list_mini;
  
}

if ($column > 0){
  
  ?></div><?
  
}

get_page('/m/communities/invite/?id='.$comm['ID'].'&', $spage, $page, 'list');

back(communities::url($comm['ID']));
acms_footer();
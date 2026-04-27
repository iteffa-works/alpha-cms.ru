<?php
$comm = db::get_string("SELECT `ID`,`URL`,`USER_ID`,`NAME` FROM `COMMUNITIES` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
$par = db::get_string("SELECT `ID`,`ADMINISTRATION` FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? AND `ACT` = ? LIMIT 1", [$comm['ID'], user('ID'), 1]);
html::title(lg('Участники сообщества %s', communities::name($comm['ID'])));
acms_header();
communities::blocked($comm['ID']);

if (!isset($comm['ID'])) {
  
  error('Неверная директива');
  redirect('/m/communities/');

}

if (config('PRIVATE_COMMUNITIES') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

if (isset($par['ID']) && $par['ADMINISTRATION'] == 2 || isset($par['ID']) && $par['ADMINISTRATION'] == 1 || access('communities', null) == true){
  
  /*
  ----------------------
  Удаление из сообщества
  ----------------------
  */
  
  $delete = db::get_string("SELECT `ID`,`USER_ID` FROM `COMMUNITIES_PAR` WHERE `ADMINISTRATION` != '1' AND `ACT` = '1' AND `ID` = ? LIMIT 1", [intval(get('delete'))]);
  
  if (get('delete') && isset($delete['ID'])){
    
    get_check_valid();
    
    $message = "Вы были удалены из сообщества [url=/public/".$comm['URL']."]".$comm['NAME']."[/url].";
    messages::get(intval(config('SYSTEM')), $delete['USER_ID'], $message);
    
    db::get_set("DELETE FROM `COMMUNITIES_PAR` WHERE `ID` = ? LIMIT 1", [$delete['ID']]);
    
    success('Пользователь удален из сообщества');
    redirect('/m/communities/participants/?id='.$comm['ID']);
  
  }
  
  /*
  -------------------
  Редактирование прав
  -------------------
  */
  
  if (get('edit')){
    
    $edit_user = db::get_string("SELECT `ID`,`USER_ID`,`ADMINISTRATION` FROM `COMMUNITIES_PAR` WHERE `ADMINISTRATION` != '1' AND `ACT` = '1' AND `ID` = ? LIMIT 1", [intval(get('edit'))]);
    $account = db::get_string("SELECT `ID`,`LOGIN` FROM `USERS` WHERE `ID` = ? LIMIT 1", [$edit_user['USER_ID']]);
    
    if (!isset($account['ID'])){
      
      error('Такого пользователя не существует');
      redirect('/m/communities/participants/?id='.$comm['ID']);
    
    }
    
    if (user('ID') == $account['ID']){
      
      error('Неизвестная ошибка', 'session');
      redirect('/m/communities/participants/?id='.$comm['ID']);
    
    }
    
    if (isset($edit_user['ID'])){
      
      if (post('ok_edit_comm_us')){
        
        valid::create(array(
          
          'ACCESS_COMM_US' => ['access', 'number', [0, 5], 'Права']
        
        ));
        
        db::get_set("UPDATE `COMMUNITIES_PAR` SET `ADMINISTRATION` = ? WHERE `ID` = ? LIMIT 1", [ACCESS_COMM_US, $edit_user['ID']]);
        
        $message = "Администрация сообщества [url=/public/".$comm['URL']."]".$comm['NAME']."[/url] выдала вам новые права.";
        messages::get(intval(config('SYSTEM')), $account['ID'], $message);
        
        success('Изменения успешно приняты');
        redirect('/m/communities/participants/?id='.$comm['ID']);
      
      }
      
      ?>
      <div class='list'>
      <b><?=user::login_mini($account['ID'])?></b>:<br />
      <form method='post' class='ajax-form' action='/m/communities/participants/?id=<?=$comm['ID']?>&edit=<?=$edit_user['ID']?>'>
      <?=html::select('access', array(
        0 => ['Обычный участник', ($edit_user['ADMINISTRATION'] == 0 ? "selected" : null)], 
        2 => ['Администратор', ($edit_user['ADMINISTRATION'] == 2 ? "selected" : null)], 
        3 => ['Модератор', ($edit_user['ADMINISTRATION'] == 3 ? "selected" : null)]
      ), 'Права', 'form-control-100-modify-select', 'lock')?>
      <?=html::button('button ajax-button', 'ok_edit_comm_us', 'save', 'Сохранить')?>  
      <a class='button-o' href='/m/communities/participants/?id=<?=$comm['ID']?>'><?=lg('Отмена')?></a>
      </form>
      </div>
      <?
      
    }
  
  }
  
}

$column = db::get_column("SELECT COUNT(`ID`) FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `ACT` = ?", [$comm['ID'], 1]);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><?
  
}

$data = db::get_string_all("SELECT * FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `ACT` = ? ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$comm['ID'], 1]);
while ($list = $data->fetch()) {
  
  if ($list['ADMINISTRATION'] == 1){
    
    $dop = '<br />'.lg('Создатель');
    
  }elseif ($list['ADMINISTRATION'] == 2){
    
    $dop = '<br />'.lg('Администратор');
    
  }elseif ($list['ADMINISTRATION'] == 3){
    
    $dop = '<br />'.lg('Модератор');
    
  }else{
    
    $dop = null;
    
  }
  
  $dop2 = null;  
  if ($comm['USER_ID'] != $list['USER_ID']){
    
    if (isset($par['ID']) && $par['ADMINISTRATION'] == 1 || access('communities', null) == true || isset($par['ID']) && $par['ADMINISTRATION'] == 2){
      
      $dop2 = "
      <a class='btn' href='/m/communities/participants/?id=".$comm['ID']."&edit=".$list['ID']."'>".icons('lock', 15, 'fa-fw')." ".lg('Права')."</a>
      <a class='btn' href='/m/communities/participants/?id=".$comm['ID']."&delete=".$list['ID']."&".TOKEN_URL."'>".icons('times', 15, 'fa-fw')." ".lg('Удалить')."</a>
      ";
      
    }
    
  }
  
  require (ROOT.'/modules/users/plugins/list-mini.php');
  echo $list_mini;
  
}

if ($column > 0){
  
  ?></div><?
  
}

get_page('/m/communities/participants/?id='.$comm['ID'].'&', $spage, $page, 'list');

back('/public/'.$comm['URL']);
acms_footer();
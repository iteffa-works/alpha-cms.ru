<?php
$account = db::get_string("SELECT `ID`,`ACCESS` FROM `USERS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);  
  
html::title(lg('История блокировок %s на форуме', user::login_mini($account['ID'])));
acms_header();

if (!isset($account['ID'])){
  
  error('Пользователь не найден');
  redirect('/');
  
}

$column = db::get_column("SELECT COUNT(*) FROM `FORUM_BAN` WHERE `USER_ID` = ?", [$account['ID']]);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty(lg('У пользователя %s нет нарушений на форуме', user::login_mini($account['ID'])));

}else{
  
  ?><div class='list-body'><? 
  
}

if (access('users_blocked', null) == true && get('delete') && db::get_column("SELECT COUNT(*) FROM `FORUM_BAN` WHERE `ID` = ? LIMIT 1", [intval(get('delete'))]) > 0){
  
  get_check_valid();
  
  db::get_set("DELETE FROM `FORUM_BAN` WHERE `ID` = ? LIMIT 1", [intval(get('delete'))]);
  
  success('Блокировка удалена');
  redirect('/m/block/forum_list/?id='.$account['ID']);

}

$data = db::get_string_all("SELECT * FROM `FORUM_BAN` WHERE `USER_ID` = ? ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$account['ID']]);
while ($list = $data->fetch()){
  
  if ($list['REASON'] == 1){
    
    $reason = 'СПАМ, реклама';
  
  }elseif ($list['REASON'] == 2){
    
    $reason = 'Мошенничество';
  
  }elseif ($list['REASON'] == 3){
    
    $reason = 'Нецензурная брань, оскорбления';
  
  }elseif ($list['REASON'] == 4){
    
    $reason = 'Разжигание ненависти';
  
  }elseif ($list['REASON'] == 5){
    
    $reason = 'Пропаганда нацизма';
  
  }elseif ($list['REASON'] == 6){
    
    $reason = 'Пропаганда наркотиков';
  
  }elseif ($list['REASON'] == 7){
    
    $reason = 'Систематические нарушения';
  
  }elseif ($list['REASON'] == 0){
    
    $reason = 'Иная';
  
  }
  
  if ($list['BAN'] == 1){
    
    $block_time = lg('Навсегда')."<br /><br />";
  
  }elseif ($list['BAN_TIME'] < TM){
    
    $block_time = lg('истек')."<br /><br />";
    
  }else{
    
    $block_time = lg('до')." ".ftime($list['BAN_TIME'])."<br /><br />";
  
  }
  
  ?>
  <div class='list-menu'>
  <b><?=lg('Причина блокировки')?>:</b> <?=lg($reason)?><br /><br />
  <b><?=lg('Заблокировал')?>:</b> <?=user::login($list['ADM_ID'], 0, 1)?><br /><br />
  <?=lg('Комментарий')?>:</b> <?=text($list['MESSAGE'])?><br /><br />
  <?=lg('Время блокировки')?>:</b> <?=ftime($list['TIME'])?><br /><br />
  <b><?=lg('Срок')?>:</b> <?=$block_time?>
  <?
  
  if (access('users_blocked', null) == true) {
    
    ?>
    <a href='/m/block/forum_list/?id=<?=$account['ID']?>&delete=<?=$list['ID']?>&<?=TOKEN_URL?>' class='btn'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Удалить')?></a>
    <?
      
  }
  
  ?></div><?

}

if ($column > 0){
  
  ?></div><?

}

get_page('/m/block/forum_list/?id='.$account['ID'].'&', $spage, $page, 'list');
  
back('/id'.$account['ID']);  
acms_footer();
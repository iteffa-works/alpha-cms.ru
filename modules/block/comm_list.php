<?php
$comm = db::get_string("SELECT `ID`,`USER_ID`,`URL` FROM `COMMUNITIES` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]); 
acms_header(lg('История блокировок %s сообщества', communities::name($comm['ID'])));

if (!isset($comm['ID'])){
  
  error('Неверная директива');
  redirect('/');
  
}

$column = db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_BAN` WHERE `COMMUNITY_ID` = ?", [$comm['ID']]);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty(lg('У сообщества %s нет нарушений', communities::name($comm['ID'])));

}else{
  
  ?><div class='list-body'><? 
  
}

if (access('communities', null) == true && get('delete')){
  
  get_check_valid();
  
  $ban = db::get_string("SELECT `BAN`,`ID` FROM `COMMUNITIES_BAN` WHERE `ID` = ? LIMIT 1", [intval(get('delete'))]);
  
  if (isset($ban['ID'])) {
    
    if ($ban['BAN'] == 1) { 
      
      db::get_set("UPDATE `COMMUNITIES` SET `BAN` = ? WHERE `ID` = ? LIMIT 1", [0, $comm['ID']]);
    
    }
    
    db::get_set("DELETE FROM `COMMUNITIES_BAN` WHERE `ID` = ? LIMIT 1", [intval(get('delete'))]);
  
    success('Блокировка удалена');
    redirect('/m/block/comm_list/?id='.$comm['ID']);
    
  }

}

$data = db::get_string_all("SELECT * FROM `COMMUNITIES_BAN` WHERE `COMMUNITY_ID` = ? ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$comm['ID']]);
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
  
  if (access('communities', null) == true) {
    
    ?>
    <a href='/m/block/comm_list/?id=<?=$comm['ID']?>&delete=<?=$list['ID']?>&<?=TOKEN_URL?>' class='btn'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Удалить')?></a>
    <?
      
  }
  
  ?></div><?

}

if ($column > 0){
  
  ?></div><?

}

get_page('/m/block/comm_list/?id='.$comm['ID'].'&', $spage, $page, 'list');
  
back('/public/'.$comm['URL']);  
acms_footer();
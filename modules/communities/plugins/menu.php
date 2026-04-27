<div style='margin-top: -20px; margin-left: 13px; margin-bottom: 20px'>
<div id='int'>

<?php
  
if (get('get') == "app_go" && !isset($par['ID']) && $comm['PRIVATE'] == 2){
  
  get_check_valid();
  
  if (db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? AND `ACT` = ? LIMIT 1", [$comm['ID'], user('ID'), 0]) == 0){
    
    db::get_add("INSERT INTO `COMMUNITIES_PAR` (`USER_ID`, `COMMUNITY_ID`, `ACT`) VALUES (?, ?, ?)", [user('ID'), $comm['ID'], 0]);  
    db::get_add("INSERT INTO `COMMUNITIES_JURNAL` (`COMMUNITY_ID`, `TIME`, `MESSAGE`) VALUES (?, ?, ?)", [$comm['ID'], TM, '[url=/id'.user('ID').']'.user('LOGIN').'[/url] подал заявку на вступление в сообщество']);
    $message = lg('Пользователь %s хочет вступить в ваше сообщество %s', '[b]'.user('LOGIN').'[/b]', '[b]'.$comm['NAME'].'[/b]').'. [url=/m/communities/applications/?id='.$comm['ID'].']'.lg('Перейти к заявкам').'[/url]';
    messages::get(intval(config('SYSTEM')), $comm['USER_ID'], $message);
    
  }

} 

if (get('get') == "app_no" && $comm['PRIVATE'] == 2){
  
  get_check_valid();
  
  db::get_set("DELETE FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? AND `ACT` = ?", [$comm['ID'], user('ID'), 0]);
  db::get_add("INSERT INTO `COMMUNITIES_JURNAL` (`COMMUNITY_ID`, `TIME`, `MESSAGE`) VALUES (?, ?, ?)", [$comm['ID'], TM, '[url=/id'.user('ID').']'.user('LOGIN').'[/url] отменил заявку на вступление в сообщество']);

}
  
if (get('get') == "int_go" && !isset($par['ID']) && $comm['PRIVATE'] == 0){
  
  get_check_valid();
  
  if (db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? AND `ACT` = ? LIMIT 1", [$comm['ID'], user('ID'), 2]) > 0){
    
    db::get_set("UPDATE `COMMUNITIES_PAR` SET `ACT` = ? WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? LIMIT 1", [1, $comm['ID'], user('ID')]);
  
  }else{
    
    db::get_add("INSERT INTO `COMMUNITIES_PAR` (`USER_ID`, `COMMUNITY_ID`) VALUES (?, ?)", [user('ID'), $comm['ID']]);
  
  }
  
  db::get_add("INSERT INTO `COMMUNITIES_JURNAL` (`COMMUNITY_ID`, `TIME`, `MESSAGE`) VALUES (?, ?, ?)", [$comm['ID'], TM, '[url=/id'.user('ID').']'.user('LOGIN').'[/url] вступил в сообщество']);

}

if (get('get') == "int_stop" && isset($par['ID']) && $par['ADMINISTRATION'] != 1){
  
  get_check_valid();
  
  db::get_set("DELETE FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ?", [$comm['ID'], user('ID')]);
  db::get_add("INSERT INTO `COMMUNITIES_JURNAL` (`COMMUNITY_ID`, `TIME`, `MESSAGE`) VALUES (?, ?, ?)", [$comm['ID'], TM, '[url=/id'.user('ID').']'.user('LOGIN').'[/url] покинул сообщество']);

}

if ($comm['PRIVATE'] == 0 && db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? AND `ACT` = ? LIMIT 1", [$comm['ID'], user('ID'), 1]) == 0){
  
  if (user('ID') > 0){
    
    ?>
    <a class="menu-profile-button mpb-green" onclick="request('/public/<?=$comm['URL']?>?get=int_go&<?=TOKEN_URL?>', '#int')" ajax="no"><?=icons('plus', 15, 'fa-fw')?> <?=lg('Вступить')?></a>
    <? 
    
  }else{
    
    ?>
    <span class="menu-profile-button mpb-green menu-profile-op"><?=icons('plus', 15, 'fa-fw')?> <?=lg('Вступить')?></span>
    <?
    
  }

}elseif (db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? AND `ACT` = ? LIMIT 1", [$comm['ID'], user('ID'), 1]) > 0){
  
  if (isset($par['ID']) && $par['ADMINISTRATION'] == 1){
    
    ?>
    <span class="menu-profile-button mpb-gray menu-profile-op"><?=icons('minus', 15, 'fa-fw')?> <?=lg('Покинуть')?></span>
    <?
    
  }else{
    
    ?>
    <a class="menu-profile-button mpb-gray" onclick="request('/public/<?=$comm['URL']?>?get=int_stop&<?=TOKEN_URL?>', '#int')" ajax="no"><?=icons('minus', 15, 'fa-fw')?> <?=lg('Покинуть')?></a>
    <?
    
  }

}elseif ($comm['PRIVATE'] == 2 && db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? AND `ACT` = ? LIMIT 1", [$comm['ID'], user('ID'), 0]) == 0){
  
  if (user('ID') > 0){
    
    ?>
    <a class="menu-profile-button mpb-green" style="width: 137px" onclick="request('/public/<?=$comm['URL']?>?get=app_go&<?=TOKEN_URL?>', '#int')" ajax="no"><?=icons('plus', 15, 'fa-fw')?> <?=lg('Подать заявку')?></a>
    <? 
    
  }else{
    
    ?>
    <span class="menu-profile-button mpb-green menu-profile-op" style="width: 137px"><?=icons('plus', 15, 'fa-fw')?> <?=lg('Подать заявку')?></span>
    <?
    
  }

}elseif ($comm['PRIVATE'] == 2 && db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? AND `ACT` = ? LIMIT 1", [$comm['ID'], user('ID'), 0]) > 0){
  
  if (user('ID') > 0){
    
    ?>
    <a class="menu-profile-button mpb-gray" style="width: 153px" onclick="request('/public/<?=$comm['URL']?>?get=app_no&<?=TOKEN_URL?>', '#int')" ajax="no"><?=icons('minus', 15, 'fa-fw')?> <?=lg('Отменить заявку')?></a>
    <? 
    
  }else{
    
    ?>
    <span class="menu-profile-button mpb-gray menu-profile-op" style="width: 153px"><?=icons('minus', 15, 'fa-fw')?> <?=lg('Отменить заявку')?></span>
    <?
    
  }

}

if (user('ID') > 0 && $comm['PRIVATE'] == 0 || isset($par['ID']) && $par['ADMINISTRATION'] > 0 && $comm['PRIVATE'] == 1 || user('ID') > 0 && $comm['PRIVATE'] == 2){
  
  ?>
  <a class="menu-profile-button mpb-blue" href="/m/communities/invite/?id=<?=$comm['ID']?>"><?=icons('user-plus', 15, 'fa-fw')?> <?=lg('Пригласить')?></a>
  <?
  
}else{
  
  ?>
  <span class="menu-profile-button mpb-blue menu-profile-op"><?=icons('user-plus', 15, 'fa-fw')?> <?=lg('Пригласить')?></span>
  <?
  
}

?></div></div><?
  
if (isset($par['ID']) && $par['ADMINISTRATION'] == 1 || access('communities', null) == true){
  
  ?>
  <div style='margin-bottom: 20px'>
  <a href='/m/communities/edit/?id=<?=$comm['ID']?>' class='profile-edit'><?=icons('gear', 17, 'fa-fw')?> <?=lg('Редактировать сообщество')?></a>
  </div>
  <?
  
}
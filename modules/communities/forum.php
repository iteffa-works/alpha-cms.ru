<?php
$comm = db::get_string("SELECT `ID`,`URL`,`USER_ID` FROM `COMMUNITIES` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
$par = db::get_string("SELECT `ADMINISTRATION`,`ID` FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? AND `ACT` = ? LIMIT 1", [$comm['ID'], user('ID'), 1]);
acms_header(lg('Форум сообщества %s', communities::name($comm['ID'])));
communities::blocked($comm['ID']);
is_active_module('PRIVATE_COMMUNITIES');

if (!isset($comm['ID'])) {
  
  error('Неверная директива');
  redirect('/m/communities/');

}

/*
------------------
Редактировать тему
------------------
*/

$id = intval(get('edit_them'));
if ($id > 0 && user('ID') > 0) {
  
  $them = db::get_string("SELECT * FROM `COMMUNITIES_FORUM_THEM` WHERE `ID` = ? AND `COMMUNITY_ID` = ? LIMIT 1", [$id, $comm['ID']]);
  
  if (access('communities', null) == true || $them['USER_ID'] == user('ID') || $comm['USER_ID'] == user('ID')){
    
    if (isset($them['ID'])) {
    
      if (post('ok_edit_them_comm')){
        
        valid::create(array(
          
          'THEM_NAME' => ['name', 'text', [2, 200], 'Название', 0],
          'THEM_MESS' => ['message', 'text', [10, 20000], 'Содержание', 0]
        
        ));
        
        if (THEM_MESS != $them['MESSAGE'] && db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_FORUM_THEM` WHERE `MESSAGE` = ? AND `SECTION_ID` = ? LIMIT 1", [THEM_MESS, $them['SECTION_ID']]) > 0){
          
          error('Тема с таким содержимым уже существует в данном разделе');
          redirect('/m/communities/forum/?id='.$comm['ID'].'&edit_them='.$id);
        
        }
        
        if (ERROR_LOG == 1){
          
          redirect('/m/communities/forum/?id='.$comm['ID'].'&edit_them='.$id);
        
        }
        
        db::get_set("UPDATE `COMMUNITIES_FORUM_THEM` SET `EDIT_TIME` = ?, `EDIT_USER_ID` = ?, `NAME` = ?, `MESSAGE` = ? WHERE `ID` = ? LIMIT 1", [TM, user('ID'), THEM_NAME, THEM_MESS, $them['ID']]);
        
        success('Изменения успешно приняты');
        redirect('/m/communities/forum/?id='.$comm['ID'].'&id_them='.$id);
      
      }
    
      ?>    
      <div class='list'>
      <form method='post' class='ajax-form' action='/m/communities/forum/?id=<?=$comm['ID']?>&edit_them=<?=$id?>'>
      <?
      html::input('name', 'Название', null, null, tabs($them['NAME']), 'form-control-100', 'text', null, 'book');
      define('ACTION', '/m/communities/forum/?id='.$comm['ID'].'&edit_them='.$id);
      define('TYPE', 'communities_forum_them');
      define('ID', $id);
      html::textarea(tabs($them['MESSAGE']), 'message', 'Введите содержимое', null, 'form-control-textarea', 9); 
      ?><br /><br /><?
      html::button('button ajax-button', 'ok_edit_them_comm', 'save', 'Сохранить');  
      ?>
      <a class='button-o' href='/m/communities/forum/?id=<?=$comm['ID']?>&id_them=<?=$id?>'><?=lg('Отмена')?></a>
      <form>
      </div>
      <?
    
      back('/m/communities/forum/?id='.$comm['ID'].'&id_them='.$id);
      acms_footer();
      
    }
    
  }
  
}

/*
-------------
Просмотр темы
-------------
*/

$id = intval(get('id_them'));
if ($id > 0) {
  
  $them = db::get_string("SELECT * FROM `COMMUNITIES_FORUM_THEM` WHERE `ID` = ? LIMIT 1", [$id]);
  
  if (isset($them['ID'])) {
    
    if (user('ID') > 0){
      
      if (db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `USER_ID` = ? AND `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [user('ID'), $them['ID'], 'communities_forum_them']) == 0){
        
        db::get_add("INSERT INTO `EYE` (`USER_ID`, `TIME`, `OBJECT_ID`, `TYPE`) VALUES (?, ?, ?, ?)", [user('ID'), TM, $them['ID'], 'communities_forum_them']);
        db::get_set("UPDATE `COMMUNITIES` SET `RATING` = `RATING` + '1' WHERE `ID` = ? LIMIT 1", [$comm['ID']]);
      
      }else{        
        
        db::get_set("UPDATE `EYE` SET `TIME` = ? WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [TM, $them['ID'], 'communities_forum_them']);
      
      }
    
    }
    
    if (isset($par['ID']) && $par['ADMINISTRATION'] != 0 || access('communities', null) == true){
      
      if (get('them') == "off" && $them['ACTIVE'] == 1){ 
        
        db::get_set("UPDATE `COMMUNITIES_FORUM_THEM` SET `ACTIVE_TIME` = ?, `ACTIVE_USER_ID` = ?, `ACTIVE` = ? WHERE `ID` = ? LIMIT 1", [TM, user('ID'), 0, $them['ID']]);          
        redirect('/m/communities/forum/?id_them='.$them['ID'].'&id='.$comm['ID']);
      
      }
      
      if (get('them') == "on" && fthem['ACTIVE'] == 0){ 
        
        db::get_set("UPDATE `COMMUNITIES_FORUM_THEM` SET `ACTIVE_TIME` = ?, `ACTIVE_USER_ID` = ?, `ACTIVE` = ? WHERE `ID` = ? LIMIT 1", [null, 0, 1, $them['ID']]);          
        redirect('/m/communities/forum/?id_them='.$them['ID'].'&id='.$comm['ID']);
      
      }
    
    }
    
    if (access('communities', null) == true || $them['USER_ID'] == user('ID') || $comm['USER_ID'] == user('ID')){
      
      require_once (ROOT.'/modules/communities/plugins/forum_them_delete.php');
      
      ?>
      <div class='list'>
      <a href='/m/communities/forum/?id=<?=$comm['ID']?>&edit_them=<?=$them['ID']?>' class='btn'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Редактировать')?></a>
      <a href='/m/communities/forum/?id=<?=$comm['ID']?>&id_them=<?=$them['ID']?>&get=delete&<?=TOKEN_URL?>' class='btn'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Удалить')?></a>
      <?php if ($them['ACTIVE'] == 1){ ?>
      <a href='/m/communities/forum/?id=<?=$comm['ID']?>&id_them=<?=$them['ID']?>&them=off&<?=TOKEN_URL?>' class='btn'><?=icons('lock', 15, 'fa-fw')?> <?=lg('Закрыть тему')?></a>
      <?php }else{ ?>
      <a href='/m/communities/forum/?id=<?=$comm['ID']?>&id_them=<?=$them['ID']?>&them=on&<?=TOKEN_URL?>' class='btn'><?=icons('unlock', 15, 'fa-fw')?> <?=lg('Открыть тему')?></a>
      <?php } ?>
      </div>
      <?
        
    }
    
    ?>
    <div class='list-body'>
    <div class='list-menu'>
    <div class='user-info-mini'>
    <div class='user-avatar-mini'>
    <?=user::avatar($them['USER_ID'], 45, 1)?> 
    </div>
    <div class='user-login-mini' style='top: 4px; left: 55px;'>
    <?=user::login($them['USER_ID'], 0, 1)?><br />
    <span class='time'><?=ftime($them['TIME'])?></span>
    </div>
    </div>
    <br />
    <b><?=tabs($them['NAME'])?></b>
    <br />    
    <?=attachments_files($them['ID'], 'communities_forum_them', 320)?>
    <br />
    <?=text($them['MESSAGE'])?>
      
    <?php
      
    if ($them['ACTIVE'] == 0 || $them['EDIT_TIME'] > 0){      
      
      ?><div class='list-menu'><small><?
        
      if ($them['EDIT_TIME'] > 0){
        
        ?>
        <?=lg('Последний раз тему редактировал')?> <a href='/id<?=$them['EDIT_USER_ID']?>'><?=user::login_mini($them['EDIT_USER_ID'])?></a> - <?=ftime($them['EDIT_TIME'])?>
        <br />
        <?
        
      }
      
      if ($them['ACTIVE'] == 0){
        
        ?>
        <?=lg('Тема закрыта пользователем')?> <a href='/id<?=$them['ACTIVE_USER_ID']?>'><?=user::login_mini($them['ACTIVE_USER_ID'])?></a> - <?=ftime($them['ACTIVE_TIME'])?>
        <br />
        <?
        
      }
      
      ?></small></div><?
      
    }     
      
    likes_ajax($them['ID'], 'communities_forum_them', $them['USER_ID'], 1);
    dislikes_ajax($them['ID'], 'communities_forum_them');
    $action = '/m/communities/forum/?id='.$comm['ID'].'&id_them='.$them['ID'];
    ?>
    
    <div id='like'>
    <?=likes_list($them['ID'], 'communities_forum_them', $action)?>
    <div class='menu-sw-cont'>  
    <a class='menu-sw-cont-left-33' href="/m/eye/?id=<?=$them['ID']?>&url=<?=base64_encode($action)?>&type=communities_forum_them&<?=TOKEN_URL?>"><?=icons('eye', 18, 'fa-fw')?>
    <?=db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [$them['ID'], 'communities_forum_them'])?></a><?=mlikes($them['ID'], $action, 'communities_forum_them', 'menu-sw-cont-left-33')?><?=mdislikes($them['ID'], $action, 'communities_forum_them', 'menu-sw-cont-left-33')?>
    </div>
    </div> 
      
    </div>
    </div>
      
    <div class='list'>
    <b><?=lg('Комментарии')?></b> <span class='count'><?=db::get_column("SELECT COUNT(`ID`) FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$them['ID'], 'communities_forum_them_comments'])?></span>
    </div>
      
    <?  
    if (user('ID') == 0 || !isset($par['ID'])){
      
      $comments_set = 'Извините, для вас комментирование недоступно';
    
    }
    
    comments($action, 'communities_forum_them', 1, 'message', $them['USER_ID'], $them['ID']);
    
    back('/m/communities/forum/?id='.$comm['ID'].'&id_sc='.$them['SECTION_ID']);
    acms_footer();
    
  }
  
}

/*
------------
Создать тему
------------
*/

$id = intval(get('add_them'));
if ($id > 0 && user('ID') > 0) {
  
  $section = db::get_string("SELECT `ID` FROM `COMMUNITIES_FORUM_SECTION` WHERE `ID` = ? AND `COMMUNITY_ID` = ? LIMIT 1", [$id, $comm['ID']]);
  
  if (isset($section['ID'])) {
    
    if (post('ok_them_comm')){
      
      valid::create(array(
        
        'THEM_NAME' => ['name', 'text', [2, 200], 'Название', 0],
        'THEM_MESS' => ['message', 'text', [10, 20000], 'Содержание', 0]
      
      ));
      
      if (db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_FORUM_THEM` WHERE `MESSAGE` = ? AND `SECTION_ID` = ? LIMIT 1", [THEM_MESS, $section['ID']]) > 0){
        
        error('Тема с таким содержимым уже существует в данном разделе');
        redirect('/m/communities/forum/?id='.$comm['ID'].'&add_them='.$id);
      
      }
      
      if (ERROR_LOG == 1){
        
        redirect('/m/communities/forum/?id='.$comm['ID'].'&add_them='.$id);
      
      }
      
      $ID = db::get_add("INSERT INTO `COMMUNITIES_FORUM_THEM` (`NAME`, `USER_ID`, `SECTION_ID`, `MESSAGE`, `TIME`, `COMMUNITY_ID`) VALUES (?, ?, ?, ?, ?, ?)", [THEM_NAME, user('ID'), $section['ID'], THEM_MESS, TM, $comm['ID']]);
      
      if (db::get_column("SELECT COUNT(*) FROM `ATTACHMENTS` WHERE `TYPE_POST` = ? AND `ID_POST` = ? LIMIT 1", ['communities_forum_them', 0]) > 0){
        
        db::get_set("UPDATE `ATTACHMENTS` SET `ID_POST` = ?, `ACT` = '1' WHERE `USER_ID` = ? AND `ACT` = '0' AND `TYPE_POST` = ?", [$ID, user('ID'), 'communities_forum_them']);
      
      }
      
      success('Тема успешно создана');
      redirect('/m/communities/forum/?id='.$comm['ID'].'&id_sc='.$id);
      
    }
    
    ?>    
    <div class='list'>
    <form method='post' class='ajax-form' action='/m/communities/forum/?id=<?=$comm['ID']?>&add_them=<?=$id?>'>
    <?
    html::input('name', 'Название', null, null, null, 'form-control-100', 'text', null, 'book');
    define('ACTION', '/m/communities/forum/?id='.$comm['ID'].'&add_them='.$id);
    define('TYPE', 'communities_forum_them');
    define('ID', 0);
    html::textarea(null, 'message', 'Введите содержимое', null, 'form-control-textarea', 9); 
    ?><br /><br /><?
    html::button('button ajax-button', 'ok_them_comm', 'plus', 'Добавить');  
    ?>
    <a class='button-o' href='/m/communities/forum/?id=<?=$comm['ID']?>&id_sc=<?=$id?>'><?=lg('Отмена')?></a>
    <form>
    </div>
    <?
    
    back('/m/communities/forum/?id='.$comm['ID'].'&id_sc='.$id);
    acms_footer();
    
  }
  
}

/*
--------------
Удалить раздел
--------------
*/

$id = intval(get('delete_sc'));
if ($id > 0 && $comm['USER_ID'] == user('ID')) {
  
  $section = db::get_string("SELECT `ID`,`NAME` FROM `COMMUNITIES_FORUM_SECTION` WHERE `ID` = ? AND `COMMUNITY_ID` = ? LIMIT 1", [$id, $comm['ID']]);
  
  if (isset($section['ID'])) {
    
    if (get('get') == 'delete_sc_ok') {
      
      $data = db::get_string_all("SELECT * FROM `COMMUNITIES_FORUM_THEM` WHERE `SECTION_ID` = ?", [$id]);
      while ($list = $data->fetch()){
        
        db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$list['ID'], 'communities_forum_them']);
        db::get_set("DELETE FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ?", [$list['ID'], 'communities_forum_them']);
        db::get_set("DELETE FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$list['ID'], 'communities_forum_them']);
        db::get_set("DELETE FROM `COMMUNITIES_FORUM_THEM` WHERE `ID` = ? LIMIT 1", [$list['ID']]);
      
      }
      
      db::get_set("DELETE FROM `COMMUNITIES_FORUM_SECTION` WHERE `ID` = ?", $id);
      
      success('Раздел успешно удален');
      redirect('/m/communities/forum/?id='.$comm['ID']);
      
    }
    
    ?>
    <div class='list'>
    <?=lg('Вы действительно хотите удалить раздел')?> <b><?=tabs($section['NAME'])?></b>?<br /><br />
    <a href='/m/communities/forum/?id=<?=$comm['ID']?>&delete_sc=<?=$id?>&get=delete_sc_ok' class='button'><?=lg('Да, удалить')?></a>
    <a href='/m/communities/forum/?id=<?=$comm['ID']?>&id_sc=<?=$id?>' class='button-o'><?=lg('Отмена')?></a>  
    </div>
    <?
    
  }
  
  back('/m/communities/forum/?id='.$comm['ID'].'&id_sc='.$id);
  acms_footer();
  
}

/*
--------------------
Редактировать раздел
--------------------
*/

$id = intval(get('edit_sc'));
if ($id > 0 && $comm['USER_ID'] == user('ID')) {
  
  $section = db::get_string("SELECT `ID`,`NAME`,`MESSAGE` FROM `COMMUNITIES_FORUM_SECTION` WHERE `ID` = ? AND `COMMUNITY_ID` = ? LIMIT 1", [$id, $comm['ID']]);
  
  if (isset($section['ID'])) {
    
    if (post('ok_edit_sec_comm')){
      
      valid::create(array(
        
        'CAT_NAME' => ['name', 'text', [1, 1200], 'Название', 0],
        'CAT_MESSAGE' => ['message', 'text', [0, 200], 'Описание', 0]
      
      ));
      
      if (CAT_NAME != $section['NAME'] && db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_FORUM_SECTION` WHERE `NAME` = ? AND `COMMUNITY_ID` = ? LIMIT 1", [CAT_NAME, $comm['ID']]) == 1){
        
        error('Раздел с таким названием уже существует');
        redirect('/m/communities/forum/?edit_sc='.$id.'&id='.$comm['ID']);
       
      }
      
      if (ERROR_LOG == 1){
        
        redirect('/m/communities/forum/?edit_sc='.$id.'&id='.$comm['ID']);
      
      }
      
      db::get_set("UPDATE `COMMUNITIES_FORUM_SECTION` SET `NAME` = ?, `MESSAGE` = ? WHERE `ID` = ? LIMIT 1", [CAT_NAME, CAT_MESSAGE, $section['ID']]);
      
      success('Раздел успешно создан');
      redirect('/m/communities/forum/?id_sc='.$id.'&id='.$comm['ID']);
      
    }
    
    ?>
    <div class='list'>
    <form method='post' class='ajax-form' action='/m/communities/forum/?edit_sc=<?=$id?>&id=<?=$comm['ID']?>'>
    <?
  
    html::input('name', 'Название', null, null, tabs($section['NAME']), 'form-control-100', 'text', null, 'list');
    html::input('message', 'Описание', null, null, tabs($section['MESSAGE']), 'form-control-100', 'text', null, 'list');
    html::button('button ajax-button', 'ok_edit_sec_comm', 'save', 'Сохранить');
  
    ?>
    <a class='button-o' href='/m/communities/forum/?id=<?=$comm['ID']?>'><?=lg('Отмена')?></a>
    </form>
    </div>
    <?
  
    back('/m/communities/forum/?id='.$comm['ID']);
    acms_footer();
    
  }
  
}

/*
----------
Список тем
----------
*/

$id = intval(get('id_sc'));
if ($id > 0) {
  
  $section = db::get_string("SELECT `NAME`,`MESSAGE` FROM `COMMUNITIES_FORUM_SECTION` WHERE `ID` = ? LIMIT 1", [$id]);
  
  $column = db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_FORUM_THEM` WHERE `SECTION_ID` = ?", [$id]);
  $spage = spage($column, PAGE_SETTINGS);
  $page = page($spage);
  $limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;
  
  if (isset($par['ID'])) {
    
    ?>
    <div class='list'>
    <a href='/m/communities/forum/?id=<?=$comm['ID']?>&add_them=<?=$id?>' class='btn'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Содать тему')?></a>
    <?
    
    if ($comm['USER_ID'] == user('ID')) {
      
      ?>
      <a href='/m/communities/forum/?id=<?=$comm['ID']?>&edit_sc=<?=$id?>' class='btn'><?=icons('edit', 15, 'fa-fw')?> <?=lg('Редактировать раздел')?></a>
      <a href='/m/communities/forum/?id=<?=$comm['ID']?>&delete_sc=<?=$id?>' class='btn'><?=icons('times', 15, 'fa-fw')?> <?=lg('Удалить раздел')?></a>  
      <?
        
    }
    
    ?></div><?
    
  }
  
  ?>
  <div class='list'>
  <?=lg('Раздел')?> "<b><?=tabs($section['NAME'])?></b>"
  </div>
  <?
  
  if ($column == 0){ 
    
    html::empty('Пока нет тем');
  
  }else{
    
    ?><div class='list-body'><?
    
  }
  
  $data = db::get_string_all("SELECT * FROM `COMMUNITIES_FORUM_THEM` WHERE `SECTION_ID` = ? ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$id]);
  while ($list = $data->fetch()) {
    
    require (ROOT.'/modules/communities/plugins/forum_them_list.php');
  
  }
  
  if ($column > 0){ 
    
    ?></div><?
    
  }
  
  get_page('/m/communities/forum/?id_sc='.$id.'&id='.$comm['ID'].'&', $spage, $page, 'list');
  
  back('/m/communities/forum/?id='.$comm['ID']);
  acms_footer();
  
}

/*
---------------
Добавить раздел
---------------
*/

if (get('get') == 'add_sc' && $comm['USER_ID'] == user('ID')) {
  
  if (post('ok_add_sec_comm')){
    
    valid::create(array(
      
      'CAT_NAME' => ['name', 'text', [1, 1200], 'Название', 0],
      'CAT_MESSAGE' => ['message', 'text', [0, 200], 'Описание', 0]
    
    ));
    
    if (db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_FORUM_SECTION` WHERE `NAME` = ? AND `COMMUNITY_ID` = ? LIMIT 1", [$name, $comm['ID']]) == 1){
      
      error('Раздел с таким названием уже существует');
      redirect('/m/communities/forum/?id='.$comm['ID'].'&get=add_sc&'.TOKEN_URL);
    
    }
    
    if (ERROR_LOG == 1){
      
      redirect('/m/communities/forum/?id='.$comm['ID'].'&get=add_sc&'.TOKEN_URL);
    
    }
    
    db::get_add("INSERT INTO `COMMUNITIES_FORUM_SECTION` (`NAME`, `MESSAGE`, `COMMUNITY_ID`) VALUES (?, ?, ?)", [CAT_NAME, CAT_MESSAGE, $comm['ID']]);
    
    success('Раздел успешно создан');
    redirect('/m/communities/forum/?id='.$comm['ID']);
  
  }
  
  ?>
  <div class='list'>
  <form method='post' class='ajax-form' action='/m/communities/forum/?get=add_sc&id=<?=$comm['ID']?>&<?=TOKEN_URL?>'>
  <?
  
  html::input('name', 'Название', null, null, null, 'form-control-100', 'text', null, 'list');
  html::input('message', 'Описание', null, null, null, 'form-control-100', 'text', null, 'list');
  html::button('button ajax-button', 'ok_add_sec_comm', 'plus', 'Добавить');
  
  ?>
  <a class='button-o' href='/m/communities/forum/?id=<?=$comm['ID']?>'><?=lg('Отмена')?></a>
  </form>
  </div>
  <?
  
  back('/m/communities/forum/?id='.$comm['ID']);
  acms_footer();
  
}

/*
--------
Мои темы
--------
*/

if (get('get') == 'my_them') {
  
  $column = db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_FORUM_THEM` WHERE `USER_ID` = ?", [user('ID')]);
  $spage = spage($column, PAGE_SETTINGS);
  $page = page($spage);
  $limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;
  
  if ($column == 0){ 
    
    html::empty('Пока пусто');
  
  }else{
    
    ?><div class='list-body'><?
    
  }
  
  $data = db::get_string_all("SELECT * FROM `COMMUNITIES_FORUM_THEM` WHERE `USER_ID` = ? ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [user('ID')]);
  while ($list = $data->fetch()) {
    
    require (ROOT.'/modules/communities/plugins/forum_them_list.php');
    
  }
  
  if ($column > 0){ 
    
    ?></div><?
    
  }
  
  get_page('/m/communities/forum/?id='.$comm['ID'].'&get=my_them&', $spage, $page, 'list');
  
  back('/m/communities/forum/?id='.$comm['ID']);
  acms_footer();
  
}

/*
---------------
Список разделов
---------------
*/

if ($comm['USER_ID'] == user('ID')) {
  
  ?>
  <div class='list'>
  <a href='/m/communities/forum/?get=add_sc&id=<?=$comm['ID']?>&<?=TOKEN_URL?>' class='btn'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Добавить раздел')?></a>
  <a href='/m/communities/forum/?get=my_them&id=<?=$comm['ID']?>' class='btn-o'><?=icons('user', 15, 'fa-fw')?> <?=lg('Мои темы')?></a>
  </div>
  <?
  
}

$column = db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_FORUM_SECTION` WHERE `COMMUNITY_ID` = ?", [$comm['ID']]);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty('Пока нет разделов');
  
}else{
  
  ?><div class='list-body'><?
  
}

$data = db::get_string_all("SELECT * FROM `COMMUNITIES_FORUM_SECTION` WHERE `COMMUNITY_ID` = ? ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$comm['ID']]);
while ($list = $data->fetch()) {
  
  ?>
  <a href='/m/communities/forum/?id=<?=$comm['ID']?>&id_sc=<?=$list['ID']?>'>
  <div class='list-menu hover'>
  <?=icons('angle-double-right', 17, 'fa-fw')?> <b><?=lg(tabs($list['NAME']))?></b> <span class='count'><?=db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_FORUM_THEM` WHERE `SECTION_ID` = ? AND `COMMUNITY_ID` = ?", [$list['ID'], $comm['ID']])?></span>
  <br />
  <span class='time'><?=lg(tabs($list['MESSAGE']))?></span>
  </div>
  </a>
  <?
  
}

if ($column > 0){ 
  
  ?></div><?
  
}

get_page('/m/communities/forum/?id='.$comm['ID'].'&', $spage, $page, 'list');

back('/public/'.$comm['URL']);
acms_footer();
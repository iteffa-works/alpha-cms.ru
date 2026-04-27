<?php  
acms_header('Жалобы', 'administration_show');

db::get_set("UPDATE `ABUSE` SET `READ` = '1'");
  
$column = db::get_column("SELECT COUNT(`ID`) FROM `ABUSE`");
$spage = SPAGE($column, PAGE_SETTINGS);
$page = PAGE($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if (user('ACCESS') == 99) {
  
  ?><div class='list'><?
    
  if (get('delete') && db::get_column("SELECT COUNT(*) FROM `ABUSE` WHERE `ID` = ? LIMIT 1", [intval(get('delete'))]) > 0){
    
    get_check_valid();
    
    db::get_set("DELETE FROM `ABUSE` WHERE `ID` = ? LIMIT 1", [intval(get('delete'))]);
      
    success('Удаление прошло успешно');
    redirect('/admin/notifications/abuse/');
      
  }
    
  if (get('get') == 'delete_all_ok'){
    
    get_check_valid();
    
    db::get_set("DELETE FROM `ABUSE`");
      
    success('Удаление прошло успешно');
    redirect('/admin/notifications/abuse/');
      
  }
  
  if (get('get') == 'delete_all'){
    
    get_check_valid();
    
    ?>
    <?=lg('Вы действительно хотите удалить все жалобы')?>?<br /><br />
    <a href='/admin/notifications/abuse/?get=delete_all_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
    <a href='/admin/notifications/abuse/' class='button-o'><?=lg('Отмена')?></a>
    <?
    
  }else{
    
    ?>
    <a href='/admin/notifications/abuse/?get=delete_all&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить все жалобы')?></a>
    <?
      
  }
  
  ?></div><?
    
}

?>
<div class='list-body'>
<div class='list-menu list-title'>  
<?=lg('Жалобы от пользователей')?> <span class='count'><?=$column?></span>
</div>
<?

if ($column == 0){
  
  html::empty('Нет жалоб');
  
}

$data = db::get_string_all("SELECT * FROM `ABUSE` ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS);
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
  
  }elseif ($list['REASON'] == 0){
    
    $reason = 'Прочее';      
  
  }
  
  if ($list['OBJECT_TYPE'] == 'users') {
    
    ?>
    <div class='list-menu'>
    <b><?=lg('Жалобу подал')?>:</b> <?=user::login($list['USER_ID'], 0, 1)?><br />
    <b><?=lg('Тип нарушения')?>:</b> <?=$reason?><br />
    <b><?=lg('Сообщение')?>:</b> <?=text($list['MESSAGE'])?><br /><br />
    <b><?=lg('Аккаунт нарушителя')?>:</b><br /><?=user::login($list['OBJECT_ID'], 0, 1)?>
    <br /><br />
    <?php if (access('users_blocked', null) == true) : ?>
    <a ajax='no' href='/m/block/user/?id=<?=$comment['USER_ID']?>&<?=TOKEN_URL?>' class='button'><?=icons('ban', 17, 'fa-fw')?> <?=lg('Заблокировать нарушителя')?></a>
    <?php endif ?>
    <?php if (user('ACCESS') == 99) : ?>
    <a href='/admin/notifications/abuse/?delete=<?=$list['ID']?>&<?=TOKEN_URL?>' class='button2'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
    <?php endif ?>
    </div>
    <?
    
  }
  
  if ($list['OBJECT_TYPE'] == 'comments') {
    
    $comment = db::get_string("SELECT `USER_ID`,`MESSAGE` FROM `COMMENTS` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
    
    ?>
    <div class='list-menu'>
    <b><?=lg('Жалобу подал')?>:</b> <?=user::login($list['USER_ID'], 0, 1)?><br />
    <b><?=lg('Тип нарушения')?>:</b> <?=$reason?><br />
    <b><?=lg('Сообщение')?>:</b> <?=text($list['MESSAGE'])?><br /><br />
    <b><?=lg('Комментарий')?>:</b><br /><?=user::login($comment['USER_ID'], 0, 1)?><br /><?=crop_text(text($comment['MESSAGE']), 0, 1000)?>
    <br /><br />
    <?php if (access('users_blocked', null) == true) : ?>
    <a ajax='no' href='/m/block/user/?id=<?=$comment['USER_ID']?>&<?=TOKEN_URL?>' class='button'><?=icons('ban', 17, 'fa-fw')?> <?=lg('Заблокировать нарушителя')?></a>
    <?php endif ?>
    <?php if (user('ACCESS') == 99) : ?>
    <a href='/admin/notifications/abuse/?delete=<?=$list['ID']?>&<?=TOKEN_URL?>' class='button2'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
    <?php endif ?>
    </div>
    <?
      
  }
  
  if ($list['OBJECT_TYPE'] == 'forum') {
    
    $forum = db::get_string("SELECT `ID`,`USER_ID`,`MESSAGE`,`BAN` FROM `FORUM_THEM` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
    
    ?>
    <div class='list-menu'>
    <b><?=lg('Жалобу подал')?>:</b> <?=user::login($list['USER_ID'], 0, 1)?><br />
    <b><?=lg('Тип нарушения')?>:</b> <?=$reason?><br />
    <b><?=lg('Сообщение')?>:</b> <?=text($list['MESSAGE'])?><br />
    <b><?=lg('Тема форума')?>:</b><br /><?=user::login($forum['USER_ID'], 0, 1)?><br /><?=crop_text(text($forum['MESSAGE']), 0, 1000)?><br />
    <br /><br />
    <?php if (access('users_blocked', null) == true) : ?>
	<a ajax='no' href='/m/block/user/?id=<?=$forum['USER_ID']?>&<?=TOKEN_URL?>' class='button'><?=icons('ban', 17, 'fa-fw')?> <?=lg('Заблокировать автора темы')?></a>
    <?php endif ?>
    <?php if (access('forum', null) == true) : ?>
	<?php if ($forum['BAN'] == 0) : ?>
    <a ajax='no' href='/m/forum/show/?id=<?=$forum['ID']?>&them=ban_on&<?=TOKEN_URL?>' class='button'><?=icons('ban', 15, 'fa-fw')?> <?=lg('Заблокировать тему')?></a>
    <?php else : ?>       
    <a ajax='no' href='/m/forum/show/?id=<?=$forum['ID']?>&them=ban_off&<?=TOKEN_URL?>' class='button'><?=icons('ban', 15, 'fa-fw')?> <?=lg('Разблокировать тему')?></a>
    <?php endif ?>
    <?php endif ?>
    <?php if (user('ACCESS') == 99) : ?>
    <a href='/admin/notifications/abuse/?delete=<?=$list['ID']?>&<?=TOKEN_URL?>' class='button2'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
    <?php endif ?>
    </div>
    <?
      
  }
  
  if ($list['OBJECT_TYPE'] == 'clips') {
    
    $clip = db::get_string("SELECT `ID`,`USER_ID`,`NAME` FROM `CLIPS` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
    
    ?>
    <div class='list-menu'>
    <b><?=lg('Жалобу подал')?>:</b> <?=user::login($list['USER_ID'], 0, 1)?><br />
    <b><?=lg('Тип нарушения')?>:</b> <?=$reason?><br />
    <b><?=lg('Сообщение')?>:</b> <?=text($list['MESSAGE'])?><br />
    <b><?=lg('Клип добавил')?>:</b> <?=user::login($clip['USER_ID'], 0, 1)?><br />
    <b><?=lg('Название клипа')?>:</b> <?=tabs($clip['NAME'])?>
    <br /><br />
    <a ajax='no' href='/m/clips/comments/?id=<?=$clip['ID']?>' class='button'><?=icons('video-camera', 17, 'fa-fw')?> <?=lg('К клипу')?></a>
    <?php if (access('users_blocked', null) == true) : ?>
	<a ajax='no' href='/m/block/user/?id=<?=$clip['USER_ID']?>&<?=TOKEN_URL?>' class='button'><?=icons('ban', 17, 'fa-fw')?> <?=lg('Заблокировать автора')?></a>
    <?php endif ?>
    <?php if (access('clips', null) == true) : ?>
    <a href='/m/clips/delete/?id=<?=$clip['ID']?>&<?=TOKEN_URL?>' class='button2'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить клип')?></a>
    <?php endif ?>
    <?php if (user('ACCESS') == 99) : ?>
    <a href='/admin/notifications/abuse/?delete=<?=$list['ID']?>&<?=TOKEN_URL?>' class='button2'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
    <?php endif ?>
    </div>
    <?
      
  }
  
  if ($list['OBJECT_TYPE'] == 'music') {
    
    $file = db::get_string("SELECT * FROM `MUSIC` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
    
    ?>
    <div class='list-menu'>
    <b><?=lg('Жалобу подал')?>:</b> <?=user::login($list['USER_ID'], 0, 1)?><br />
    <b><?=lg('Тип нарушения')?>:</b> <?=$reason?><br />
    <b><?=lg('Сообщение')?>:</b> <?=text($list['MESSAGE'])?><br />
    <b><?=lg('Песню добавил')?>:</b> <?=user::login($file['USER_ID'], 0, 1)?><br />
    <b><?=lg('Название')?>:</b> <?=tabs($file['FACT_NAME'])?>
    <br /><br />
    <a ajax='no' href='/m/music/show/?id=<?=$file['ID']?>' class='button'><?=icons('mail-forward', 17, 'fa-fw')?> <?=lg('К песне')?></a>
    <?php if (access('users_blocked', null) == true) : ?>
	<a ajax='no' href='/m/block/user/?id=<?=$file['USER_ID']?>&<?=TOKEN_URL?>' class='button'><?=icons('ban', 17, 'fa-fw')?> <?=lg('Заблокировать автора')?></a>
    <?php endif ?>
    <?php if (user('ACCESS') == 99) : ?>
    <a href='/admin/notifications/abuse/?delete=<?=$list['ID']?>&<?=TOKEN_URL?>' class='button2'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
    <?php endif ?>
    </div>
    <?
      
  }

  if ($list['OBJECT_TYPE'] == 'photos') {
    
    $file = db::get_string("SELECT * FROM `PHOTOS` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
    
    ?>
    <div class='list-menu'>
    <b><?=lg('Жалобу подал')?>:</b> <?=user::login($list['USER_ID'], 0, 1)?><br />
    <b><?=lg('Тип нарушения')?>:</b> <?=$reason?><br />
    <b><?=lg('Сообщение')?>:</b> <?=text($list['MESSAGE'])?><br />
    <b><?=lg('Фото добавил')?>:</b> <?=user::login($file['USER_ID'], 0, 1)?><br />
    <b><?=lg('Название')?>:</b> <?=tabs($file['NAME'])?>
    <br /><br />
    <a ajax='no' href='/m/photos/show/?id=<?=$file['ID']?>' class='button'><?=icons('mail-forward', 17, 'fa-fw')?> <?=lg('К фото')?></a>
    <?php if (access('users_blocked', null) == true) : ?>
	<a ajax='no' href='/m/block/user/?id=<?=$file['USER_ID']?>&<?=TOKEN_URL?>' class='button'><?=icons('ban', 17, 'fa-fw')?> <?=lg('Заблокировать автора')?></a>
    <?php endif ?>
    <?php if (user('ACCESS') == 99) : ?>
    <a href='/admin/notifications/abuse/?delete=<?=$list['ID']?>&<?=TOKEN_URL?>' class='button2'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
    <?php endif ?>
    </div>
    <?
      
  }

  if ($list['OBJECT_TYPE'] == 'videos') {
    
    $file = db::get_string("SELECT * FROM `VIDEOS` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
    
    ?>
    <div class='list-menu'>
    <b><?=lg('Жалобу подал')?>:</b> <?=user::login($list['USER_ID'], 0, 1)?><br />
    <b><?=lg('Тип нарушения')?>:</b> <?=$reason?><br />
    <b><?=lg('Сообщение')?>:</b> <?=text($list['MESSAGE'])?><br />
    <b><?=lg('Видео добавил')?>:</b> <?=user::login($file['USER_ID'], 0, 1)?><br />
    <b><?=lg('Название')?>:</b> <?=tabs($file['NAME'])?>
    <br /><br />
    <a ajax='no' href='/m/videos/show/?id=<?=$file['ID']?>' class='button'><?=icons('mail-forward', 17, 'fa-fw')?> <?=lg('К видео')?></a>
    <?php if (access('users_blocked', null) == true) : ?>
	<a ajax='no' href='/m/block/user/?id=<?=$file['USER_ID']?>&<?=TOKEN_URL?>' class='button'><?=icons('ban', 17, 'fa-fw')?> <?=lg('Заблокировать автора')?></a>
    <?php endif ?>
    <?php if (user('ACCESS') == 99) : ?>
    <a href='/admin/notifications/abuse/?delete=<?=$list['ID']?>&<?=TOKEN_URL?>' class='button2'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
    <?php endif ?>
    </div>
    <?
      
  }

  if ($list['OBJECT_TYPE'] == 'files') {
    
    $file = db::get_string("SELECT * FROM `FILES` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
    
    ?>
    <div class='list-menu'>
    <b><?=lg('Жалобу подал')?>:</b> <?=user::login($list['USER_ID'], 0, 1)?><br />
    <b><?=lg('Тип нарушения')?>:</b> <?=$reason?><br />
    <b><?=lg('Сообщение')?>:</b> <?=text($list['MESSAGE'])?><br />
    <b><?=lg('Файл добавил')?>:</b> <?=user::login($file['USER_ID'], 0, 1)?><br />
    <b><?=lg('Название')?>:</b> <?=tabs($file['NAME'])?>
    <br /><br />
    <a ajax='no' href='/m/files/show/?id=<?=$file['ID']?>' class='button'><?=icons('mail-forward', 17, 'fa-fw')?> <?=lg('К файлу')?></a>
    <?php if (access('users_blocked', null) == true) : ?>
	<a ajax='no' href='/m/block/user/?id=<?=$file['USER_ID']?>&<?=TOKEN_URL?>' class='button'><?=icons('ban', 17, 'fa-fw')?> <?=lg('Заблокировать автора')?></a>
    <?php endif ?>
    <?php if (user('ACCESS') == 99) : ?>
    <a href='/admin/notifications/abuse/?delete=<?=$list['ID']?>&<?=TOKEN_URL?>' class='button2'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
    <?php endif ?>
    </div>
    <?
      
  }
  
}

get_page('/admin/notifications/abuse/?', $spage, $page, 'list-menu');

?></div><br /><?
  
back('/admin/desktop/');
acms_footer();
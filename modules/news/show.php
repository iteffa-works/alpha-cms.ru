<?php
$news = db::get_string("SELECT * FROM `NEWS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
html::title(lg('Новость - %s', tabs($news['NAME'])));
acms_header();

if (!isset($news['ID'])) {
  
  error('Неверная директива');
  redirect('/m/news/');

}

if (config('PRIVATE_NEWS') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

/*
---------
Просмотры
---------
*/

if (user('ID') > 0){
  
  if (db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `USER_ID` = ? AND `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [user('ID'), $news['ID'], 'news']) == 0){
    
    db::get_add("INSERT INTO `EYE` (`USER_ID`, `TIME`, `OBJECT_ID`, `TYPE`) VALUES (?, ?, ?, ?)", [user('ID'), TM, $news['ID'], 'news']);
  
  }else{
    
    db::get_set("UPDATE `EYE` SET `TIME` = ? WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [TM, $news['ID'], 'news']);
    
  }

}

if (access('news', null) == true){
  
  require_once (ROOT.'/modules/news/plugins/delete.php');
  
  ?>
  <div class='list'>
  <a href='/m/news/edit/?id=<?=$news['ID']?>&<?=TOKEN_URL?>' class='btn'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Редактировать')?></a>
  <a href='/m/news/show/?id=<?=$news['ID']?>&get=delete&<?=TOKEN_URL?>' class='btn'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Удалить')?></a>
  </div>
  <?
  
}

?>
<div class='list-body'>
<div class='list-menu'>
<b><?=tabs($news['NAME'])?></b>
<br />    
<?=attachments_files($news['ID'], 'news', 320)?>
<br />
<?=text($news['MESSAGE'])?>  
<?php
hooks::challenge('news_foot', 'news_foot');
hooks::run('news_foot');
likes_ajax($news['ID'], 'news', $news['USER_ID'], 1);
dislikes_ajax($news['ID'], 'news');
$action = '/m/news/show/?id='.$news['ID'];
?>
</div>
  
<div class='list-menu'>
<div class='user-info-mini'>
<div class='user-avatar-mini'>
<?=user::avatar($news['USER_ID'], 45)?> 
</div>
<div class='user-login-mini' style='top: 0px; left: 55px;'>
<?=lg('Добавил')?>: <?=user::login($news['USER_ID'], 0, 1)?><br />
<div class='time'><?=ftime($news['TIME'])?></div>    
</div>
</div>  
  
<div id='like'>
<?=likes_list($news['ID'], 'news', $action)?>
<div class='menu-sw-cont'>  
<a class='menu-sw-cont-left-33' href="/m/eye/?id=<?=$news['ID']?>&url=<?=base64_encode($action)?>&type=news&<?=TOKEN_URL?>"><?=icons('eye', 18, 'fa-fw')?> <?=db::get_column("SELECT COUNT(`ID`) FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ? LIMIT 1", [$news['ID'], 'news'])?></a><?=mlikes($news['ID'], $action, 'news', 'menu-sw-cont-left-33')?><?=mdislikes($news['ID'], $action, 'news', 'menu-sw-cont-left-33')?>
</div>
</div>
  
</div>  
</div>
  
<div class='list'>
<b><?=lg('Комментарии')?></b> <span class='count'><?=db::get_column("SELECT COUNT(`ID`) FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$news['ID'], 'news_comments'])?></span>
</div>
  
<?  
if (user('ID') == 0 || $news['PRIVATE_COMMENTS'] == 2 && $news['USER_ID'] != user('ID') || $news['PRIVATE_COMMENTS'] == 1 && access('news', null) == true){
  
  $comments_set = 'Извините, для вас комментирование недоступно';
  
}

comments($action, 'news', 1, 'message', $news['USER_ID'], $news['ID']);

back('/m/news/');
acms_footer();
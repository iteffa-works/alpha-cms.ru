<?php
  
$count = db::get_column("SELECT COUNT(*) FROM `BLOGS` WHERE `COMMUNITY` = ? LIMIT 16", [$comm['ID']]);

?>
<a href='/m/communities/blogs/?id=<?=$comm['ID']?>'>
<div class='profile_list'>  
<b><?=lg('Записи')?></b>
<span class='count'><?=$count?></span>  
<span style='float: right; position: relative; top: 5px;'><?=icons('chevron-right', 18)?></span>
</div>
</a>
<?
  
if (isset($par['ID'])) {
  
  ?>
  <div class='profile_list' style='margin-bottom: 10px;'>  
  <a href='/m/communities/add_blog/?id=<?=$comm['ID']?>' class='profile-edit2'><?=icons('pencil', 18, 'fa-fw')?> <?=lg('Написать в блог')?></a>   
  </div>
  <?
  
}

define('URL_BLOGS', '/public/'.$comm['URL'].'?');
$data = db::get_string_all("SELECT * FROM `BLOGS` WHERE `COMMUNITY` = ? ORDER BY `TIME` DESC LIMIT 15", [$comm['ID']]);
while ($list = $data->fetch()) {
  
  require (ROOT.'/modules/blogs/plugins/list.php');
  echo $blogs_list;

}

if ($count == 0) {
  
  html::empty(lg('У cообщества %s пока нет записей в блоге', tabs($comm['NAME'])), 'book');

}

if ($count >= 15) {
  
  ?>
  <div class='comments-ended'>
  <a href='/m/communities/blogs/?id=<?=$comm['ID']?>' class='button'><?=lg('Все записи')?></a>
  </div>
  <?  
  
}
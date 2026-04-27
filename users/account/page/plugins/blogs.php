<?php
if (config('PRIVATE_BLOGS') == 1) {
  
  $count = db::get_column("SELECT COUNT(*) FROM `BLOGS` WHERE `USER_ID` = ? AND `COMMUNITY` = ? LIMIT 16", [$account['ID'], 0]);
  
  ?>
  <a href='/m/blogs/users/?id=<?=$account['ID']?>'>
  <div class='profile_list'>  
  <b><?=lg('Записи %s', $account['LOGIN'])?></b>
  <span class='count'><?=$count?></span>  
  <span style='float: right; position: relative; top: 5px;'><?=icons('chevron-right', 18)?></span>
  </div>
  </a>
  <?
  
  if ($account['ID'] == user('ID')) {
    
    ?>
    <div class='profile_list' style='margin-bottom: 10px;'>  
    <a href='/m/blogs/add/' class='profile-edit2'><?=icons('pencil', 18, 'fa-fw')?> <?=lg('Написать в блог')?></a>   
    </div>
    <?
    
  }

  define('URL_BLOGS', '/id'.$account['ID'].'?');
  $data = db::get_string_all("SELECT * FROM `BLOGS` WHERE `USER_ID` = ? AND `COMMUNITY` = ? ORDER BY `TIME` DESC LIMIT 15", [$account['ID'], 0]);
  while ($list = $data->fetch()) {

    require (ROOT.'/modules/blogs/plugins/list.php');
    echo $blogs_list;
  
  }

  if ($count == 0) {
  
    html::empty(lg('У %s пока нет записей в блоге', $account['LOGIN']), 'book');
  
  }

  if ($count >= 15) {
  
    ?>
    <div class='comments-ended'>
    <a href='/m/blogs/users/?id=<?=$account['ID']?>' class='button'><?=lg('Все записи %s', $account['LOGIN'])?></a>
    </div>
    <?  
  
  }
  
}
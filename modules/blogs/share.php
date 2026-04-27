<?php   
$blog = db::get_string("SELECT * FROM `BLOGS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
html::title(lg('%s', tabs($blog['NAME'])));
acms_header();

if (config('PRIVATE_BLOGS') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

if (!isset($blog['ID'])) {
  
  error('Неверная директива');
  redirect('/m/blogs/');

}

if ($blog['COMMUNITY'] == 0) {
  
  $link = '/m/blogs/show/?id='.$blog['ID'];
  
}else{
  
  $link = '/m/communities/show_blog/?id='.$blog['ID'];
  
}

if (get('get') == 'go' && $blog['USER_ID'] != user('ID') && $blog['SHARE'] == 0) {
  
  if (db::get_column("SELECT COUNT(*) FROM `BLOGS` WHERE `SHARE` = ? AND `USER_ID` = ? LIMIT 1", [$blog['ID'], user('ID')]) > 0) {
    
    error('Вы уже поделились данной записью');
    redirect($link);
    
  }
  
  if (post('ok_share')){
    
    valid::create(array(

      'BLOGS_PRIVATE_COMMENTS' => ['private_comments', 'number', [0, 5], 'Приватность комментариев'],
      'BLOGS_MESSAGE' => ['message', 'text', [0, 20000], 'Содержание', 0]
    
    ));
    
    if (ERROR_LOG == 1){
      
      redirect('/m/blogs/share/?id='.$blog['ID'].'&get=go');
    
    }
    
    $ID = db::get_add("INSERT INTO `BLOGS` (`PRIVATE_COMMENTS`, `USER_ID`, `MESSAGE`, `TIME`, `SHARE`) VALUES (?, ?, ?, ?, ?)", [BLOGS_PRIVATE_COMMENTS, user('ID'), BLOGS_MESSAGE, TM, $blog['ID']]);
    
    /*
    --------------------
    Уведомления в журнал
    --------------------
    */
    
    if (db::get_column("SELECT COUNT(*) FROM `NOTIFICATIONS_SETTINGS` WHERE `USER_ID` = ? AND `SHARE` = ? LIMIT 1", [$blog['USER_ID'], 1]) == 1){ 
      
      db::get_add("INSERT INTO `NOTIFICATIONS` (`USER_ID`, `OBJECT_ID`, `OBJECT_ID_LIST`, `TIME`, `TYPE`) VALUES (?, ?, ?, ?, ?)", [$blog['USER_ID'], user('ID'), $blog['ID'], TM, 'blogs_share']);
    
    }
    
    success('Вы успешно поделились записью');
    redirect('/m/blogs/users/?id='.user('ID'));
    
  }
  
  ?>
  <div class='list-body'>
  <div class='list-menu'>
  <?=lg('Вы собираетесь поделиться записью')?>: <a href='<?=$link?>'><?=tabs($blog['NAME'])?></a>
  </div>
  <div class='list-menu'>
  <form method='post' class='ajax-form' action='/m/blogs/share/?id=<?=$blog['ID']?>&get=go'>
  <?=html::textarea(null, 'message', 'Введите содержимое', null, 'form-control-textarea', 9, 0)?>
  <br /><br />
  <?=html::select('private_comments', array(
  0 => ['Всем', 0], 
  1 => ['Мне и друзьям', 1], 
  2 => ['Только мне', 2]
  ), 'Комментирование', 'form-control-100-modify-select', 'comment');?>
  <?=html::button('button ajax-button', 'ok_share', 'mail-forward', 'Поделиться')?>
  <a class='button-o' href='<?=$link?>'><?=lg('Отмена')?></a>
  <form>
  </div>
  </div>
  <?
  
  back($link);
  acms_footer();
  
}

if (get('get') == 'edit' && $blog['USER_ID'] == user('ID') && $blog['SHARE'] > 0) {
  
  if (post('ok_edit_share')){
    
    valid::create(array(

      'BLOGS_PRIVATE_COMMENTS' => ['private_comments', 'number', [0, 5], 'Приватность комментариев'],
      'BLOGS_MESSAGE' => ['message', 'text', [0, 20000], 'Содержание', 0]
    
    ));
    
    if (ERROR_LOG == 1){
      
      redirect('/m/blogs/share/?id='.$blog['ID'].'&get=edit');
    
    }
    
    db::get_set("UPDATE `BLOGS` SET `PRIVATE_COMMENTS` = ?, `MESSAGE` = ? WHERE `ID` = ? LIMIT 1", [BLOGS_PRIVATE_COMMENTS, BLOGS_MESSAGE, $blog['ID']]);
    
    success('Изменения успешно приняты');
    redirect($link);
    
  }
  
  ?>
  <div class='list-body'>
  <div class='list-menu'>
  <form method='post' class='ajax-form' action='/m/blogs/share/?id=<?=$blog['ID']?>&get=edit'>
  <?=html::textarea(tabs($blog['MESSAGE']), 'message', 'Введите содержимое', null, 'form-control-textarea', 9, 0)?>
  <br /><br />
  <?=html::select('private_comments', array(
  0 => ['Всем', ($blog['PRIVATE_COMMENTS'] == 0 ? "selected" : null)], 
  1 => ['Мне и друзьям', ($blog['PRIVATE_COMMENTS'] == 1 ? "selected" : null)], 
  2 => ['Только мне', ($blog['PRIVATE_COMMENTS'] == 2 ? "selected" : null)]
  ), 'Комментирование', 'form-control-100-modify-select', 'comment')?>
  <?=html::button('button ajax-button', 'ok_edit_share', 'save', 'Сохранить')?>
  <a class='button-o' href='<?=$link?>'><?=lg('Отмена')?></a>
  <form>
  </div>
  </div>
  <?
  
  back($link);
  acms_footer();
  
}

?>
<div class='list'>
<b><?=lg('Кто поделился')?>:</b>
</div>
<?

$column = db::get_column("SELECT COUNT(*) FROM `BLOGS` WHERE `SHARE` = ?", [$blog['ID']]);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty('Пока пусто');

}else{
  
  ?><div class='list-body'><?
  
}

$data = db::get_string_all("SELECT * FROM `BLOGS` WHERE `SHARE` = ? ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$blog['ID']]);  
while ($list = $data->fetch()){
  
  require (ROOT.'/modules/users/plugins/list-mini.php');
  echo $list_mini;

}

if ($column > 0){ 
  
  ?></div><?
  
}

get_page('/m/blogs/share/?id='.$blog['ID'].'&', $spage, $page, 'list');

back($link);
acms_footer();
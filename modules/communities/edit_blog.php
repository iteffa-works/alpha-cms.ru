<?php  
$blog = db::get_string("SELECT * FROM `BLOGS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
$comm = db::get_string("SELECT `ID`,`URL` FROM `COMMUNITIES` WHERE `ID` = ? LIMIT 1", [$blog['COMMUNITY']]);
$par = db::get_string("SELECT `ID`,`ADMINISTRATION` FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? AND `ACT` = ? LIMIT 1", [$comm['ID'], user('ID'), 1]);
html::title(lg('Редактировать - %s', tabs($blog['NAME'])));
acms_header();
access('users');
communities::blocked($comm['ID']);

if (!isset($blog['ID']) || $blog['SHARE'] > 0) {
  
  error('Неверная директива');
  redirect('/m/communities/');

}

if (config('PRIVATE_COMMUNITIES') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

if (!isset($par['ID'])) {
  
  error('Вы не состоите в сообществе');
  redirect('/public/'.$comm['URL']);

}

if (!isset($comm['ID'])) {
  
  error('Неверная директива');
  redirect('/m/communities/');

}

if (isset($par['ID']) && $par['ADMINISTRATION'] == 2 || isset($par['ID']) && $par['ADMINISTRATION'] == 1 || access('communities', null) == true || $blog['USER_ID'] != user('ID')){
  
  if (post('ok_edit_blogs_comm')){
    
    valid::create(array(
      
      'BLOGS_NAME' => ['name', 'text', [2, 200], 'Название', 0],
      'BLOGS_PRIVATE_COMMENTS' => ['private_comments', 'number', [0, 5], 'Приватность комментариев'],
      'BLOGS_ID_CATEGORY' => ['id_cat', 'number', [0, 99999], 'Категория'],
      'BLOGS_MESSAGE' => ['message', 'text', [10, 20000], 'Содержание', 0]
    
    ));
    
    if ($blog['MESSAGE'] != BLOGS_MESSAGE && db::get_column("SELECT COUNT(*) FROM `BLOGS` WHERE `USER_ID` = ? AND `MESSAGE` = ? AND `COMMUNITY` = ? LIMIT 1", [user('ID'), BLOGS_MESSAGE, $comm['ID']]) == 1){
      
      error('Запись с таким содержимым уже существует в блоге сообщества');
      redirect('/m/communities/edit_blog/?id='.$blog['ID']);
    
    }
    
    if (ERROR_LOG == 1){
      
      redirect('/m/communities/edit_blog/?id='.$blog['ID']);
    
    }
    
    db::get_set("UPDATE `BLOGS` SET `ID_CATEGORY` = ?, `PRIVATE_COMMENTS` = ?, `NAME` = ?, `MESSAGE` = ? WHERE `ID` = ? LIMIT 1", [BLOGS_ID_CATEGORY, BLOGS_PRIVATE_COMMENTS, BLOGS_NAME, BLOGS_MESSAGE, $blog['ID']]);
    
    if (access('communities', null) == true){
      
      logs('Блоги сообществ - редактирование записи [url=/m/communities/show_blog/?id='.$blog['ID'].']'.$blog['NAME'].'[/url]', user('ID'));
    
    }
    
    success('Изменения успешно приняты');
    redirect('/m/communities/show_blog/?id='.$blog['ID']);
  
  }
  
  ?>    
  <div class='list'>
  <form method='post' class='ajax-form' action='/m/communities/edit_blog/?id=<?=$blog['ID']?>'>
  <?
  html::input('name', 'Название', null, null, tabs($blog['NAME']), 'form-control-100', 'text', null, 'book');
  define('ACTION', '/m/communities/edit_blog/?id='.$blog['ID']);
  define('TYPE', 'blogs');
  define('ID', $blog['ID']);
  html::textarea(tabs($blog['MESSAGE']), 'message', 'Введите содержимое', null, 'form-control-textarea', 9);  
  ?><br /><br /><?
  $array = array();
  $array[0] = ['Без категории', ($blog['ID_CATEGORY'] == 0 ? "selected" : null)];
  $data = db::get_string_all("SELECT * FROM `BLOGS_CATEGORIES` ORDER BY `ID` DESC");  
  while ($list = $data->fetch()){
    
    $array[$list['ID']] = [$list['NAME'], ($blog['ID_CATEGORY'] == $list['ID'] ? "selected" : null)];

  }
  html::select('id_cat', $array, 'Категория', 'form-control-100-modify-select', 'folder-open'); 
  html::select('private_comments', array(
    0 => ['Всем', ($blog['PRIVATE_COMMENTS'] == 0 ? "selected" : null)], 
    1 => ['Мне и друзьям', ($blog['PRIVATE_COMMENTS'] == 1 ? "selected" : null)], 
    2 => ['Только мне', ($blog['PRIVATE_COMMENTS'] == 2 ? "selected" : null)]
  ), 'Комментирование', 'form-control-100-modify-select', 'comment');
  html::button('button ajax-button', 'ok_edit_blogs_comm', 'save', 'Сохранить');  
  ?>
  <a class='button-o' href='/m/communities/show_blog/?id=<?=$blog['ID']?>'><?=lg('Отмена')?></a>
  <form>
  </div>
  <?
  
}else{
  
  error('Неверная директива');
  redirect('/public/'.$comm['URL']);
  
}

back('/m/communities/show_blog/?id='.$blog['ID']);
acms_footer();
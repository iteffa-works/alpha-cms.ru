<?php
html::title('Кто оценил');
acms_header();
get_check_valid();

$comments = db::get_string("SELECT `ID`,`OBJECT_TYPE` FROM `COMMENTS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
$action = tabs(base64_decode(get('action')));

if (isset($comments['ID']) && str($action) > 0){
  
  $column = db::get_column("SELECT COUNT(*) FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = 'like'", [$comments['ID'], $comments['OBJECT_TYPE']]);
  $spage = spage($column, PAGE_SETTINGS);
  $page = page($spage);
  $limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;
  
  if ($column == 0){ 
    
    html::empty('Пока пусто');
  
  }else{
    
    ?><div class='list-body'><?
    
  }
  
  $data = db::get_string_all("SELECT * FROM `LIKES` WHERE `OBJECT_ID` = ? AND `TYPE` = 'like' AND `OBJECT_TYPE` = ? ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$comments['ID'], $comments['OBJECT_TYPE']]);  
  while ($list = $data->fetch()){
    
    require (ROOT.'/modules/users/plugins/list-mini.php');
    echo $list_mini;
    
  }
  
  if ($column > 0){ 
    
    ?></div><?
    
  }
  
  get_page('/m/comments/likes/?id='.$comments['ID'].'&action='.base64_encode($action).'&'.TOKEN_URL.'&', $spage, $page, 'list');
  
}else{
  
  error('Неверная директива');
  redirect('/');
  
}

back($action, 'Назад');
acms_footer();
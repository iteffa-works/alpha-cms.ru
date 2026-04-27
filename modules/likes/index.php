<?php
html::title('Кто оценил');
acms_header();
get_check_valid();

$action = tabs(base64_decode(get('action')));
$id = intval(get('id'));
$type = tabs(esc(get('type')));

if ($id > 0 && str($action) > 0 && str($type) > 0){
  
  $column = db::get_column("SELECT COUNT(*) FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = 'like'", [$id, $type]);
  $spage = spage($column, PAGE_SETTINGS);
  $page = page($spage);
  $limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;
  
  if ($column == 0){ 
    
    html::empty('Пока пусто');
  
  }else{
    
    ?><div class='list-body'><?
    
  }
  
  $data = db::get_string_all("SELECT * FROM `LIKES` WHERE `OBJECT_ID` = ? AND `TYPE` = 'like' AND `OBJECT_TYPE` = ? ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$id, $type]);  
  while ($list = $data->fetch()){
    
    $dop = '<br /><span class="time">'.ftime($list['TIME']).'</span>';
    require (ROOT.'/modules/users/plugins/list-mini.php');
    echo $list_mini;
    
  }
  
  if ($column > 0){ 
    
    ?></div><?
    
  }
  
  get_page('/m/likes/?id='.$id.'&type='.$type.'&action='.base64_encode($action).'&'.TOKEN_URL.'&', $spage, $page, 'list');
  
}else{
  
  error('Неверная директива');
  redirect('/');
  
}

back($action, 'Назад');
acms_footer();
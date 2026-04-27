<?php
acms_header('Кто просмотрел');
get_check_valid();

$id = intval(get('id'));
$action = tabs(get('url'));
$type = tabs(esc(get('type')));

if (str($action) > 0 && str($type) > 0){
  
  $column = db::get_column("SELECT COUNT(*) FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ?", [$id, $type]);
  $spage = spage($column, PAGE_SETTINGS);
  $page = page($spage);
  $limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;
  
  if ($column == 0){ 
    
    html::empty('Пока пусто');
  
  }else{
    
    ?><div class='list-body'><?
    
  }
  
  $data = db::get_string_all("SELECT * FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ? ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$id, $type]);  
  while ($list = $data->fetch()){
    
    $dop = '<br /><span class="time">'.ftime($list['TIME']).'</span>';    
    require (ROOT.'/modules/users/plugins/list-mini.php');
    echo $list_mini;
    
  }
  
  if ($column > 0){ 
    
    ?></div><?
    
  }
  
  get_page('/m/eye/?id='.$id.'&type='.$type.'&url='.$action.'&'.TOKEN_URL.'&', $spage, $page, 'list');
  
}else{
  
  error('Неверная директива');
  redirect('/');
  
}

back(base64_decode($action), 'Назад');
acms_footer();
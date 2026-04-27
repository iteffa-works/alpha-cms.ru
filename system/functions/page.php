<?php
  
/*
-------------------------
Функция пагинации страниц
-------------------------
*/

//Выводит текущую страницу
function page($data = 1){ 
  
  $page = 1;
  
  if (get('page') == 'end'){ 
    
    $page = intval($data);
  
  }elseif (is_numeric(get('page'))){ 
    
    $page = intval(get('page'));
  
  }
  
  if ($page < 1){ $page = 1; }
  
  if ($page > $data){ $page = $data; }
  
  return $page;

}

// Определяет кол-во страниц
function spage($data = 0, $data_str = 10){ 
  
  if ($data != 0) {
    
    $v_pages = ceil($data / $data_str);
    
    return $v_pages;
  
  }else{ 
    
    return 1;
    
  }
  
}

// Вывод номеров страниц 
function get_page($link2 = '?', $data = 1, $page = 1, $class = 'list') {
  
  if ($data > 1) {
    
    if ($page < 1) {
      
      $page = 1;
    
    }
    
    $link = $link2;
    
    ?><div class='<?=$class?>'><?
      
    if (post('ok_list_page')){
      
      $list_page = intval(post('list-page'));
      $data_page = $link.'page='.$list_page;
      
      redirect($data_page);
    
    }
    
    ?>
    <form method='post' class='ajax-form' action='<?=$link?>'>
    <?  
      
    html::input('list-page', '№', null, null, null, 'form-control-page', null, null, 'sort-numeric-asc');  
    html::button('btn-page ajax-button', 'ok_list_page', null, 'Вперед');
    
    ?><br /><?
      
    if ($page != 1) {
      
      ?><a class='btn-page' href='<?=$link?>page=1' title='<?=lg('Страница')?> 1'><?=icons('angle-left', 20)?></a><?
      
    }
    
    if ($page != 1) {
      
      ?><a class='btn-page' href='<?=$link?>page=1' title='<?=lg('Страница')?> 1'>1</a><?
      
    }else{
      
      ?><span class='btn-page-o'>1</span><?
      
    }
    
    for ($ot = -2; $ot <= 2; $ot++){
      
      if ($page + $ot > 1 && $page + $ot < $data) {
        
        if ($ot != 0) {
          
          ?><a class='btn-page' href='<?=$link?>page=<?=($page + $ot)?>' title='<?=lg('Страница')?> <?=($page + $ot)?>'><?=($page + $ot)?></a><?
          
        }else{
          
          ?><span class='btn-page-o'><?=($page + $ot)?></span><?
          
        }
      
      }
    
    }
    
    if ($page != $data) {
      
      ?><a class='btn-page' href='<?=$link?>page=end' title='<?=lg('Страница')?> <?=$data?>'><?=$data?></a><?
      
    }elseif ($data > 1) {
      
      ?><span class='btn-page-o'><?=$data?></span><?
      
    }
    
    if ($page != $data) {
      
      ?><a class='btn-page' href='<?=$link?>page=end' title='<?=lg('Последняя страница')?>'><?=icons('angle-right', 20)?></a><?
      
    }
    
    ?></form></div><?
    
  }
    
}
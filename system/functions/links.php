<?php
  
function links_show($url) {
  
  if (strpos($url, HTTP_HOST) === false){
  
    return '<div class="links_show"><b>'.lg('Внешняя ссылка').'</b><br />'.url_filter($url).'</div>';
    
  }else{
    
    return $url;
    
  }
  
}
  
function links2($arr) {
  
  $set = (get('base') == 'panel' || strpos($arr[1], '/admin/') !== false ? 'ajax="no"' : null);
  
  if (!strpos($arr[1], HTTP_HOST) === false || !preg_match('#://#',$arr[1])){
    
    $link = url_filter($arr[1]);
    if ($link == false) { $link = tabs($arr[1]); }
    
    return '<a href="'.$link.'" '.$set.'>'.tabs($arr[2]).'</a>';
    
  }else{
    
    return '<a href="/url/?data='.base64_encode(html_entity_decode(url_filter($arr[1]))).'">'.tabs($arr[2]).'</a>';
    
  }
  
}

function links3($arr){
  
  $set = (get('base') == 'panel' || strpos($arr[1], '/admin/') !== false ? 'ajax="no"' : null); 
  
  if (strpos($arr[1], HTTP_HOST) !== false){
    
    return '<a href="'.url_filter($arr[1]).'" '.$set.'>'.links_show(url_filter($arr[1])).'</a>'; 
  
  }else{
    
    return '<a href="/url/?data='.base64_encode(html_entity_decode(url_filter($arr[1]))).'" '.$set.'>'.links_show(url_filter($arr[1])).'</a>';
  
  }
  
}
  
function links($msg, $param = 1) {
  
  if ($param == 1) {
    
    $pt = "/\[url=((?!javascript:|data:|document.cookie).+)\](.+)\[\/url\]/isU";
    
    if (preg_match($pt, $msg)) {
      
      $msg = preg_replace_callback($pt, 'links2', $msg);
    
    }else{
      
      if (strpos($msg, 'style') === false && strpos($msg, 'img') === false && strpos($msg, 'src') === false){
        
        $msg = preg_replace_callback('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', 'links3', $msg);
      
      }
    
    }
    
  }else{
    
    $msg = preg_replace('/\[url=(.+?)\]/i', '', $msg);
    $msg = preg_replace('/\[\/url]/i', '', $msg);
    
  }
  
  return $msg;

}
<?php
  
/*
-----------------
Добавление лайков
-----------------
*/

function likes_ajax($id, $type, $user_id, $notification = 1){
  
  if (get('like'.$id) && user('ID') > 0) {
    
    get_check_valid();
    
    if (db::get_column("SELECT COUNT(`ID`) FROM `LIKES` WHERE `USER_ID` = ? AND `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? LIMIT 1", [user('ID'), $id, $type, 'like']) == 0) {
      
      db::get_add("INSERT INTO `LIKES` (`USER_ID`, `TIME`, `OBJECT_ID`, `OBJECT_TYPE`, `TYPE`) VALUES (?, ?, ?, ?, ?)", [user('ID'), TM, $id, $type, 'like']);
      db::get_set("DELETE FROM `LIKES` WHERE `USER_ID` = ? AND `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? LIMIT 1", [user('ID'), $id, $type, 'dislike']);
      
      /*
      --------------------
      Уведомления в журнал
      --------------------
      */
      
      if ($notification == 1) {
        
        if ($user_id != user('ID')){
          
          if (db::get_column("SELECT COUNT(*) FROM `NOTIFICATIONS_SETTINGS` WHERE `USER_ID` = ? AND `LIKES` = ? LIMIT 1", [$user_id, 1]) == 1){ 
            
            db::get_add("INSERT INTO `NOTIFICATIONS` (`USER_ID`, `OBJECT_ID`, `OBJECT_ID_LIST`, `TIME`, `TYPE`) VALUES (?, ?, ?, ?, ?)", [$user_id, user('ID'), $id, TM, $type.'_like']);
          
          }
        
        }
      
      }
    
    }else{
      
      db::get_set("DELETE FROM `LIKES` WHERE `USER_ID` = ? AND `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? LIMIT 1", [user('ID'), $id, $type, 'like']);
    
    }
  
  }
  
}

/*
--------------------
Добавление дислайков
--------------------
*/

function dislikes_ajax($id, $type){
  
  if (get('dislike'.$id) && user('ID') > 0) {
    
    get_check_valid();
    
    if (db::get_column("SELECT COUNT(`ID`) FROM `LIKES` WHERE `USER_ID` = ? AND `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? LIMIT 1", [user('ID'), $id, $type, 'dislike']) == 0) {
      
      db::get_add("INSERT INTO `LIKES` (`USER_ID`, `TIME`, `OBJECT_ID`, `OBJECT_TYPE`, `TYPE`) VALUES (?, ?, ?, ?, ?)", [user('ID'), TM, $id, $type, 'dislike']);
      db::get_set("DELETE FROM `LIKES` WHERE `USER_ID` = ? AND `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? LIMIT 1", [user('ID'), $id, $type, 'like']);
    
    }else{
      
      db::get_set("DELETE FROM `LIKES` WHERE `USER_ID` = ? AND `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? LIMIT 1", [user('ID'), $id, $type, 'dislike']);
    
    }
  
  }
  
} 

/*
------------
Вывод лайков
------------
*/
  
function mlikes($id, $url, $type, $class, $element = 'like') {
  
  $likes = db::get_column("SELECT COUNT(`ID`) FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? LIMIT 1", [$id, $type, 'like']);
  $ulikes = db::get_column("SELECT COUNT(`ID`) FROM `LIKES` WHERE `OBJECT_ID` = ? AND `USER_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? LIMIT 1", [$id, user('ID'), $type, 'like']);
  
  if ($ulikes > 0){
    
    $cl = "color: #3DC999";
  
  }else{
    
    $cl = null;
  
  }
  
  return '<span style="'.$cl.'" class="'.$class.'" onclick="request(\''.url_request_get($url).'like'.$id.'='.$id.'&'.TOKEN_URL.'\', \'#'.$element.'\')">'.icons('thumbs-up', 18, 'fa-fw').' '.$likes.'</span>';
  
}

/*
---------------
Вывод дислайков
---------------
*/

function mdislikes($id, $url, $type, $class, $element = 'like') {
  
  $dislikes = db::get_column("SELECT COUNT(`ID`) FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? LIMIT 1", [$id, $type, 'dislike']);
  $udislikes = db::get_column("SELECT COUNT(`ID`) FROM `LIKES` WHERE `OBJECT_ID` = ? AND `USER_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? LIMIT 1", [$id, user('ID'), $type, 'dislike']);
  
  if ($udislikes > 0){
    
    $cd = "color: #FB6083";
  
  }else{
    
    $cd = null;
  
  }
  
  return '<span style="'.$cd.'" class="'.$class.'" onclick="request(\''.url_request_get($url).'dislike'.$id.'='.$id.'&'.TOKEN_URL.'\', \'#'.$element.'\')">'.icons('thumbs-down', 18, 'fa-fw').' '.$dislikes.'</span>';
  
}

/*
---------------------
Вывод тех кто лайкнул
---------------------
*/

function likes_list($id, $type, $url) {
  
  if (db::get_column("SELECT COUNT(`ID`) FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? LIMIT 1", [$id, $type, 'like']) > 0){
      
    $s = 0;
    $avatar = null;
    $end = null;
    $data = db::get_string_all("SELECT `USER_ID` FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? AND `TYPE` = ? ORDER BY `TIME` DESC", [$id, $type, 'like']);
    while ($list = $data->fetch()){
      
      $s++;
      if ($s >= 1 && $s <= 3){
        
        $avatar .= " ".user::avatar($list['USER_ID'], 25)." ";
      
      }
    
    }
    
    if ($s > 3){
      
      $count = $s - 3; 
      
      $end = "<span style='position: relative; bottom: 9px; margin-left: 5px;'>".lg('и ещё')." <span class='count'>".$count."</span> ".lg('чел.')."</span>";
    
    }
      
    return "<br /><a href='/m/likes/?id=".$id."&action=".base64_encode($url)."&type=".$type."&".TOKEN_URL."'><span style='position: relative; bottom: 9px; margin-right: 5px;'>".lg('Понравилось')."</span>".$avatar.$end."</a>";
    
  }
  
}
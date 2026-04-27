<?php
  
/*
--------------------------------------------
Класс для работы с пользовательскими данными
--------------------------------------------
*/
  
class communities {
  
  /*
  -------------
  Вывод аватара
  -------------
  */
  
  public static function avatar($ID, $size = 80, $border = 0){
    
    //$ID - идентификатор
    //$size - размер аватара

    $comm = db::get_string("SELECT `AVATAR` FROM `COMMUNITIES` WHERE `ID` = ? LIMIT 1", [$ID]);    
    
    if ($border == 1){
      
      $border = 'border: 7px white solid;';
    
    }else{
      
      $border = null;
    
    }
    
    if (db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_BAN` WHERE `COMMUNITY_ID` = ? AND `BAN` = ? LIMIT 1", [$ID, 1]) == 0){
      
      if (is_file(ROOT.'/files/upload/communities/avatar/100x100/'.$comm['AVATAR'].'.jpg')){
        
        $avatar = "<img src='/files/upload/communities/avatar/100x100/".$comm['AVATAR'].".jpg' class='avatar' style='height: ".$size."px; width: ".$size."px; ".$border."'>";
      
      }else{
        
        $avatar = "<img src='/files/upload/communities/avatar/no_photo.jpg' class='avatar' style='height: ".$size."px; width: ".$size."px; ".$border."'>";
      
      }
      
    }else{
      
      $avatar = "<img src='/files/upload/communities/avatar/no_photo.jpg' class='avatar' style='height: ".$size."px; width: ".$size."px; ".$border."'>";
      
    }
    
    return $avatar;
  
  }
  
  /*
  ------------------------
  Вывод имени без настроек
  ------------------------
  */
  
  public static function name($ID){

    $comm = db::get_string("SELECT `NAME` FROM `COMMUNITIES` WHERE `ID` = ? LIMIT 1", [$ID]);
    
    if (db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_BAN` WHERE `COMMUNITY_ID` = ? AND `BAN` = ? LIMIT 1", [$ID, 1]) == 0){
      
      return tabs($comm['NAME']);
      
    }else{
      
      return 'Community blocked';
      
    }
  
  }
  
  /*
  --------------
  Вывод описания
  --------------
  */
  
  public static function mess($ID){

    $comm = db::get_string("SELECT `MESSAGE` FROM `COMMUNITIES` WHERE `ID` = ? LIMIT 1", [$ID]);
    
    if (db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_BAN` WHERE `COMMUNITY_ID` = ? AND `BAN` = ? LIMIT 1", [$ID, 1]) == 0){
      
      return tabs($comm['MESSAGE']);
      
    }else{
      
      return '...';
      
    }
  
  }
  
  /*
  ------------
  Вывод адреса
  ------------
  */
  
  public static function url($ID){

    $comm = db::get_string("SELECT `URL` FROM `COMMUNITIES` WHERE `ID` = ? LIMIT 1", [$ID]);

    return '/public/'.tabs($comm['URL']);
  
  }
  
  /*
  -----------------------------------
  Ограничение доступа если сообщество
  заблокировано
  -----------------------------------
  */
  
  public static function blocked($ID){
    
    if (db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_BAN` WHERE `COMMUNITY_ID` = ? AND (`BAN` = ? OR `BAN_TIME` > ?) LIMIT 1", [$ID, 1, TM]) > 0){
      
      redirect(communities::url($ID));
      
    }
  
  }
  
}
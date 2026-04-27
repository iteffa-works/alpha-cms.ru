<?php
  
/*
--------------------------------------------
Класс для работы с пользовательскими данными
--------------------------------------------
*/
  
class user {
  
  public static function avatar($ID, $size = 80, $online = 0, $border = 0){
    
    //$ID - идентификатор пользователя
    //$size - размер аватара
    //$online - вывод значка онлайн
    //$border - белая обводка аватара для отступа от границ (0 - выкл, 1 - вкл)

    $account = db::get_string("SELECT `DATE_VISIT`,`VERSION` FROM `USERS` WHERE `ID` = ? LIMIT 1", [$ID]);
    $account_set = db::get_string("SELECT `AVATAR`,`AVATAR_PHONE` FROM `USERS_SETTINGS` WHERE `USER_ID` = ? LIMIT 1", [$ID]); 
    $photo = db::get_string("SELECT `SHIF`,`ID_DIR` FROM `PHOTOS` WHERE `ID` = ? LIMIT 1", [$account_set['AVATAR']]);

    $on = null;
    $border = ($border == 1 ? 'border: 7px #fff solid;' : null);

    if ($online == 1 && $account['DATE_VISIT'] > (TM - config('ONLINE_TIME_USERS'))){
      
      $on = "<span class='avatar-online-".($account['VERSION'] == 'touch' ? 'touch' : 'web')."' style='z-index: 2'><span><i class='fa fa-".($account['VERSION'] == 'touch' ? 'mobile' : 'desktop')."'></i></span></span>";
    
    }  
    
    $size_text = $size / 2 - 2;
    
    //Пользователь удален или его не существует
    if (!isset($account['VERSION'])) {
      
      return "<span style='display: inline-block; position: relative'><div class='avatar-o' style='".$border." background-color: ".$account_set['AVATAR_PHONE']."; font-size: ".$size_text."px; width: ".$size."px; height: ".$size."px;'><span><i class='fa fa-times'></i></span></div></span>";
    
    }
    
    //Пользователь заблокирован
    if (db::get_column("SELECT COUNT(*) FROM `BAN_USER` WHERE `USER_ID` = ? AND `BAN` = ? LIMIT 1", [$ID, 1]) > 0) {
      
      return "<span style='display: inline-block; position: relative'><div class='avatar-o' style='".$border." background-color: ".$account_set['AVATAR_PHONE']."; font-size: ".$size_text."px; width: ".$size."px; height: ".$size."px;'><span><i class='fa fa-ban'></i></span></div>".$on."</span>";
    
    }
    
    //Пользователь установил аватар, выводим фото
    if (is_file(ROOT.'/files/upload/photos/150x150/'.$photo['SHIF'].'.jpg')) {
      
      return "<span style='display: inline-block; position: relative'><img class='avatar' style='".$border." width: ".$size."px; height: ".$size."px; position: relative; z-index: 1' src='/files/upload/photos/150x150/".$photo['SHIF'].".jpg'>".$on."</span>";
    
    }
    
    //Пользователь не установил аватар, выводим базовый аватар
    return "<span style='display: inline-block; position: relative'><div class='avatar-o' style='".$border." background-color: ".$account_set['AVATAR_PHONE']."; font-size: ".$size_text."px; width: ".$size."px; height: ".$size."px'><span>".mb_substr(user::login_mini($ID), 0, 1, 'utf-8')."</span></div>".$on."</span>";
  
  }
  
  /*
  -------------------
  Вывод иконки онлайн
  -------------------
  */
  
  public static function online($ID){
    
    if (db::get_column("SELECT COUNT(*) FROM `USERS` WHERE `ID` = ? AND `DATE_VISIT` > ? AND `VERSION` = 'touch' LIMIT 1", [$ID, (TM-config('ONLINE_TIME_USERS'))]) > 0){
      
      return "<i class='fa fa-mobile fa-fw' style='position: relative; top: 1px; color: green'></i>";
    
    }elseif (db::get_column("SELECT COUNT(*) FROM `USERS` WHERE `ID` = ? AND `DATE_VISIT` > ? AND `VERSION` = 'web' LIMIT 1", [$ID, (TM-config('ONLINE_TIME_USERS'))]) > 0){
      
      return "<i class='fa fa-laptop fa-fw' style='position: relative; top: 1px; color: green'></i>";
    
    }
  
  }
  
  /*
  -------------------------------
  Вывод логина со всем содержимым
  -------------------------------
  */
  
  public static function login($ID, $avatar = 0, $link = 0, $online = 0, $color = 'black') {
    
    $account_set = db::get_string("SELECT * FROM `USERS_SETTINGS` WHERE `USER_ID` = ? LIMIT 1", [$ID]); 
    $account = db::get_string("SELECT * FROM `USERS` WHERE `ID` = ? LIMIT 1", [$ID]);
    
    $cban = ($color == "white" ? "<font color='#FFDEDB'>" : "<font color='#939699'>");   
    $login = (isset($account['ID']) ? tabs($account['LOGIN']) : 'Delete');
    
    if (db::get_column("SELECT COUNT(*) FROM `BAN_USER` WHERE `USER_ID` = ? AND `BAN_TIME` > ? AND `BAN` = ? LIMIT 1", [$ID, TM, 0]) > 0){
      
      $login = $cban.$login."</font> ".icons('ban', 15, 'fa-fw');
      
    }elseif (db::get_column("SELECT COUNT(*) FROM `BAN_USER` WHERE `USER_ID` = ? AND `BAN` = ? LIMIT 1", [$ID, 1]) > 0){
      
      $login = $cban."Blocked</font> ".icons('ban', 15, 'fa-fw');
    
    }
    
    $avatar = ($avatar > 0 ? user::avatar($ID, 25)." " : null);
    $online = ($online > 0 ? " ".user::online($ID)." " : null);
    $link1 = null;
    $link2 = null;
    
    if ($link > 0){
      
      $ajn = (get('base') == 'panel' && user('ID') > 0 ? 'ajax="no"' : null);      
      $link1 = "<a href='/id".$ID."' ".$ajn." style='color: ".$color."'>"; 
      $link2 = "</a>";
    
    }
    
    $upd1 = null; //вносимые изменения перед логином
    $upd2 = null; //вносимые изменения после логина
    
    $result = scandir(ROOT.'/system/PHP-classes/users_login/', SCANDIR_SORT_ASCENDING);
    
    for ($i = 0; $i < count($result); $i++){
      
      if (preg_match('#\.php$#i',$result[$i])){       
        
        require (ROOT.'/system/PHP-classes/users_login/'.$result[$i]);
      
      }
    
    }

    return $link1.$avatar.$upd1."<b>".$login."</b>".$upd2.$online.$link2;
  
  }
  
  /*
  -------------------------
  Вывод логина без настроек
  -------------------------
  */
  
  public static function login_mini($ID){

    $login = db::get_column("SELECT `LOGIN` FROM `USERS` WHERE `ID` = ? LIMIT 1", [$ID]);
    $ban = db::get_column("SELECT COUNT(*) FROM `BAN_USER` WHERE `USER_ID` = ? AND `BAN` = ? LIMIT 1", [$ID, 1]);

    return ($login != null ? ($ban != 1 ? tabs($login) : 'Blocked') : 'Delete');
  
  }
  
  /*
  ---------------------------
  Адрес аккаунта пользователя
  ---------------------------
  */
  
  public static function url($ID) {

    return '/id'.$ID;
  
  }
  
}
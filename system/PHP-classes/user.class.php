<?php
  
/*
--------------------------------------------
Класс для работы с пользовательскими данными
--------------------------------------------
*/
  
class user {
  
  public static function avatar($ID, $size = 80, $online = 0, $border = 0, $color_avatar = 1, $color_avatar_gr1 = null, $color_avatar_gr2 = null){
    
    //$ID - идентификатор пользователя
    //$size - размер аватара
    //$online - вывод значка онлайн
    //$border - белая обводка аватара для отступа от границ (0 - выкл, 1 - вкл)
    //$color_avatar - цветная рамка для аватара (0 - выкл, 1 - вкл)
    //$color_avatar_gr1 - градиент 1
    //$color_avatar_gr2 - градиент 2

    $account = db::get_string("SELECT * FROM `USERS` WHERE `ID` = ? LIMIT 1", [$ID]);
    $account_set = db::get_string("SELECT * FROM `USERS_SETTINGS` WHERE `USER_ID` = ? LIMIT 1", [$ID]); 
    $photo = db::get_string("SELECT `SHIF`,`ID_DIR`,`EXT` FROM `PHOTOS` WHERE `ID` = ? LIMIT 1", [$account_set['AVATAR']]);

    $on = null;
    $avatar_color_size = $size;
    $avatar_color_on = null;
    $avatar_color_css = null;
    $avatar_color_border = null;
    $border_max = ($border == 1 ? 7 : 0);
    $border = ($border == 1 ? 'border: 7px #fff solid;' : null);
    $christmas_hats = null;
    
    /*
    -------------------------------------
    Услуга "Новогодняя шапка для аватара"
    -------------------------------------
    */
    
    if (isset($account_set['CHRISTMAS_HATS']) && isset($account_set['CHRISTMAS_HATS_TIME'])) {
      
      if ($online == 0 && $size == 85 || $online != 0 && $size == 45 || $online != 0 && $size == 55) {
        
        $christmas_hats = christmas_hats_avatar($size, $account_set['CHRISTMAS_HATS'], $account_set['CHRISTMAS_HATS_TIME'], $account_set['CHRISTMAS_HATS_OFF']);
        
      }
      
    }
    
    /*
    ----------------------------------
    Услуга "Цветная рамка для аватара"
    ----------------------------------
    */
    
    if (isset($account['AVATAR_COLOR1']) && isset($account['AVATAR_COLOR2']) && isset($account['AVATAR_COLOR_SET']) && isset($account['AVATAR_COLOR_ANIMATE'])) {
      
      if (!is_panel() && $size != 100 && $color_avatar == 1 && str($account['AVATAR_COLOR1']) > 0 && str($account['AVATAR_COLOR2']) > 0 && $account['AVATAR_COLOR_SET'] == 1){
        
        if ($size == 55 || $size == 45 || $size >= 85) {
          
          $avatar_color_css = 'class="avatar_color"';
          $border = null;
          $avatar_color_size = ($size - ($border_max != 7 ? 8 : 2));
          $avatar_color_on = 'bottom: -4px';
          $avatar_color_border = "background: linear-gradient(90deg, #".tabs($account['AVATAR_COLOR1']).", #".tabs($account['AVATAR_COLOR2']).", #".tabs($account['AVATAR_COLOR1']).", #".tabs($account['AVATAR_COLOR2'])."); background-size: 400% 400%; bottom: ".$border_max."px; width: ".($avatar_color_size + 8)."px; height: ".($avatar_color_size + 8)."px; ".($account['AVATAR_COLOR_ANIMATE'] == 1 ? "animation: avatar_color_gradient 5s ease infinite;" : null);
        
        }
      
      }
      
    }
    
    if ($color_avatar_gr1 != null && $color_avatar_gr2 != null) {
      
      $avatar_color_css = 'class="avatar_color"';
      $border = null;
      $avatar_color_size = ($size - ($border_max != 7 ? 8 : 2));
      $avatar_color_on = 'bottom: -4px';
      $avatar_color_border = "background: linear-gradient(90deg, #".tabs($color_avatar_gr1).", #".tabs($color_avatar_gr2).", #".tabs($color_avatar_gr1).", #".tabs($color_avatar_gr2)."); background-size: 400% 400%; bottom: ".$border_max."px; width: ".($avatar_color_size + 8)."px; height: ".($avatar_color_size + 8)."px; animation: avatar_color_gradient 5s ease infinite;";
      
    }

    if ($online == 1 && $account['DATE_VISIT'] > (TM - config('ONLINE_TIME_USERS'))){
      
      $on = "<span class='avatar-online-".($account['VERSION'] == 'touch' ? 'touch' : 'web')."' style='".$avatar_color_on."; z-index: 2'><span><i class='fa fa-".($account['VERSION'] == 'touch' ? 'mobile' : 'desktop')."'></i></span></span>";
    
    }  
    
    $size_text = $avatar_color_size / 2 - 2;
    
    //Пользователь удален или его не существует
    if (!isset($account['VERSION'])) {
      
      return "<span style='display: inline-block; position: relative; ".$avatar_color_border."' ".$avatar_color_css."><div class='avatar-o' style='".$border." background-color: ".$account_set['AVATAR_PHONE']."; font-size: ".$size_text."px; width: ".$avatar_color_size."px; height: ".$avatar_color_size."px;'><span><i class='fa fa-times'></i></span></div></span>";
    
    }
    
    //Пользователь заблокирован
    if (db::get_column("SELECT COUNT(*) FROM `BAN_USER` WHERE `USER_ID` = ? AND `BAN` = ? LIMIT 1", [$ID, 1]) > 0) {
      
      return "<span style='display: inline-block; position: relative; ".$avatar_color_border."' ".$avatar_color_css."><div class='avatar-o' style='".$border." background-color: ".$account_set['AVATAR_PHONE']."; font-size: ".$size_text."px; width: ".$avatar_color_size."px; height: ".$avatar_color_size."px;'><span><i class='fa fa-ban'></i></span></div>".$on."</span>";
    
    }
    
    //Пользователь купил услугу и установил GIF аватар
    if (isset($account['GIF_AVATAR_TIME'])) {
      
      if (is_file(ROOT.'/files/upload/photos/150x150/'.$photo['SHIF'].'.jpg') && $photo['EXT'] == 'gif' && $account['GIF_AVATAR_TIME'] > TM) {
        
        return "<span style='display: inline-block; position: relative; ".$avatar_color_border."' ".$avatar_color_css.">".$christmas_hats."<img class='avatar' style='".$border." width: ".$avatar_color_size."px; height: ".$avatar_color_size."px; position: relative; z-index: 1' src='/files/upload/photos/source/".$photo['SHIF'].".gif'>".$on."</span>";
      
      }
      
    }
    
    //Пользователь установил аватар, выводим фото
    if (is_file(ROOT.'/files/upload/photos/150x150/'.$photo['SHIF'].'.jpg')) {
      
      return "<span style='display: inline-block; position: relative; ".$avatar_color_border."' ".$avatar_color_css.">".$christmas_hats."<img class='avatar' style='".$border." width: ".$avatar_color_size."px; height: ".$avatar_color_size."px; position: relative; z-index: 1' src='/files/upload/photos/150x150/".$photo['SHIF'].".jpg'>".$on."</span>";
    
    }
    
    //Пользователь не установил аватар, выводим базовый аватар
    return "<span style='display: inline-block; position: relative; ".$avatar_color_border."' ".$avatar_color_css."'>".$christmas_hats."<div class='avatar-o' style='".$border." background-color: ".$account_set['AVATAR_PHONE']."; font-size: ".$size_text."px; width: ".$avatar_color_size."px; height: ".$avatar_color_size."px'><span>".mb_substr(user::login_mini($ID), 0, 1, 'utf-8')."</span></div>".$on."</span>";
  
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
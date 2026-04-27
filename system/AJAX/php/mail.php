<?php
  
FOREACH (ARRAY('config', 'connect', 'users') AS $connect) {
  
  INCLUDE_ONCE ("../../../system/connections/global/$connect.php"); 
  
}

ACCESS('users');

IF (AJAX() == TRUE){
  
  $mail = DB::GET_STRING("SELECT * FROM `MAIL` WHERE `USER_ID` = '".INTVAL(GET('id'))."' AND `MY_ID` = '".$user['ID']."' LIMIT 1");
  $account = DB::GET_STRING("SELECT * FROM `USERS` WHERE `ID` = '".$mail['USER_ID']."' LIMIT 1");
  $msg = DB::GET_STRING("SELECT * FROM `MAIL_MESSAGE` WHERE (`USER_ID` = '".$user['ID']."' OR `MY_ID` = '".$user['ID']."') AND (`USER_ID` = '".$account['ID']."' OR `MY_ID` = '".$account['ID']."') AND `ID` = '".INTVAL(GET('id_msg'))."' LIMIT 1");
  
  IF (ISSET($mail['ID']) && ISSET($account['ID']) && ISSET($msg['ID'])){
    
    ECHO "<div class='list-menu'>";
    ECHO "<a href='/users/profile/?path=mail&section=message&id=".$account['ID']."&reply=".$msg['ID']."&page=".INTVAL(GET('page'))."'>".LG('Ответить')."</a>";
    ECHO "</div>";
    
    ECHO "<div class='list-menu'>";
    ECHO "<a href='/users/profile/?path=mail&section=message&id=".$account['ID']."&delete_my=".$msg['ID']."&page=".INTVAL(GET('page'))."'>".LG('Удалить у меня')."</a>";
    ECHO "</div>";

    ECHO "<div class='list-menu'>";
    ECHO "<a href='/users/profile/?path=mail&section=message&id=".$account['ID']."&delete_all=".$msg['ID']."&page=".INTVAL(GET('page'))."'>".LG('Удалить у всех')."</a>";
    ECHO "</div>";
    
  }ELSE{
    
    ECHO LG('Ошибка');
    
  }
  
}ELSE{
  
  ECHO LG('Не удалось установить соединение');

}
  
?>
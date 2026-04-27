<?php
  
if (isset($dop)){
  
  $dop = $dop;
  
}else{
  
  $dop = null;  
  
}

if (isset($dop2)){
  
  $dop2 = $dop2;
  
}else{
  
  $dop2 = null;  
  
}

$list_mini = "<div class='list-menu'><div class='user-info-mini'><div class='user-avatar-mini'><a href='/id".$list['USER_ID']."'>".user::avatar($list['USER_ID'], 45, 1)."</a></div><div class='user-login-mini'>".user::login($list['USER_ID'], 0, 1)." ".$dop."</div></div>".$dop2."</div>";
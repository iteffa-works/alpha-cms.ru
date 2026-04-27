<br /><center>

<?php
  
if (url_request_validate('/account/mail/messages') == false) {  
  
  if (str(REQUEST_URI) <= 3) {
    
    $rtype = 1;
  
  }else{
    
    $rtype = 2;
  
  }
  
  $data = db::get_string_all("SELECT `CODE` FROM `BANNERS` WHERE `ACT` = ? AND `TYPE` = ? ORDER BY `ID` DESC", [1, $rtype]);
  while ($list = $data->fetch()){ 
    
    echo $list['CODE'];
  
  }
  
}
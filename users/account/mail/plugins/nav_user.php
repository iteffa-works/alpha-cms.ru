<a href='/id<?=ACCOUNT_ID?>' class='mail-messages-nav'>
<div id='messages' action='/account/mail/messages/?id=<?=ACCOUNT_ID?>&<?=TOKEN_URL?>'>
  
<span class='mail-messages-nav-avatar'>
<?=user::avatar(ACCOUNT_ID, 38)?>
</span>  
  
<span class='mail-messages-nav-login'>
<?=user::login_mini(ACCOUNT_ID)?> 
</span>
  
<span class='mail-messages-nav-online'>

<?php  
  
if (user('MESSAGES_PRINTS') == ACCOUNT_ID) {
  
  echo lg('печатает')." ...";
  db::get_set("UPDATE `USERS` SET `MESSAGES_PRINTS` = '0' WHERE `ID` = ? LIMIT 1", [user('ID')]);
  
}else{
  
  if (ACCOUNT_DATE_VISIT < TM-config('ONLINE_TIME_USERS')){
    
    if (ACCOUNT_SEX == 2){
      
      $time_sex = lg('была в сети')." ";    
    
    }ELSE{
      
      $time_sex = lg('был в сети')." ";
    
    }
    
    $time_us = $time_sex.stime(ACCOUNT_DATE_VISIT);
    
    if (str($time_us) > 32){
      
      $time_us = '<marquee scrolldelay="800" behavior="alternate">'.$time_us.'</marquee>';
    
    }
    
    echo $time_us;
  
  }else{
    
    echo lg('в сети');
  
  }
  
}
?> 

</span>
</div>  
</a>
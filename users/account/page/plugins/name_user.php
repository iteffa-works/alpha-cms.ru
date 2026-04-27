<?php
  
if ($account['DATE_VISIT'] < (TM-config('ONLINE_TIME_USERS'))){
  
  if ($account['SEX'] == 2){
    
    $online = "<span style='text-align: left; padding-left: 30px; width: 225px'>".lg('Была в сети')." ".stime($account['DATE_VISIT'])."</span>";
    
  }else{
    
    $online = "<span style='text-align: left; padding-left: 30px; width: 225px'>".lg('Был в сети')." ".stime($account['DATE_VISIT'])."</span>";
  
  }
  
}else{
  
  $online = "<span style='color: #33CE99'>".lg('Сейчас в сети')."</span>";
  
}

?>

<div class='name_user'>
<div style='text-align: left; padding-left: 43px; width: 255px'><?=user::login($account['ID'])?></div> 
<?=$online?>
</div>
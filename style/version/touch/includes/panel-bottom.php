<div class='panel-bottom-optimize'> 
  
<?php if (!user('ID')){ ?>
  
<a href='/login/' class='panel-bottom-gs' id='aut'>
<span><?=icons('sign-in', 28)?></span>
<b><?=lg('Войти')?></b>
</a>
  
<a href='/registration/' class='panel-bottom-aut'>
<span><?=icons('user-plus', 20)?></span>
<b><?=lg('Присоединиться')?></b> 
</a>
  
<a href='/password/' class='panel-bottom-gs' id='password'>
<span><?=icons('unlock', 28)?></span>
<b><?=lg('Пароль')?></b>
</a>
  
<?php 
                       
}else{ 
  
  $mess = db::get_column("SELECT COUNT(*) FROM `MAIL_MESSAGE` WHERE `USER_ID` = ? AND `USER` = ? AND `READ` = '0'", [user('ID'), user('ID')]);
  
  if ($mess > 99){
    
    $c_mess = "<small class='count-mess'>99+</small>";
  
  }elseif ($mess > 0){
    
    $c_mess = "<small class='count-mess'>".$mess."</small>";
  
  }else{
    
    $c_mess = null;
  
  }
  
  $notif = db::get_column("SELECT COUNT(*) FROM `NOTIFICATIONS` WHERE `USER_ID` = ? AND `READ` = '1'", [user('ID')]);
  
  if ($notif > 99){
    
    $c_notif = "<small class='count-mess'>99+</small>";
  
  }elseif ($notif > 0){
    
    $c_notif = "<small class='count-mess'>".$notif."</small>";
  
  }else{
    
    $c_notif = null;
  
  }
  
  $ta = db::get_column("SELECT COUNT(*) FROM `TAPE` WHERE `USER_ID` = ? AND `READ` = '1'", [user('ID')]);
  
  if ($ta > 99){
    
    $c_ta = "<small class='count-mess'>99+</small>";
  
  }elseif ($ta > 0){
    
    $c_ta = "<small class='count-mess'>".$ta."</small>";
  
  }else{
    
    $c_ta = null;
  
  }
  
?>

<a href='/account/cabinet/' class='panel-bottom' id='cabinet'>
<span style='top: -6px;'><?=icons('th-large', 26)?></span>  
<b><?=lg('Кабинет')?></b>
</a>
  
<a href='/account/mail/' class='panel-bottom' id='mail'>
<o id='count_mail'><?=$c_mess?></o>  
<span><?=icons('envelope', 25)?></span>  
<b><?=lg('Почта')?></b> 
</a>
  
<a href='/account/journal/' class='panel-bottom' id='journal'>
<o id='count_notif'><?=$c_notif?></o>
<span><?=icons('bell', 25)?></span>  
<b><?=lg('Журнал')?></b>  
</a>
  
<a href='/account/tape/' class='panel-bottom' id='tape'>
<o id='count_tape'><?=$c_ta?></o> 
<span><?=icons('feed', 26)?></span>  
<b><?=lg('Лента')?></b>  
</a>
  
<a href='/id<?=user('ID')?>' class='panel-bottom' id='account'>
<span><?=icons('user-circle', 25)?></span>  
<b><?=lg('Страница')?></b>  
</a>
  
<?php } ?>  

</div>
  
<div class='panel-bottom-optimize2'></div>